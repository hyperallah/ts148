<?php

namespace Database\Factories;

use App\Support\Utils\FileUtils;
use Illuminate\Database\Eloquent\Factories\Factory;

class FileFactory extends Factory
{
    public function definition(): array
    {
        $fileExtension = ".png";
        $name = FileUtils::generateRandomFileName();

        return [
            'upload_path' => FileUtils::getBaseUploadDir() . $name,
            'original_name' => $this->faker->name() . $fileExtension,
            'extension' => $fileExtension,
            'name' => $name
        ];
    }
}
