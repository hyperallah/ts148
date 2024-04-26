<?php

namespace App\Http\Requests;

use App\Support\BaseRequest;

class FileUploadRequest extends BaseRequest
{

    public function rules(): array
    {
        return [
            'attachments' => 'max:5|array',
            'attachments.*' => 'extensions:jpg,png|image|mimetypes:image/jpeg,image/png|max:8096',
        ];
    }
}
