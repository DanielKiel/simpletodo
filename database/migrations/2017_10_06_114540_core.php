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
            $table->string('token');
            $table->string('type')->default('default');

            $table->integer('created')->unsigned();
            $table->integer('updated')->unsigned();
            $table->integer('version');
            $table->integer('weight')->default(0);

            $table->string('title');
            $table->longText('description')->nullable();
            $table->json('data')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['id', 'deleted_at']);
            $table->index('token');
            $table->index('type');
        });

        Schema::create('lists_history', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id')->unsigned();
            $table->bigInteger('lists_id')->unsigned();
            $table->string('token');
            $table->string('title');
            $table->string('type');

            $table->integer('created')->unsigned();
            $table->integer('updated')->unsigned();
            $table->integer('version');
            $table->integer('weight')->default(0);

            $table->longText('description')->nullable();
            $table->json('data')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['id', 'deleted_at']);
            $table->index('token');
            $table->index('type');
        });

        Schema::create('shared_lists', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id')->unsigned();
            $table->string('token');

            $table->integer('to')->unsigned();
            $table->boolean('following')->default('0');

            $table->timestamps();
            $table->softDeletes();

            $table->index(['id', 'deleted_at']);
            $table->index('token');
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

            Schema::table('shared_lists', function(Blueprint $table) {
                $table->foreign('to')->references('id')->on('users');
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
            Schema::table('lists', function(Blueprint $table) {
                $table->dropForeign('lists_created_foreign');
                $table->dropForeign('lists_updated_foreign');
            });

            Schema::table('shared_lists', function(Blueprint $table) {
                $table->dropForeign('shared_lists_to_foreign');
            });

            Schema::table('lists_history', function(Blueprint $table) {
                $table->dropForeign('lists_history_created_foreign');
                $table->dropForeign('lists_history_updated_foreign');
                $table->dropForeign('lists_history_lists_id_foreign');
            });
        }
        catch (\Exception $e) {
            dump($e->getMessage());
        }

        Schema::dropIfExists('lists_history');
        Schema::dropIfExists('shared_lists');
        Schema::dropIfExists('lists');
    }
}
