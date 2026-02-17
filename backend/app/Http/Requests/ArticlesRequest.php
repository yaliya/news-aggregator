<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticlesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search'    => ['sometimes', 'string'],
            'source'    => ['sometimes', 'string'],
            'category'  => ['sometimes', 'string'],
            'author'    => ['sometimes', 'string'],
            'date_from' => ['sometimes', 'date'],
            'date_to'   => ['sometimes', 'date', 'after_or_equal:date_from'],
            'per_page'  => ['sometimes', 'integer', 'min:1', 'max:100'],
            'sort'      => ['sometimes', 'string', 'in:published_at,-published_at,created_at,-created_at'],
        ];
    }
}
