<?php

namespace App\Http\Controllers\Api\File;

use App\Actions\File\FindFileByIdAction;
use App\Http\Controllers\Api\ApiController as BaseController;
use App\Http\Requests\FindFileByIdRequest;

class FindFileByIdController extends BaseController
{
    private FindFileByIdAction $findFileByIdAction;
    public function __construct(FindFileByIdAction $findFileByIdAction)
    {
        $this->findFileByIdAction = $findFileByIdAction;
    }

    public function __invoke(FindFileByIdRequest $request)
    {
        return response($this->findFileByIdAction->run($request));
    }
}
