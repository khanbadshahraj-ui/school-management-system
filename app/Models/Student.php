<?php

namespace App\Models;

use App\Models\Concerns\BelongsToSchool;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use BelongsToSchool;

    protected $fillable = ['campus_id', 'admission_number', 'first_name', 'last_name', 'status'];
}
