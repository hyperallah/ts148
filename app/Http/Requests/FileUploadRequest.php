<?php

namespace App\Http\Requests;

use App\Support\BaseRequest;

class FileUploadRequest extends BaseRequest
{

    public function rules(): array
    {
        return [
            'attachments.*' => 'required|file|mimes:jpeg,png,jpg|max:14000',
            'attachments' => 'max:3|required',
        ];
    }
}
