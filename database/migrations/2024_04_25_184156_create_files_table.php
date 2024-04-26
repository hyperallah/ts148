<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string("upload_path");
            $table->string("original_name");
//            $table->string("original_extension");
            $table->string("extension");
            $table->string("name", 255)->unique("name");
           // i.e ALTER TABLE files ADD FULLTEXT  name(`name_idx`);
            $table->fullText("name");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
