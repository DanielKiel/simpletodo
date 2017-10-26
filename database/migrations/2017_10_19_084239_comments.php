<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Comments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id')->unsigned();
            $table->bigInteger('lists_id')->unsigned();
            $table->integer('by')->unsigned();
            $table->bigInteger('reply_to')->unsigned()->nullable();
            $table->integer('version');
            $table->text('content');
            $table->json('position');

            $table->timestamps();
            $table->softDeletes();

            $table->index(['id', 'deleted_at']);
        });

        try {
            Schema::table('comments', function(Blueprint $table) {
                $table->foreign('lists_id')->references('id')->on('lists');
                $table->foreign('reply_to')->references('id')->on('comments');
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
            Schema::table('comments', function(Blueprint $table) {
                $table->dropForeign('comments_lists_id_foreign');
                $table->dropForeign('comments_reply_to_foreign');
                $table->dropForeign('comments_by_foreign');
            });
        }
        catch (\Exception $e) {
            dump($e->getMessage());
        }

        Schema::dropIfExists('comments');
    }
}
