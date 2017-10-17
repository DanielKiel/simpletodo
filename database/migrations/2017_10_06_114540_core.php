<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Core extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lists', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id')->unsigned();
            $table->text('token');
            $table->text('title');
            $table->longText('description')->nullable();

            $table->integer('created')->unsigned();
            $table->integer('updated')->unsigned();
            $table->integer('version');

            $table->timestamps();
            $table->softDeletes();

            $table->index(['id', 'deleted_at']);
        });

        Schema::create('lists_history', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id')->unsigned();
            $table->bigInteger('lists_id')->unsigned();
            $table->text('token');
            $table->text('title');
            $table->longText('description')->nullable();

            $table->integer('created')->unsigned();
            $table->integer('updated')->unsigned();
            $table->integer('version');

            $table->timestamps();
            $table->softDeletes();

            $table->index(['id', 'deleted_at']);
        });

        //make relations
        try {
            Schema::table('lists', function(Blueprint $table) {
                $table->foreign('created')->references('id')->on('users');
                $table->foreign('updated')->references('id')->on('users');
            });

            Schema::table('lists_history', function(Blueprint $table) {
                $table->foreign('created')->references('id')->on('users');
                $table->foreign('updated')->references('id')->on('users');
                $table->foreign('lists_id')->references('id')->on('lists');
            });
        }
        catch (\Exception $e) {
            dump($e->getTraceAsString());
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
            Schema::table('lists', function(Blueprint $table) {
                $table->dropForeign('lists_created_foreign');
                $table->dropForeign('lists_updated_foreign');
            });

            Schema::table('lists_history', function(Blueprint $table) {
                $table->dropForeign('lists_history_created_foreign');
                $table->dropForeign('lists_history_updated_foreign');
                $table->dropForeign('lists_history_lists_id_foreign');
            });
        }
        catch (\Exception $e) {
            dump($e->getTraceAsString());
        }



        Schema::dropIfExists('lists');
        Schema::dropIfExists('lists_history');
    }
}
