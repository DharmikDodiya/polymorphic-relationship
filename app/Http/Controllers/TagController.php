<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use App\Traits\ResponseMessage;
use Illuminate\Support\Facades\Validator;

class TagController extends Controller
{
    use ResponseMessage;
    public function create(Request $request){
        $request->validate([
            'tag_name'   => 'required|string|max:30',
        ]);
        $tag = new Tag();
        $tag->tag_name = $request->input('tag_name');
        $tag->save();

        return $this->success('tag created successfully',$tag);
    }

    public function list(){
        $tag = Tag::all();
        return $this->success('tag List',$tag);
    }

    public function get($id){
        $tag = Tag::with('reels','articals')->findOrFail($id);
        return $this->success('get reel and artical by tag id',$tag);
    }

    public function destory($id){
        $tag = Tag::findOrFail($id);
        $tag->delete();
       
        return $this->success('tag deleted successfully');
    }
}
