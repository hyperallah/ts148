<?php

namespace App\Support\Utils;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class FileUtils
{
    public static function toArray(Collection $attachments): array {
        $raw = [];
        for ($i = 0; $i < sizeof($attachments); $i++) {
            $original = preg_split("/\./", $attachments[$i]->getClientOriginalName(), -1, PREG_SPLIT_NO_EMPTY);

            $original_name = $original[0];
            $extension = $original[1];

            $raw[$i]["original_name"] = $original_name;
            $raw[$i]["temp_path"] = $attachments[$i]->getPathName();
            $raw[$i]["extension"] = $extension;
        }

        return $raw;
    }

    public static function generateRandomFileName() : string
    {
        return Str::lower(Str::random(72));
    }

    public static function enrichAttachmentsWithRandomName(array $data) : array
    {
        for ($i = 0; $i < sizeof($data); $i++) {
            $data[$i]["name"] = self::generateRandomFileName();
        }

        return $data;
    }

    public static function getBaseUploadDir() : string {
        return config("attachments.storage.public", "attachments") . "/";
    }

    public static function getUploadPathFor(array $attachment) : string
    {
        return self::getBaseUploadDir() . $attachment["name"] . "." . $attachment["extension"];
    }

    public static function enrichAttachmentsWithUploadPath(array $attachments) : array
    {
        for ($i = 0; $i < sizeof($attachments); $i++) {
            $attachments[$i]["upload_path"] = self::getUploadPathFor($attachments[$i]);
        }

        return $attachments;
    }
}
