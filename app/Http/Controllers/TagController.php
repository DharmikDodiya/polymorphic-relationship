<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    
    public function create(Request $request){
        $request->validate([
            'tag_name'   => 'required|string|max:30',
        ]);
        $tag = new Tag();
        $tag->tag_name = $request->input('tag_name');
        $tag->save();

        return success('tag created successfully',$tag);
    }

    public function list(){
        $tag = Tag::all();
        return success('tag List',$tag);
    }

    public function update(Request $request ,Tag $id){
        $request->validate([
            'tag_name'                    => 'string|max:30',
        ]);
            $id->update($request->only('tag_name'));
            return $this->success('tag update successfully',$id);
    }

    public function get($id){
        $tag = Tag::with('reels','articals')->findOrFail($id);
        return success('get reel and artical by tag id',$tag);
    }

    public function destory($id){
        $tag = Tag::findOrFail($id);
        $tag->delete();
       
        return success('tag deleted successfully');
    }
}
