<?php

namespace App\Actions\File;

use App\Http\Requests\FindFileByIdRequest;
use App\Services\FileService;

class FindFileByIdAction
{
    private FileService $fileService;
    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    public function run(FindFileByIdRequest $request)
    {
        $fileId = $request->id;
        return $this->fileService->findById($fileId);
    }
}
