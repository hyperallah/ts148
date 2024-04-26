<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FindFileByIdRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => 'string|exists:files',
        ];
    }
}
