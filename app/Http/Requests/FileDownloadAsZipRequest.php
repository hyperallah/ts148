<?php

namespace App\Http\Requests;

use App\Support\BaseRequest;

class FileDownloadAsZipRequest extends BaseRequest
{

    public function rules(): array
    {
        return [
//            'filename' => "required",
        ];
    }
}
