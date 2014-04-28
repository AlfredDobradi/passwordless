<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserAttemptsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_attempt', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id')->index;
			$table->boolean('success')->default(false);
			$table->string('login_hash',40);
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
		Schema::drop('user_attempts');
	}

}
