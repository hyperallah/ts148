<?php

namespace App\Http\Controllers\Web\File;

use App\Http\Controllers\Web\WebController as BaseController;

class UploadFilesController extends BaseController
{
    public function __construct()
    {
    }


    public function __invoke()
    {
        return response("resource created", 201);
    }
}
