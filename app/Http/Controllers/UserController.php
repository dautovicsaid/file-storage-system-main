<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{

    public function getAllUsers()
    {
        if (!auth()->user()->is_admin) {
            return redirect('files.index');
        }


        $users = User::all();
        return view('users.admin_panel', [
            'users' => $users
        ]);
    }

    public function changeStorageLimit(Request $request)
    {

        if (!auth()->user()->is_admin) {
            return redirect('files.index');
        }
        $user = User::find($request->user_id);
        $new_storage_limit = User::gb_converted_to_bytes($request->storage_limit);
        if($new_storage_limit<$user->storage_used) {

            return redirect()->back()->withErrors(['storage_resize_error' => 'Storage resize failed - storage used is bigger than new storage size.']);
        }
        $user->storage_limit = $new_storage_limit;
        $user->update();

        return redirect()->back();
    }
}
