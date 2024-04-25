<?php

namespace App\Http\Controllers\Web\File;

use App\Http\Controllers\Web\WebController as BaseController;
use App\Http\Requests\FindFileByIdRequest;

class FindFileByIdController extends BaseController
{
    public function __construct()
    {

    }

    public function __invoke(FindFileByIdRequest $request)
    {
    }
}
