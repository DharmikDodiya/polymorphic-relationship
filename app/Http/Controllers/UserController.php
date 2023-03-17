<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Image;
use Illuminate\Http\Request;
use App\Traits\ResponseMessage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    use ResponseMessage;

    public function create(Request $request){
        $request->validate([
            'name'          => 'required|string|max:30',
            'email'         => 'required|email|unique:users,email',
            'password'      => 'required|min:6|max:12',
            'image'         => 'required|file'
        ]);

        $user = User::create($request->only('name','email','password'));
        $image = now()->timestamp.".{$request->image->getClientOriginalName()}";
        $path = $request->file('image')->storeAs('images', $image, 'public');

        Image::create([
            'imageable_id'  => $user->id,
            'image'         => "/storage/{$path}",
            'imageable_type'=> 'App\Models\User'   
        ]);
        return $this->success('user created successfully',$user);
    }

    public function list(){
        $user = User::all();

        if(is_null($user)){
            return $this->DataNotFound();
        }
        return $this->success('user data list',$user);
    }

    public function update(Request $request,User $id){
        $validatedata = Validator::make($request->all(), [
            'name'                    => 'string|max:30',
            'image'                   => 'file'
        ]);
    
        if($validatedata->fails()){
            return $this->ErrorResponse($validatedata);  
        }
        else{
        
            $id->update($request->only('name'));
        
            $image = now()->timestamp.".{$request->image->getClientOriginalName($request->image)}";
            $path = $request->file('image')->storeAs('images', $image, 'public');

            $id->image()->update([
                'image'             => "/storage/{$path}",
            ]);
            return $this->success('Updated user Data successfully',$id);
        }
    }


    public function get($id){
        $user = User::find($id);
        
        if(is_null($user)){
            return $this->DataNotFound();
        }
        $user->image;
        return $this->success('user Details',$user);
    }

    public function destory($id){
        $user = User::find($id);

        if(is_null($user)){
            return $this->DataNotFound();
        }
        else{
            
            //$image = $user->image->image;
            //dd($image);
            //unlink(public_path($image));
            $user->image()->delete();
            $user -> delete();
            return $this->success('user deleted successfully');
        }
    }
}
