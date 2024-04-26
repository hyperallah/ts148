<?php

namespace web;

use App\Models\File;
use App\Support\Utils\FileUtils;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

/*
 * Attachment it's just an abstraction from the file mime type.
 * Photo, Video, Document it's "attachment" first of all.
 */
class AttachmentUploadTest extends TestCase
{
    public function uploadAttachmentsRequest(array $files)
    {
        $response = $this->post('files/store', ["attachments" => $files], ["Content-Type"  => "multipart/form-data"]);

        return $response;
    }

    /**
     * Проверка, что файлы создаются в одной директории
     * @return void
     */
    public function test_attachment_upload_directory_check(): void
    {
        $imagesCount = 5;
        $allowedExtension = '.png';

        for ($i = 0; $i < $imagesCount; $i++) {
            $names[] = FileUtils::generateRandomFileName();
        }

        for ($i = 0; $i < $imagesCount; $i++) {
            $file = $names[$i] . $allowedExtension; // filename has allowed extension
            $files[] = UploadedFile::fake()->image($file, 220, 220);
        }

        $response = $this->uploadAttachmentsRequest($files);

        $files = File::query()->whereIn("original_name", $names)->get();

        // проверяем, что каждый предыдущий путь фйла не отличается от текущего.
        $trigers = 0;
        $last = 0;
        for ($j = 0; $j < $files->count(); $j++) {
            $last = $j;
            if ($files[$j]->upload_path == $files[$last]->upload_path) {
                $trigers++;
            }
        }

        // если все 5 созданных файлов расплоложенны в одной директории, вернуть true;
        $this->assertIsBool($trigers == $imagesCount);
    }

    /** Проверяем, что после попытки залить больше 5 файлов выкидывается исключение валидации.
     * @return void
     */
    public function test_attachment_upload_more_than_five_and_get_exception(): void
    {
        $imagesCount = 7;
        $allowedExtension = '.png';

        for ($i = 0; $i < $imagesCount; $i++) {
            $names[] = FileUtils::generateRandomFileName();
        }

        for ($i = 0; $i < $imagesCount; $i++) {
            $file = $names[$i] . $allowedExtension; // filename has allowed extension
            $files[] = UploadedFile::fake()->image($file, 220, 220);
        }

        $response = $this->uploadAttachmentsRequest($files);

        $response->assertInvalid("attachments");
    }

    /** Пытаемся залить файл с уже существующим (оригинальным) именем файла, проверяем что оригинальное имя не перезаписывается.
     * @return void
     */
    public function test_attachment_upload_file_that_already_exists_and_check_has_not_overwriten(): void
    {
        $allowedExtension = '.png';
        $notUniqueName = FileUtils::generateRandomFileName(); // генерирует случайное оригинальное имя лишь один раз

        for ($i = 0; $i < 3 ; $i++) {
            $file = $notUniqueName . $allowedExtension;
            $files[] = UploadedFile::fake()->image($file, 220, 220);
        }

        $response = $this->uploadAttachmentsRequest($files);

        $storedNames = File::query()->where("original_name", $notUniqueName);

        $this->assertTrue($storedNames->count() == 3);
    }

    /** Пытаемся залить файл, проверяем что его имя отличается от оригинального, тоесть априоре меняется и есть "уникальное".
     * @return void
     */
    public function test_attachment_upload_file_that_already_exists_and_check_it_has_been_renamed(): void
    {
        $allowedExtension = '.png';
        $name = "qwerty";
        $file = $name . $allowedExtension;

        $files[] = UploadedFile::fake()->image($file, 220, 220);

        $response = $this->uploadAttachmentsRequest($files);

        $storedFile = File::query()->where("original_name", $name)->get()->last();


        // проверяем, что оригинальное имя (под которым файл был залит) ОТЛИЧАЕТСЯ от имени файла (под которым файл был записан в бд и хранилище).
        $this->assertTrue($storedFile->name !== $name);
    }

    /**
     * проверяем правила валидации на невозможность залить файл больше чем 8000 кб
     * @return void
     */
    public function test_attachment_file_size_validation()
    {
        $allowedExtension = '.png';
        $name = "qwerty";
        $file = $name . $allowedExtension;

        $response = $this->post('files/store', [
            'attachments' => [UploadedFile::fake()->create($file, 4000, "image/png")]
        ], ["Content-Type"  => "multipart/form-data"]);
        $response->assertValid("attachments.0");

        $response = $this->post('files/store', [
            'attachments' => [UploadedFile::fake()->create($file, 9000, "image/png")]
        ], ["Content-Type"  => "multipart/form-data"]);
        $response->assertInvalid("attachments.0");
    }

    /**
     * проверяем правила валидации на невозможность залить файл c другим типом
     * @return void
     */
    public function test_attachment_file_mimetype_validation()
    {
        $allowedExtension = '.png';
        $name = "qwerty";
        $file = $name . $allowedExtension;

        $response = $this->post('files/store', [
            'attachments' => [UploadedFile::fake()->create($file, 4000, "image/png")]
        ], ["Content-Type"  => "multipart/form-data"]);
        $response->assertValid("attachments.0");


        $notAllowedExtension = '.zip';
        $name = "qwertyzippy";
        $file = $name . $notAllowedExtension;
        $response = $this->post('files/store', [
            'attachments' => [UploadedFile::fake()->create($file, 1337, "application/zip")]
        ], ["Content-Type"  => "multipart/form-data"]);
        $response->assertInvalid("attachments.0");
    }

    /**
     * Проверяем правила валидации на невозможность залить файл c другим типом
     * @return void
     */
    public function test_attachment_file_extension_validation() : void
    {
        $allowedExtension = '.png';
        $name = "qwerty";
        $file = $name . $allowedExtension;

        $response = $this->post('files/store', [
            'attachments' => [UploadedFile::fake()->create($file, 4000)]
        ], ["Content-Type"  => "multipart/form-data"]);
        $response->assertValid("attachments.0");


        $notAllowedExtension = '.mp4';
        $name = "qwertysexvideo";
        $file = $name . $notAllowedExtension;
        $response = $this->post('files/store', [
            'attachments' => [UploadedFile::fake()->create($file, 1337)]
        ], ["Content-Type"  => "multipart/form-data"]);
        $response->assertInvalid("attachments.0");
    }

    /**
     * @return void
     */
    public function test_download_file_as_zip() : void
    {
        $allowedExtension = '.png';
        $name = "qwerty";
        $file = $name . $allowedExtension;

        $files[] = UploadedFile::fake()->image($file, 220, 220);
        $this->uploadAttachmentsRequest($files);

        $storedFile = File::query()->where("original_name", $name)->get()->last();

        $response = $this->get("files/download/" . $storedFile->name . $allowedExtension);
        $response->assertStatus(200);
    }
}
