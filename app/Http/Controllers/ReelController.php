<?php

namespace App\Http\Controllers;

use App\Models\Reel;
use Illuminate\Http\Request;

class ReelController extends Controller
{
    
    public function create(Request $request){
        $request->validate([
            'reel_name'                    => 'required|string|max:30',
            'tag_id'                       => 'exists:tags,id'
        ]);

            $reel = Reel::create($request->only('reel_name'));
            $reel->tags()->attach($request->tag_id);
            return success('reel created successfully',$reel);
        
    }

    public function list(){
        $reel = Reel::all();
        return success('reels list',$reel);
    }

    public function update(Request $request,Reel $id){
        $request->validate([
            'reel_name'                    => 'required|string|max:30',
            'tag_id'                        => 'exists:tags,id'
        ]);
            $tagids = $request->tag_id;
            $id->update($request->only('reel_name'));
            $id->tags()->sync($tagids);
            return success('Updated reel and tag successfully',$id->tags);
    }

    public function get($id){
        $reel = Reel::with('tags')->findOrFail($id);
        
        return success('reel Details',$reel);
    }

    public function destory($id){
        $reel = Reel::findOrFail($id);
            $reel -> delete();
            $reel->tags()->delete();
            return success('reel deleted successfully');
    }
}
