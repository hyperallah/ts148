<?php

namespace App\Http\Controllers\Api\File;

use App\Actions\File\ListFilesAction;
use App\Exceptions\RepositoryResourceFailedException;
use App\Http\Controllers\Api\ApiController as BaseController;
use App\Http\Requests\ListFilesRequest;
use OpenApi\Annotations as OA;

class ListFilesController extends BaseController
{
    private ListFilesAction $listFilesAction;
    public function __construct(
        ListFilesAction $listFilesAction
    ) {
        $this->listFilesAction = $listFilesAction;
    }


    /**
     * @OA\Get(
     *     description="Get files",
     *     path="/api/v1/files/",
     *     tags={"Files"},
     *     summary="Get list of files (with pagination by default)",
     *     security={{ "passport": {} }},
     *     @OA\Response(
     *     response="200",
     *     description="List of files",
     *     @OA\JsonContent(),
     *  ),
     *      @OA\Parameter(
     *          description="Order by",
     *          in="query",
     *          name="sortDirection",
     *          required=false,
     *          @OA\Schema(type="string"),
     *          @OA\Examples(example="desc", value="desc", summary="by Descending"),
     *          @OA\Examples(example="asc", value="asc", summary="by Ascending"),
     *      ),
     *      @OA\Parameter(
     *          description="Sort by",
     *          in="query",
     *          name="sortBy",
     *          required=false,
     *          @OA\Schema(type="string"),
     *          @OA\Examples(example="id", value="id", summary="by id"),
     *          @OA\Examples(example="name", value="name", summary="by name"),
     *          @OA\Examples(example="created_at", value="created_at", summary="by creation date"),
     *      ),
     *   @OA\Parameter(
     *      name="perPage",
     *       in="query",
     *       required=false,
     *       @OA\Schema(
     *            type="int"
     *        )
     *     )
     * )
     */
    public function __invoke(ListFilesRequest $request)
    {
        $files = $this->listFilesAction->run($request);

        return response()->json($files);
    }
}
