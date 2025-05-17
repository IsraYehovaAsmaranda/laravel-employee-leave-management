<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    /** @use HasFactory<\Database\Factories\TaskFactory> */
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        "title",
        "description",
        "due_date",
        "criteria",
        "attachment"
    ];

    protected $casts = [
        "criteria" => "array",
        "due_date" => "datetime"
    ];

    public function tasks()
    {
        return $this->hasMany(IntervieweeTask::class);
    }
}
