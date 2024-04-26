<?php

namespace App\Services;

use App\Exceptions\RepositoryResourceFailedException;
use App\Repositories\FileRepository;
use App\Support\Services\IFileService;
use App\Support\Utils\FileUtils;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File as SymfonyFile;

class FileService implements IFileService
{
    private FileRepository $fileRepository;

    public function __construct(FileRepository $fileRepository)
    {
        $this->fileRepository = $fileRepository;
    }

    /**
     * @throws RepositoryResourceFailedException
     * @param array $options
     *  Options: pagination per-page num, sort col, sort direction.
     */
    public function getAll(
        array $options
    ) {

        return $this->fileRepository->getAll()
            ->sortBy($options["sortBy"] ?? null, $options["orderDirection"] ?? null)
            ->paginate($options["perPage"] ?? null);
    }

    public function findById(string $id)
    {
        return $this->fileRepository->findById($id);
    }

    public function createAttachmentsInStorage(array $attachments)
    {
        for ($i = 0; $i < sizeof($attachments); $i++) {
            $withName = $attachments[$i]["name"] . "." . $attachments[$i]['extension'];
            Storage::disk("public")->putFileAs("attachments", new SymfonyFile($attachments[$i]["temp_path"]), $withName);
        }
    }

    /**
     * @throws RepositoryResourceFailedException
     */
    public function uploadAttachments(array $attachments)
    {
        // sanitize raw attachments from request.
        // then: at the out we'll get reflected array of attachments.
        // which (in this case), used to write files to the storages, and create rows in-the db.
        $preparedToUploadAttachments = $this->handleAttachments(collect($attachments));

        $this->createAttachmentsInStorage($preparedToUploadAttachments);

        // exclude a 'temp_path' key from each element of array.
        for ($i = 0; $i < sizeof($preparedToUploadAttachments); $i++) {
            unset($preparedToUploadAttachments[$i]["temp_path"]);
        }

        // insert and return;
         return $this->fileRepository->create($preparedToUploadAttachments)->queryAll();
    }

    /**
     * Important: Collisions cannot exceed more than one, so we'll get latest instance of
     * @param string $name
     * @return mixed
     */
    public function findCollisionByFileName(string $name)
    {
        return $this->fileRepository->findByName($name)->queryAll()->last();
    }

    /**
     * Determining, if file has a collision, using his name.
     * @param array $attachment
     * @return bool
     */
    public function hasFileCollisions(array $attachment) : bool
    {
       $collision = $this->findCollisionByFileName($attachment["name"]);

       // returns true if collision "exists" and has same extension and name with attachment;
       if (!is_null($collision) && $attachment["extension"] == $collision["extension"]) {
           return true;
       }

       // hasn't collision
       return false;
    }

    /** handling and transform before uploading to the database and file storage
     * @param Collection $attachments
     * @return array
     */
    public function handleAttachments(Collection $attachments) : array
    {
        // transform collection of UploadedFile objects to array;
        $raw = FileUtils::toArray($attachments);

        // adding field "name" with random file name to each element of the array
        $enrichedAttachments = FileUtils::enrichAttachmentsWithRandomName($raw);

        // existence check each "random" name.
        $attachmentsWithCollision = [];
        $uniqueAttachments = [];
        for ($j = 0; $j < sizeof($enrichedAttachments); $j++) {
            if($this->hasFileCollisions($enrichedAttachments[$j])) {
                $attachmentsWithCollision[$j] = $enrichedAttachments[$j];
            } else {
                $uniqueAttachments[$j] = $enrichedAttachments[$j];
            }
        }

        // then: resolving filename collisions, if exists.
        $handled = [];
        if (sizeof($attachmentsWithCollision) > 0) {
            // returns resolved collisions.
            $resolvedCollisions = $this->resolveFileNameCollisions($attachmentsWithCollision);
            $handled = array_merge($handled, $resolvedCollisions);
        } else {
            $handled = $uniqueAttachments;
        }

        // finally, will be added "upload path" field for each element of attachment,
        // to be used for upload in file-storage and for stores in database.
        return FileUtils::enrichAttachmentsWithUploadPath($handled);
    }

    /**
     * Accepts array with collisions, and then returns resolved with new random file name.
     * @param array $collisions
     * @return array
     */
    public function resolveFileNameCollisions(array $collisions) : array
    {
        for ($i = 0; $i < sizeof($collisions); $i++) {
            $collisions[$i]["name"] = FileUtils::generateRandomFileName();
        }

        return $collisions;
    }
}
