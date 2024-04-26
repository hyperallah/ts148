<?php

namespace App\Http\Controllers\Web\File;

use App\Http\Controllers\Web\WebController as BaseController;
use App\Http\Requests\FileDownloadAsZipRequest;
use ZipArchive;

class DownloadFileAsZipController extends BaseController
{
    public function __invoke(FileDownloadAsZipRequest $request)
    {
        $fileName = $request->filename;
        $filePath = public_path("\attachments\\{$fileName}");

        $zip = new ZipArchive();
        $fileNameWithoutExtension = preg_split("/\./", "$fileName", -1, PREG_SPLIT_NO_EMPTY)[0];
        if ($zip->open("attachments\\{$fileName}" . ".zip",  ZipArchive::CREATE)) {
            $zip->addFile($filePath, $fileName);
        }
        $zip->close();

        return response()->download(public_path("attachments\\{$fileName}" . ".zip"));
    }
}
