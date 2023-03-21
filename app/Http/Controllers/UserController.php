<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function create(Request $request){
        $request->validate([
            'name'          => 'required|string|max:30',
            'email'         => 'required|email|unique:users,email',
            'password'      => 'required|min:6|max:12',
            'image'         => 'required|file|mimes:jpg,png'
        ]);

        $user = User::create($request->only(['name','email'])
        +[
            'password'      => Hash::make($request->password),
        ]
        );
        $image = now()->timestamp.".{$request->image->getClientOriginalName()}";
        $path = $request->file('image')->storeAs('images', $image, 'public');
        $user->image()->create([
            'image'     => "/storage/{$path}"
        ]);
        return success('user created successfully',$user);
    }

    public function list(){
        $user = User::all();
        return success('user list',$user);
    }

    public function update(Request $request,User $id){
        $request->validate([
                'name'                    => 'string|max:30',
                'image'                   => 'file|mimes:png,jpg'
        ]);
            $id->update($request->only('name'));
        
            $image = now()->timestamp.".{$request->image->getClientOriginalName($request->image)}";
            $path = $request->file('image')->storeAs('images', $image, 'public');

            $id->image()->update([
                'image'             => "/storage/{$path}",
            ]);
            return success('Updated user Data successfully',$id);
        
    }


    public function get($id){
        $user = User::findOrFail($id);
        
        $user->image;
        return success('user Details',$user);
    }

    public function destory($id){
        $user = User::findOrFail($id);
            $user->image()->delete();
            $user -> delete();
            return success('user deleted successfully');
    }

    public function latestImage($id){
        $userimage = User::with('latestImage')->findOrFail($id);
        return success('latest user iamge',$userimage);
    }
}
