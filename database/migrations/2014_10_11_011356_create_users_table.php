<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
Schema::create('users', function($table) {
			$table->increments('id');
			$table->string('username',40)->unique;
			$table->string('email',60)->unique;
			$table->string('firstname',40);
			$table->string('lastname',40);
    	$table->string('password');
			$table->boolean('status')->default(0);
      $table->string('confirmation_code')->nullable();
      $table->rememberToken();
      $table->timestamps();
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
	}

}
