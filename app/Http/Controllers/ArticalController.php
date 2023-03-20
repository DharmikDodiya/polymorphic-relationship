<?php

namespace App\Http\Controllers;

use App\Models\Artical;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ArticalController extends Controller
{
    public function create(Request $request){
        $request->validate([
            'artical_name'                    => 'required|string|max:30',
            'tag_id'                          => 'exists:tags,id|numeric'
        ]);
    
            $artical = Artical::create($request->only('artical_name'));
            $artical->tags()->attach($request->tag_id);
            return $this->success('artical created successfully',$artical);
        
    }

    public function list(){
        $artical = Artical::all();
        return success('articals list',$artical);
    }

    public function update(Request $request,Artical $id){
        $request->validate([
            'artical_name'                    => 'string|max:30',
            'tag_id'                          => 'exists:tags,id|numeric'
        ]);

            $tagids = $request->tag_id;
            $id->update($request->only('artical_name'));
            $id->tags()->sync($tagids);
            return success('artical comment add and update successfully',$id);
    }


    public function get($id){
        $artical = Artical::with('tags')->findOrFail($id);
        return success('artical with tags data',$artical);
    }

    public function destory($id){
        $artical = Artical::findOrFail($id);
            $artical -> delete();
            $artical->tags()->detach();
            return success('artical with tags deleted successfully');
    }
}
