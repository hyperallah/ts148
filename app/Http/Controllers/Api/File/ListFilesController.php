<?php

namespace App\Http\Controllers\Api\File;

use App\Actions\File\ListFilesAction;
use App\Exceptions\RepositoryResourceFailedException;
use App\Http\Controllers\Api\ApiController as BaseController;
use App\Http\Requests\ListFilesRequest;

class ListFilesController extends BaseController
{
    private ListFilesAction $listFilesAction;
    public function __construct(
        ListFilesAction $listFilesAction
    ) {
        $this->listFilesAction = $listFilesAction;
    }

    /**
     * @throws RepositoryResourceFailedException
     */
    public function __invoke(ListFilesRequest $request)
    {
        $files = $this->listFilesAction->run($request);

        return response()->json($files);
    }
}
