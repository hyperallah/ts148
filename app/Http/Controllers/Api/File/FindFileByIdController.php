<?php


namespace App\Http\Controllers\Api\File;

use App\Actions\File\FindFileByIdAction;
use App\Http\Controllers\Api\ApiController as BaseController;
use App\Http\Requests\FindFileByIdRequest;
use OpenApi\Annotations as OA;

class FindFileByIdController extends BaseController
{
    private FindFileByIdAction $findFileByIdAction;
    public function __construct(FindFileByIdAction $findFileByIdAction)
    {
        $this->findFileByIdAction = $findFileByIdAction;
    }


    /**
     * @OA\Get(
     *     description="Get file by specified id",
     *     path="/api/v1/files/{id}",
     *     tags={"Files"},
     *     summary="Get file by specified id",
     *     @OA\Response(
     *     response="200",
     *     description="Response with file",
     *     @OA\JsonContent(),
     *  ),
     *        @OA\Parameter(
     *       name="id",
     *       in="path",
     *       required=true,
     *       @OA\Schema(
     *            type="string"
     *       )
     *    ),
     *     @OA\Response(
     *     response="404",
     *     description="Requested file with provided id is not found",
     *     @OA\JsonContent(),
     *  )
     * ),
     * )
     */
    public function __invoke(FindFileByIdRequest $request)
    {
        return response($this->findFileByIdAction->run($request));
    }
}
