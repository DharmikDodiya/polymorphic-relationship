<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;



class PhotoController extends Controller
{
    
    public function create(Request $request){
        $request->validate( [
            'photo_name'                    => 'required|string|max:30',
            'body'                          => 'max:50'
        ]);
            $photo = Photo::create($request->only('photo_name'));
            $comments = $request->body;
            
            foreach($comments as $cm){
               $photo->comments()->create([
                'body' =>    $cm
            ]);
            }
        
            return success('photo created successfully',$photo);
        
    }

    public function list(){
        $photo = Photo::all();
        return success('user data list',$photo);
    }

    public function update(Request $request,Photo $id){
        $request->validate([
            'photo_name'                    => 'string|max:30',
            'body'                          => 'max:50',
            'comment_id'                    => 'numeric'
        ]);
            $id->update($request->only('photo_name'));
            $id->comments()->updateOrCreate(
        ['id' => $request->comment_id],[
                           'body'   => $request->body
                        ]
                    );
            return success('Updated photo Data successfully',$id);
       
    }


    public function get($id){
        $photo = Photo::with('comments')->findOrFail($id);
    
        $photo->image;
        return success('photo Details',$photo);
    }

    public function destory($id){
        $photo = Photo::findOrFail($id);
        $photo->comments()->delete();
        $photo -> delete();
        return success('photo and comment deleted successfully');
    }

}
