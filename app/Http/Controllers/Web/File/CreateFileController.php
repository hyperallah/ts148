<?php

namespace App\Http\Controllers\Web\File;

use App\Http\Controllers\Web\WebController as BaseController;

class CreateFileController extends BaseController
{
    public function __invoke()
    {
        return view("upload");
    }
}
