<?php

namespace App\Http\Controllers\Web\File;

use App\Actions\File\FindFileByIdAction;
use App\Http\Controllers\Web\WebController as BaseController;
use App\Http\Requests\FindFileByIdRequest;

class FindFileByIdController extends BaseController
{
    private FindFileByIdAction $findFileByIdAction;
    public function __construct(
        FindFileByIdAction $findFileByIdAction
    ) {
        $this->findFileByIdAction = $findFileByIdAction;
    }

    public function __invoke(FindFileByIdRequest $request)
    {
        $this->findFileByIdAction->run($request);
    }
}
