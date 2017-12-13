<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FileUploads extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('list_files', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->bigInteger('lists_id')->unsigned();
            $table->integer('by')->unsigned();
            $table->integer('version');
            $table->boolean('published')->default(1);
            $table->string('path');
            $table->json('data');

            $table->timestamps();
            $table->softDeletes();

            $table->index(['id', 'deleted_at']);
        });

        try {
            Schema::table('list_files', function(Blueprint $table) {
                $table->foreign('lists_id')->references('id')->on('lists');
                $table->foreign('by')->references('id')->on('users');
            });
        }
        catch (\Exception $e) {
            dump($e->getMessage());
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        try {
            Schema::table('list_files', function(Blueprint $table) {
                $table->dropForeign('list_files_lists_id_foreign');
                $table->dropForeign('list_files_by_foreign');
            });
        }
        catch (\Exception $e) {
            dump($e->getMessage());
        }

        Schema::dropIfExists('list_files');
    }
}
