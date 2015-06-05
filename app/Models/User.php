<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Role;
class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	//protected $fillable = ['username', 'email', 'password','confirmation_code','status'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token'];

	/*
	Allow 'Mass assignment' of all fields except id and password.
	See http://laravel.com/docs/4.2/eloquent#mass-assignment
	*/
  protected $guarded = array('id');

  // This will allow us to attach a role to a user.
  // Example: To give the first user the role that contains ID 3
  //          User::first()->role()->attach(3)
  public function roles(){
   return $this->belongsToMany('App\Models\Role')->withTimestamps();
  }

}
