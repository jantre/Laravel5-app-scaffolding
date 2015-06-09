<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class RolesTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$roles = ['member','administrator','owner'];
		foreach($roles as $role)
		{
			\App\Models\Role::create( ['name'=> $role] );
		}
	}

}
