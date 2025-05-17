<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Interviewee extends Model implements AuditableContract
{
    /** @use HasFactory<\Database\Factories\IntervieweeFactory> */
    use HasFactory, HasUuids, SoftDeletes, Auditable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'cv',
        'metadata'
    ];

    protected $casts = [
        'metadata' => 'array'
    ];

    public function tasks()
    {
        return $this->hasMany(IntervieweeTask::class);
    }
}
