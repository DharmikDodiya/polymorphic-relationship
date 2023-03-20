<?php

namespace App\Http\Controllers;

use App\Models\Reel;
use App\Models\Tag;
use Illuminate\Http\Request;
use App\Traits\ResponseMessage;
use Illuminate\Support\Facades\Validator;

class ReelController extends Controller
{
    use ResponseMessage;
    public function create(Request $request){
        $validatedata = Validator::make($request->all(), [
            'reel_name'                    => 'required|string|max:30',
            'tag_id'                       => 'exists:tags,id'
        ]);
    
        if($validatedata->fails()){
            return $this->ErrorResponse($validatedata);  
        }
        else{
            $reel = Reel::create($request->only('reel_name'));
            $reel->tags()->attach($request->tag_id);
            return $this->success('reel created successfully',$reel);
        }
    }

    public function list(){
        $reel = Reel::all();
        return $this->success('reels list',$reel);
    }

    public function update(Request $request,Reel $id){
        $validatedata = Validator::make($request->all(), [
            'reel_name'                    => 'required|string|max:30',
            'tag_id'                        => 'exists:tags,id'
        ]);
    
        if($validatedata->fails()){
            return $this->ErrorResponse($validatedata);  
        }
        else{
            $tagids = $request->tag_id;
            $id->update($request->only('reel_name'));
            $id->tags()->sync($tagids);
            return $this->success('Updated reel and tag successfully',$id->tags);
        }
    }


    public function get($id){
        $reel = Reel::with('tags')->findOrFail($id);
        
        return $this->success('reel Details',$reel);
    }

    public function destory($id){
        $reel = Reel::findOrFail($id);
            $reel -> delete();
            $reel->tags()->delete();
            return $this->success('reel deleted successfully');
        
    }
}
