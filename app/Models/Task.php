<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use \OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Task extends Model implements AuditableContract
{
    /** @use HasFactory<\Database\Factories\TaskFactory> */
    use HasFactory, HasUuids, SoftDeletes, Auditable;

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
