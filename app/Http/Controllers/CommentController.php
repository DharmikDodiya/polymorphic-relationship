<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Photo;
use App\Models\Post;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Traits\ResponseMessage; 

class CommentController extends Controller
{
    use ResponseMessage;
    public function create(Request $request){
        $validatedata = Validator::make($request->all(), [
            'body'              => 'required|string|max:100',
            'photo_id'                => 'exists:photos,id|numeric',
            'video_id'                => 'exists:videos,id|numeric'      
        ]);
       
        if($validatedata->fails()){
            return $this->ErrorResponse($validatedata);  
        }
        else{
            if($photo = Photo::where('id',$request->photo_id)->first()){
            $comment = new Comment();
            $comment->body = $request->body;
            $photo->comments()->save($comment);
            return $this->success('comment created successfully',$comment);
            }
            if($video = Video::where('id',$request->video_id)->first()){
            $comment = new Comment();
            $comment->body = $request->body;
            $video->comments()->save($comment);
            return $this->success('comment Add SuccessFully',$comment);
            }
            return $this->DataNotFound();
        }
      
    }

    public function list(){
        $comment = Comment::all();

        if(is_null($comment)){
            return $this->DataNotFound();
        }
        return $this->success('comment list',$comment);
    }

    public function update(Request $request,Comment $id){
        $validatedata = Validator::make($request->all(), [
            'body'              => 'required|string|max:100',   
        ]);
    
        if($validatedata->fails()){
            return $this->ErrorResponse($validatedata);  
        }
        else{
            $id->update($request->only('body'));
            
            return $this->success('comment updated successfully',$id);
        }
    }


    public function get($id){
        $comment = Comment::with('commentable')->find($id);
        
        if(is_null($comment)){
            return $this->DataNotFound();
        }
        $comment->comments;
        return $this->success('comment Details',$comment);
    }

    public function destory($id){
        $comment = Comment::find($id);

        if(is_null($comment)){
            return $this->DataNotFound();
        }
        else{
            $comment-> delete();
            return $this->success('comment deleted successfully');
        }
    }


}
