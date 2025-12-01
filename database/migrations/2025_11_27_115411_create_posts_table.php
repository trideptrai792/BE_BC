<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::create('posts', function (Blueprint $table) {
        $table->id();

        // Nếu không dùng topic_id thì có thể giữ nullable
        $table->unsignedInteger('topic_id')->nullable();

        $table->string('title');
        $table->string('slug')->unique();

        // khớp với PostController
        $table->string('thumbnail')->nullable();   
        $table->text('excerpt')->nullable();
        $table->longText('content')->nullable();

        $table->enum('post_type', ['post', 'page'])->default('post');
        $table->tinyInteger('status')->default(1);

        $table->unsignedInteger('created_by')->nullable();
        $table->unsignedInteger('updated_by')->nullable();

        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('posts');
}
};
