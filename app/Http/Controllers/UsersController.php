<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Auth;
use App\Handlers\ImageUploadHandler;



class UsersController extends Controller
{

	public function __construct(){

		$this->middleware('auth',['except' => ['show']]);
	}


    public function show(User $user){
    	
        return view('users.show', compact('user'));
    }


    public function edit(User $user){
    	$this->authorize('update' , $user);

    	return view('users.edit' , compact('user'));
    }


    public function update(UserRequest $request , User $user , ImageUploadHandler $uploader){
    	$this->authorize('update' , $user);
    	
    	$data = $request->all();
    	if ($request->avatar) {
    		$result = $uploader->save($request->avatar , 'avatars' , Auth::id());
    		if ($result) {
    			$data['avatar'] = $result['path'];
    		}
    	}

    	$user->update($data);
    	return redirect()->route('users.show' , $user)->with('success' , '个人资料更新成功');
    }




}
