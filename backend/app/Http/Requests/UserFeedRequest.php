<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserFeedRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
            'sort'     => ['sometimes', 'string', 'in:published_at,-published_at,created_at,-created_at'],
        ];
    }
}
