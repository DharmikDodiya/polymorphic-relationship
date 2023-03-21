<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Image;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function create(Request $request){
        $request->validate([
            'post_name'          => 'required|string|max:30',
            'image'              => 'required|file|mimes:png,jpg'
        ]);

        $post = Post::create($request->only('post_name'));

        $image = now()->timestamp.".{$request->image->getClientOriginalName()}";
        $path = $request->file('image')->storeAs('images', $image, 'public');
        $post->image()->create([
            'image'     => "/storage/{$path}"
        ]); 

        return success('Post created successfully',$post);
    }

    public function list(){
        $post = Post::all();
        return success('user data list',$post);
    }


    public function update(Request $request,Post $id){
        $request->validate( [
            'post_name'                    => 'string|max:30',
            'image'                         => 'file|mimes:png,jpg'
        ]);

            $id->update($request->only('post_name'));
            
            $image = now()->timestamp.".{$request->image->getClientOriginalName($request->image)}";
            $path = $request->file('image')->storeAs('images', $image, 'public');

            $id->image()->update([
                'image'             => "/storage/{$path}",
            ]);
            return success('post Data successfully',$id);
    }

    public function get($id){
        $post = Post::findOrFail($id);
        $post->image;
        return success('user Details',$post);
    }

    public function destory($id){
        $post = Post::findOrFail($id);
        $image = $post->image->image;
            unlink(public_path($image));
            $post->image()->delete();
            $post->delete();
            return success('post deleted successfully');
    }
}
