<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Traits\ResponseMessage;

class PhotoController extends Controller
{
    use ResponseMessage;
    public function create(Request $request){
        $validatedata = Validator::make($request->all(), [
            'photo_name'                    => 'required|string|max:30',
        ]);
    
        if($validatedata->fails()){
            return $this->ErrorResponse($validatedata);  
        }
        else{
            $photo = Photo::create($request->only('photo_name'));
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
        ]);
    
        if($validatedata->fails()){
            return $this->ErrorResponse($validatedata);  
        }
        else{
            $id->update($request->only('photo_name'));
            
            return $this->success('Updated photo Data successfully',$id);
        }
    }


    public function get($id){
        $photo = Photo::find($id);
        
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
            return $this->success('photo deleted successfully');
        }
    }

}
