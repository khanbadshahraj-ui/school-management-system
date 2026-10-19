<?php

namespace App\Http\Controllers;

use App\Models\Campus;
use App\Models\Department;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class SchoolController extends Controller
{
    private function admin(Request $request): void
    {
        abort_unless($request->user()->role === 'school_admin', 403);
    }

    public function dashboard(Request $request)
    {
        return Inertia::render('Dashboard', ['counts' => ['students' => Student::count(), 'campuses' => Campus::count(), 'departments' => Department::count()],
            'students' => Student::latest()->limit(6)->get()]);
    }

    public function settings(Request $request)
    {
        $this->admin($request);

        return Inertia::render('Settings');
    }

    public function update(Request $request)
    {
        $this->admin($request);
        $data = $request->validate(['name' => 'required|string|max:150', 'primary_color' => 'required|regex:/^#[0-9a-fA-F]{6}$/', 'locale' => ['required', Rule::in(['en', 'ur'])], 'timezone' => 'required|timezone', 'currency' => 'required|size:3|alpha']);
        DB::transaction(function () use ($request, $data) {
            $school = $request->user()->school;
            $school->update($data);
            DB::table('audit_events')->insert(['school_id' => $school->id, 'user_id' => $request->user()->id, 'action' => 'school.settings.updated', 'created_at' => now()]);
        });

        return back()->with('success', 'School configuration saved.');
    }

    public function students(Request $request)
    {
        abort_unless(in_array($request->user()->role, ['school_admin', 'admissions', 'teacher']), 403);
        $search = substr((string) $request->query('search', ''), 0, 100);
        $query = Student::query();
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'ilike', '%'.$search.'%')->orWhere('last_name', 'ilike', '%'.$search.'%')->orWhere('admission_number', 'ilike', '%'.$search.'%');
            });
        }

        return Inertia::render('Students', ['students' => $query->latest()->paginate(20)->withQueryString(), 'campuses' => Campus::orderBy('name')->get(), 'search' => $search]);
    }

    public function storeStudent(Request $request)
    {
        abort_unless(in_array($request->user()->role, ['school_admin', 'admissions']), 403);
        $sid = $request->user()->school_id;
        $data = $request->validate(['first_name' => 'required|string|max:100', 'last_name' => 'required|string|max:100', 'admission_number' => ['required', 'string', 'max:50', Rule::unique('students')->where('school_id', $sid)], 'campus_id' => ['required', 'integer', Rule::exists('campuses', 'id')->where('school_id', $sid)]]);
        DB::transaction(function () use ($request, $data, $sid) {
            $student = Student::create([...$data, 'status' => 'enrolled']);
            DB::table('audit_events')->insert(['school_id' => $sid, 'user_id' => $request->user()->id, 'action' => 'student.created', 'subject_id' => $student->id, 'created_at' => now()]);
        });

        return back()->with('success', 'Student enrolled.');
    }

    public function organization(Request $request)
    {
        $this->admin($request);

        return Inertia::render('Organization', ['campuses' => Campus::orderBy('name')->get(), 'departments' => Department::orderBy('name')->get()]);
    }

    public function storeOrganization(Request $request, string $kind)
    {
        $this->admin($request);
        abort_unless(in_array($kind, ['campuses', 'departments']), 404);
        $data = $request->validate(['name' => 'required|string|max:150', 'code' => ['required', 'string', 'max:30', Rule::unique($kind)->where('school_id', $request->user()->school_id)]]);
        $model = $kind === 'campuses' ? Campus::class : Department::class;
        $model::create($data);

        return back()->with('success', 'Organization updated.');
    }
}
