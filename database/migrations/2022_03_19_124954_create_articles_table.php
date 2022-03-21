<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('theme');
            $table->string('author')->nullable();
            $table->string('title');
            $table->text('description');
            $table->string('url');
            $table->string('image_url');
            $table->timestamp('published_at');
            $table->text('content');
            $table->timestamps();
            $table->unsignedBigInteger('source_id');

            $table->foreign('source_id')
                ->references('id')
                ->on('article_sources')
                ->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles');
    }
};
