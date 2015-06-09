<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class UsersTableSeeder extends Seeder {

  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $faker = Faker\Factory::create();

   foreach(range(1,50) as $index)
   {
     User::create([
      'username' => $faker->username,
      'email' => $faker->email,
       'firstname' => $faker->firstName,
       'lastname' => $faker->lastName,
       'password' => bcrypt($faker->word),
       'status' =>1
     ])->assignRole('member');
   }
  }

}
