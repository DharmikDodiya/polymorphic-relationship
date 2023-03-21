<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function create(Request $request){
        $request->validate([
            'video_name'                    => 'required|string|max:30',
            'body'                          => 'array|max:50'
        ]);
            
        $video = Video::create($request->only('video_name'));
            $comments = $request->body;
            if(isset($comments)){
                foreach($comments as $cm){
                    $video->comments()->create([
                    'body' =>    $cm
                ]);
                } 
            }
            return success('video created successfully',$video);
    }

    public function list(){
        $video = Video::all();
        return success('video data list',$video);
    }

    public function update(Request $request,Video $id){
        $request->validate([
            'video_name'                    => 'string|max:30',
            'body'                          => 'string|max:50',
            'comment_id'                    => 'numeric'
        ]);
            $id->update($request->only('video_name'));
            if(isset($request->body)){
                $id->comments()->updateOrCreate(
                ['id' => $request->comment_id],[
                                'body'   => $request->body
                            ]
                );
            }
            return success('video  Data successfully',$id);
    }
    
    public function get($id){
        $video = Video::with('comments')->findOrFail($id);
        $video->comments;
        return success('video Details',$video);
    }

    public function destory($id){
        $video = Video::findOrFail($id);
            $video->comments()->delete();
            $video -> delete();
            return success('video deleted successfully');
        
    }
}
