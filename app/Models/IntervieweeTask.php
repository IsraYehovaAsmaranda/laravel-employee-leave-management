<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IntervieweeTask extends Model
{
    /** @use HasFactory<\Database\Factories\IntervieweeTaskFactory> */
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        "interviewee_id",
        "task_id",
        "score",
        "attachment",
        "comment",
        "is_graded",
        "detail"
    ];

    protected $casts = [
        "detail" => "array"
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function interviewee()
    {
        return $this->belongsTo(Interviewee::class);
    }
}
