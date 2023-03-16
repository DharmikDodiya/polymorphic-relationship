<?php

namespace App\Http\Controllers;

use App\Models\Artical;
use Illuminate\Http\Request;
use App\Traits\ResponseMessage;
use Illuminate\Support\Facades\Validator;

class ArticalController extends Controller
{
    use ResponseMessage;
    public function create(Request $request){
        $validatedata = Validator::make($request->all(), [
            'artical_name'                    => 'required|string|max:30',
        ]);
    
        if($validatedata->fails()){
            return $this->ErrorResponse($validatedata);  
        }
        else{
            $artical = Artical::create($request->only('artical_name'));
            return $this->success('artical created successfully',$artical);
        }
    }

    public function list(){
        $artical = Artical::all();

        if(is_null($artical)){
            return $this->DataNotFound();
        }
        return $this->success('articals list',$artical);
    }

    public function update(Request $request,Artical $id){
        $validatedata = Validator::make($request->all(), [
            'artical_name'                    => 'string|max:30',
            'tag_id'                          => 'exists:tags,id'
        ]);
    
        if($validatedata->fails()){
            return $this->ErrorResponse($validatedata);  
        }
        else{
            $tagids = $request->tag_id;
            $id->update($request->only('artical_name'));
            $id->tags()->attach($tagids);
            
            return $this->success('artical comment add and update successfully',$id);
        }
    }


    public function get($id){
        $artical = Artical::with('tags')->find($id);
        
        if(is_null($artical)){
            return $this->DataNotFound();
        }
        return $this->success('artical with tags data',$artical);
    }

    public function destory($id){
        $artical = Artical::find($id);

        if(is_null($artical)){
            return $this->DataNotFound();
        }
        else{
            $artical -> delete();
            $artical->tags()->detach();
            return $this->success('artical with tags deleted successfully');
        }
    }
}