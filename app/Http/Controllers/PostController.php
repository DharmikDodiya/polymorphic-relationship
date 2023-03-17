<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Image;
use Illuminate\Http\Request;
use App\Traits\ResponseMessage;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    use ResponseMessage;
    public function create(Request $request){
        $request->validate([
            'post_name'          => 'required|string|max:30',
            'image'              => 'required|file'
        ]);

        $post = Post::create($request->only('post_name'));

        $image = now()->timestamp.".{$request->image->getClientOriginalName()}";
        $path = $request->file('image')->storeAs('images', $image, 'public');

        Image::create([
            'imageable_id'  => $post->id,
            'image'         => "/storage/{$path}",
            'imageable_type'=> 'App\Models\Post'   
        ]);

        return $this->success('Post created successfully',$post);
    }

    public function list(){
        $post = Post::all();

        if(is_null($post)){
            return $this->DataNotFound();
        }
        return $this->success('user data list',$post);
    }


    public function update(Request $request,Post $id){
        $validatedata = Validator::make($request->all(), [
            'post_name'                    => 'required|string|max:30',
            'image'                   => 'required|string'
        ]);
    
        if($validatedata->fails()){
            return $this->ErrorResponse($validatedata);  
        }
        else{
            $id->update($request->only('post_name'));
            $id->image()->update($request->only('image'));
            return $this->success('post Data successfully',$id);
        }
    }

    public function get($id){
        $post = Post::find($id);
        $post->image;

        if(is_null($post)){
            return $this->DataNotFound();
        }
        $post->image;
        return $this->success('user Details',$post);
    }

    public function destory($id){
        $post = Post::find($id);

        if(is_null($post)){
            return $this->DataNotFound();
        }
        else{
            $post->image()->delete();
            $post->delete();
            return $this->success('post deleted successfully');
        }
    }
}
