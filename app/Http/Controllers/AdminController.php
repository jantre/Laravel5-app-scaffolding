<?php namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('owner');
    }


    public function getIndex()
    {
        $usersObj = User::with('roles')->paginate(10);
    /*
     * The only reason we are really converting users to an array here
     * rather tha just passing it to the view, is because we have some logic
     * around the roles.
     * set roles as comma separated string.
     */
    $users = $usersObj->getCollection()->toArray();
        $count = 0;
        foreach ($users as $user)
        {
            if (!empty($user['roles'])) {
                $role = [];
                foreach ($user['roles'] as $user_role)
                {
                    $role[] = $user_role['name'];
                }
                $users[$count]['roles'] = implode(',', $role);
            }
            else
            {
                $users[$count]['roles'] = "Unassigned";
            }
            $count++;
        }
        return view('admin.users')->withUsers($users)->withTotal($usersObj->total())->withPagination($usersObj->render());
    }
}
