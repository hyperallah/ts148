<?php

namespace App\Http\Controllers\Web\File;

use App\Actions\File\ListFilesAction;
use App\Exceptions\RepositoryResourceFailedException;
use App\Http\Controllers\Web\WebController as BaseController;
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
        $data = $this->listFilesAction->run($request);

        return view('files', compact('data'));
    }
}
