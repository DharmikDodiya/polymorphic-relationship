<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use App\Traits\ResponseMessage;
use Illuminate\Support\Facades\Validator;


class VideoController extends Controller
{
    use ResponseMessage;
    public function create(Request $request){
        $validatedata = Validator::make($request->all(), [
            'video_name'                    => 'required|string|max:30',
        ]);
    
        if($validatedata->fails()){
            return $this->ErrorResponse($validatedata);  
        }
        else{
            $video = Video::create($request->only('video_name'));
            return $this->success('video created successfully',$video);
        }
    }

    public function list(){
        $video = Video::all();

        if(is_null($video)){
            return $this->DataNotFound();
        }
        return $this->success('video data list',$video);
    }

    public function update(Request $request,Video $id){
        $validatedata = Validator::make($request->all(), [
            'video_name'                    => 'required|string|max:30',
        ]);
    
        if($validatedata->fails()){
            return $this->ErrorResponse($validatedata);  
        }
        else{
            $id->update($request->only('video_name'));
            
            return $this->success('video  Data successfully',$id);
        }
    }


    public function get($id){
        $video = Video::with('comments')->find($id);
        
        if(is_null($video)){
            return $this->DataNotFound();
        }
        $video->comments;
        return $this->success('video Details',$video);
    }

    public function destory($id){
        $video = Video::find($id);

        if(is_null($video)){
            return $this->DataNotFound();
        }
        else{
            $video->comments()->delete();
            $video -> delete();
            return $this->success('video deleted successfully');
        }
    }
}
