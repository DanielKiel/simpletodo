<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Tenants extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('name');

            $table->timestamps();
            $table->softDeletes();

            $table->index(['id', 'deleted_at']);
        });

        try {
            Schema::table('users', function(Blueprint $table) {
                $table->integer('tenants_id')->unsigned();

                $table->foreign('tenants_id')->references('id')->on('tenants');
            });

            Schema::table('lists', function(Blueprint $table) {
                $table->integer('tenants_id')->unsigned();

                $table->foreign('tenants_id')->references('id')->on('tenants');
            });

            Schema::table('lists_history', function(Blueprint $table) {
                $table->integer('tenants_id')->unsigned();

                $table->foreign('tenants_id')->references('id')->on('tenants');
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
                $table->dropForeign('lists_tenants_id_foreign');
            });

            Schema::table('users', function(Blueprint $table) {
                $table->dropForeign('users_tenants_id_foreign');
            });

            Schema::table('lists_history', function(Blueprint $table) {
                $table->dropForeign('lists_history_tenants_id_foreign');
            });
        }
        catch (\Exception $e) {
            dump($e->getMessage());
        }

        Schema::dropIfExists('tenants');
    }
}
