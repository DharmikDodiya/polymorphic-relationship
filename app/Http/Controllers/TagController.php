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
        
        // $ids = $request->reel_id;
        $tag = new Tag();
        $tag->tag_name = $request->input('tag_name');
        $tag->save();
        // $tag->reels()->attach($ids);

        return $this->success('tag created successfully',$tag);
    }

    public function list(){
        $tag = Tag::all();

        if(is_null($tag)){
            return $this->DataNotFound();
        }
        return $this->success('tag List',$tag);
    }

    public function get($id){
        $tag = Tag::with('reels','articals')->find($id);
        
        if(is_null($tag)){
            return $this->DataNotFound();
        }
        return $this->success('get reel and artical by tag id',$tag);
    }

    public function destory($id){
        $tag = Tag::findOrFail($id);
        if(is_null($tag)){
            return $this->DataNotFound();
        }
        $tag->delete();
        //$tag->reels()->detach();
        return $this->success('tag deleted successfully');
    }
}
