<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSocialAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('socialAccounts', function (Blueprint $table) {
          $table->string('provider_uid')->index();
          $table->enum('provider',['facebook','twitter','google','linkedin','github','bitbucket'])->index();
          $table->integer('user_id')->unsigned()->index();
          $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
          $table->boolean('allow_posts')->default(1);
          $table->string('oauth_token')->nullable()->default(null);
          $table->string('oauth_token_secret')->nullable()->default(null);
        });
    }

  /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('socialAccounts');
    }
}
