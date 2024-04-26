<?php

namespace App\Http\Controllers\Web\File;

use App\Http\Controllers\Web\WebController as BaseController;
use App\Http\Requests\FileDownloadAsZipRequest;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use ZipArchive;

class DownloadFileAsZipController extends BaseController
{
    public function __invoke(FileDownloadAsZipRequest $request)
    {
        $fileName = $request->filename;
        $filePath = public_path("\attachments\\{$fileName}");
        $fileDir = "\attachments\\{$fileName}";

        if(!Storage::disk("public")->exists($fileDir)) {
            throw new NotFoundHttpException();
        }

        $zip = new ZipArchive();
        if ($zip->open("attachments\\{$fileName}" . ".zip",  ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            $zip->addFile($filePath, $fileName);
            if($zip->close()) {
                return response()->download(public_path("attachments\\{$fileName}" . ".zip"));
            }
            throw new \Exception("Something went wrong", 500);
        }
    }
}
