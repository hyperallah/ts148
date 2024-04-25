<?php

namespace App\Http\Controllers\Web\File;

use App\Http\Controllers\Web\WebController as BaseController;
use App\Http\Requests\ListFilesRequest;

class ListFilesController extends BaseController
{

    public function __construct()
    {
    }


    public function __invoke(ListFilesRequest $request)
    {
        return view('files');
    }
}
