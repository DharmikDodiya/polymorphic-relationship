<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Traits\ResponseMessage;
use Illuminate\Support\Facades\Validator;

class PhotoController extends Controller
{
    use ResponseMessage;
    public function create(Request $request){
        $validatedata = Validator::make($request->all(), [
            'photo_name'                    => 'required|string|max:30',
            'body'                          => 'array|max:50'
        ]);
    
        if($validatedata->fails()){
            return $this->ErrorResponse($validatedata);  
        }
        else{
            $photo = Photo::create($request->only('photo_name'));
            $comments = $request->body;
            foreach($comments as $comment){
                Comment::create([
                    'commentable_id'    => $photo->id,
                    'commentable_type'  => 'App\Models\Photo',
                    'body'              => $comment
                ]);
            }
            return $this->success('photo created successfully',$photo);
        }
    }

    public function list(){
        $photo = Photo::all();

        if(is_null($photo)){
            return $this->DataNotFound();
        }
        return $this->success('user data list',$photo);
    }

    public function update(Request $request,Photo $id){
        $validatedata = Validator::make($request->all(), [
            'photo_name'                    => 'required|string|max:30',
            'body'                          => 'array|max:50'
        ]);
    
        if($validatedata->fails()){
            return $this->ErrorResponse($validatedata);  
        }
        else{
            $id->update($request->only('photo_name'));
            $comments = $request->body;
            foreach($comments as $comment){
                Comment::updateOrCreate([
                    'commentable_id'    => $id->id,
                    'commentable_type'  => 'App\Models\Photo'
                ],[
                    'commentable_id'    => $id->id,
                    'commentable_type'  => 'App\Models\Photo',
                    'body'              => $comment
                ]);
            }
            return $this->success('Updated photo Data successfully',$id);
        }
    }


    public function get($id){
        $photo = Photo::with('comments')->find($id);
        
        if(is_null($photo)){
            return $this->DataNotFound();
        }
        $photo->image;
        return $this->success('photo Details',$photo);
    }

    public function destory($id){
        $photo = Photo::find($id);

        if(is_null($photo)){
            return $this->DataNotFound();
        }
        else{
            $photo->comments()->delete();
            $photo -> delete();
            return $this->success('photo and comment deleted successfully');
        }
    }

}
