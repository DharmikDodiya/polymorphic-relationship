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
            $comment = new Comment;
            foreach($comments as $cm){
               $photo->comments()->create([
                'body' =>    $cm
            ]);
            } 
            return $this->success('photo created successfully',$photo);
        }
    }

    public function list(){
        $photo = Photo::all();
        return $this->success('user data list',$photo);
    }

    public function update(Request $request,Photo $id){
        $validatedata = Validator::make($request->all(), [
            'photo_name'                    => 'string|max:30',
            'body'                          => 'max:50',
            'comment_id'                    => 'numeric'
        ]);
    
        if($validatedata->fails()){
            return $this->ErrorResponse($validatedata);  
        }
        else{
            $id->update($request->only('photo_name'));
                $id->comments()->updateOrCreate(
        ['id' => $request->comment_id],[
                           'body'   => $request->body
                        ]
                    );
            return $this->success('Updated photo Data successfully',$id);
        }
    }


    public function get($id){
        $photo = Photo::with('comments')->findOrFail($id);
    
        $photo->image;
        return $this->success('photo Details',$photo);
    }

    public function destory($id){
        $photo = Photo::findOrFail($id);
        $photo->comments()->detach();
        $photo -> delete();
        return $this->success('photo and comment deleted successfully');
    }

}
