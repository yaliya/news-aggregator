<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserPreferencesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'source'    => ['nullable', 'array'],
            'source.*'  => ['string'],
            'category'  => ['nullable', 'array'],
            'category.*'=> ['string'],
            'author'    => ['nullable', 'array'],
            'author.*'  => ['string'],
        ];
    }
}
