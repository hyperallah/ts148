<?php

namespace App\Http\Controllers\Web\File;

use App\Actions\File\UploadFileAction;
use App\Exceptions\RepositoryResourceFailedException;
use App\Http\Controllers\Web\WebController as BaseController;
use App\Http\Requests\FileUploadRequest;

class UploadFilesController extends BaseController
{
    private UploadFileAction $uploadFileAction;
    public function __construct(
        UploadFileAction $uploadFileAction
    ) {
        $this->uploadFileAction = $uploadFileAction;
    }

    /**
     * @throws \Throwable
     * @throws RepositoryResourceFailedException
     */
    public function __invoke(FileUploadRequest $request)
    {
        $this->uploadFileAction->run($request);

        return redirect(route('files.index'), 201)
            ->with('success', 'Files has been uploaded');
    }
}
