<?php

namespace App\Actions\File;

use App\Exceptions\RepositoryResourceFailedException;
use App\Http\Requests\ListFilesRequest;
use App\Services\FileService;
use Illuminate\Pagination\LengthAwarePaginator;

class ListFilesAction
{
    private FileService $fileService;

    public function __construct(
        FileService $fileService
    ) {
        $this->fileService = $fileService;
    }

    /**
     * @throws RepositoryResourceFailedException
     */
    public function run(ListFilesRequest $request): LengthAwarePaginator
    {
       $options = $request->getSortOptions();

       return $this->fileService->getAll($options);
    }
}
