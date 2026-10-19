<?php

namespace App\Models;

use App\Models\Concerns\BelongsToSchool;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use BelongsToSchool;

    protected $fillable = ['name', 'code'];
}
