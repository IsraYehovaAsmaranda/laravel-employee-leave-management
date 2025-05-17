<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()->hasPermissionTo('create-task');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required',
            'description' => 'required',
            'due_date' => 'required|date|after:today',
            'criteria' => 'required|array',
            'criteria.*.title' => 'required',
            'criteria.*.description' => 'required',
            'criteria.*.weight' => 'required|numeric|min:1',
            'attachment' => 'nullable|file|mimes:pdf|min:100|max:500',
        ];
    }
}
