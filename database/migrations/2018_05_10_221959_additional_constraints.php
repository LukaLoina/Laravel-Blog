<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AdditionalConstraints extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
         Schema::table('post_tag', function (Blueprint $table) {
             $table->dropForeign(['tag_id']);
             $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
             $table->dropForeign(['post_id']);
             $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
        });

         Schema::table('likes', function (Blueprint $table) {
             $table->dropForeign(['post_id']);
             $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
             $table->dropForeign(['user_id']);
             $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

         Schema::table('comments', function (Blueprint $table) {
             $table->dropForeign(['post_id']);
             $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
             $table->dropForeign(['user_id']);
             $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('post_tag', function (Blueprint $table) {
            $table->dropForeign(['tag_id']);
            $table->foreign('tag_id')->references('id')->on('tags');
            $table->dropForeign(['post_id']);
            $table->foreign('post_id')->references('id')->on('posts');
        });

        Schema::table('likes', function (Blueprint $table) {
             $table->dropForeign(['post_id']);
             $table->foreign('post_id')->references('id')->on('posts');
             $table->dropForeign(['user_id']);
             $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::table('comments', function (Blueprint $table) {
             $table->dropForeign(['post_id']);
             $table->foreign('post_id')->references('id')->on('posts');
             $table->dropForeign(['user_id']);
             $table->foreign('user_id')->references('id')->on('users');
        });
    }
}
