<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateIntervieweeTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()->hasPermissionTo('update-intervieweeTask');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "interviewee_id" => "required|exists:interviewees,id",
            "task_id" => "required|exists:tasks,id",
            "score" => "nullable|numeric",
            "attachment" => "nullable|file|mimes:pdf|min:100|max:500",
            "comment" => "nullable|string",
            "detail" => "nullable|array",
        ];
    }
}
