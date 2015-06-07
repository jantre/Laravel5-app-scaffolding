<?php namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;

class AdminController extends Controller {

  public function __construct()
  {
    $this->middleware('owner');
  }


  public function getIndex(){
    $users = User::with('roles')->get()->toArray();
    $count = 0;
    foreach($users as $user){
      if(!empty($user['roles'])){
        $role = [];
        foreach($user['roles'] as $user_role) {
          $role[] = $user_role['name'];
        }
        $users[$count]['roles'] = implode(',',$role);

      }else{
        $users[$count]['roles'] = "Unassigned";
      }
      $count++;
    }
    return view('admin.users')->withUsers($users);
  }
}