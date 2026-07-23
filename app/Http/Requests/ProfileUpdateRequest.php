<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'prenom'    => ['required', 'string', 'max:255'],
            'nom'       => ['required', 'string', 'max:255'],
            'email'     => ['required', 'email', 'max:255', Rule::unique('wfb_users')->ignore($this->user()->id)],
            'telephone' => ['nullable', 'string', 'max:20'],
            'langue'    => ['nullable', 'in:fr,ar,en'],
        ];
    }
}
