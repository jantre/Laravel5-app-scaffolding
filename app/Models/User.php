<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use App\Models\Role;
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


	// Allow 'Mass assignment' of all fields except id.
  protected $guarded = array('id');

  /**
   * This will allow us to attach a role to a user
   * Example: To give the first user the role that contains ID 3
   *          User::first()->role()->attach(3)
   *
   * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
   */
  public function roles(){
   return $this->belongsToMany('App\Models\Role');
  }

  /**
   * Does the user have a particular role?
   *
   * @param $name
   * @return bool
   */
  public function hasRole($name)
  {
    foreach ($this->roles as $role)
    {
      if ($role->name == $name) return true;
    }
    return false;
  }
  /**
   * Assign a role to the user
   *
   * @param $role  The name of the role
   * @return mixed
   */
  public function assignRole($role)
  {
    $role_id = Role::where('name','=',$role)->first()->id;
    return $this->roles()->attach($role_id);
  }
  /**
   * Remove a role from a user
   *
   * @param $role
   * @return mixed
   */
  public function removeRole($role)
  {
    return $this->roles()->detach($role);
  }

  /**
   * This will allow us to attach a social provider login to a user
   * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
   */
  public function providers(){
    return $this->belongsToMany('App\Models\SocialAccounts');
  }

//  public function allowPost($provider, $status = true){
//    return $this->provider
//  }


}
