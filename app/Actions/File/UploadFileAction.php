<?php

namespace App\Actions\File;

use App\Exceptions\RepositoryResourceFailedException;
use App\Http\Requests\FileUploadRequest;
use App\Services\FileService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class UploadFileAction
{
    private FileService $fileService;

    public function __construct(
        FileService $fileService
    ) {
        $this->fileService = $fileService;
    }

    /**
     * @throws \Throwable
     * @throws RepositoryResourceFailedException
     * @return Collection | LengthAwarePaginator
     *
     */
    public function run(FileUploadRequest $request)
    {
        return $this->fileService->uploadAttachments($request->file("attachments"));
    }
}
