<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Traits\ResponseMessage;
use Illuminate\Support\Facades\Validator;


class VideoController extends Controller
{
    use ResponseMessage;
    public function create(Request $request){
        $validatedata = Validator::make($request->all(), [
            'video_name'                    => 'required|string|max:30',
            'body'                          => 'array|max:50'
        ]);
    
        if($validatedata->fails()){
            return $this->ErrorResponse($validatedata);  
        }
        else{
            $video = Video::create($request->only('video_name'));
            $comments = $request->body;
            foreach($comments as $comment){
                Comment::create([
                    'commentable_id'    => $video->id,
                    'commentable_type'  =>'App\models\Video',
                    'body'              => $comment
                ]);
            }
            return $this->success('video created successfully',$video);
        }
    }

    public function list(){
        $video = Video::all();

        return $this->success('video data list',$video);
    }

    public function update(Request $request,Video $id){
        $validatedata = Validator::make($request->all(), [
            'video_name'                    => 'string|max:30',
            'body'                          => 'array|max:50'
        ]);
    
        if($validatedata->fails()){
            return $this->ErrorResponse($validatedata);  
        }
        else{
            $id->update($request->only('video_name'));
            $comments = $request->body;
            foreach($comments as $comment){
                Comment::updateOrCreate([
                    [
                    'commentable_id'    => $id->id,
                    'commentable_type'  => 'App\Models\Video',
                    ],
                    'commentable_id'    => $id->id,
                    'commentable_type'  => 'App\Models\Video',
                    'body'              => $comment
                ]);
            }
            return $this->success('video  Data successfully',$id);
        }
    }


    public function get($id){
        $video = Video::with('comments')->findOrFail($id);
        $video->comments;
        return $this->success('video Details',$video);
    }

    public function destory($id){
        $video = Video::findOrFail($id);
            $video->comments()->delete();
            $video -> delete();
            return $this->success('video deleted successfully');
        
    }
}
