<?php

namespace App\Http\Controllers\API\Files;

use Exception;
use App\Models\Files;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\FileRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\BaseController;
use Illuminate\Support\Facades\File;


class FileController extends BaseController
{
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(FileRequest $request)
    {   
       
        $post= Post::where('user_id',auth()->user()->id)->find($request->post_id);
        if($post){
            $checkFile = $post->file()->where('post_id',$request->post_id)->get();
            if(!count($checkFile)) {
                $name = $request->file_name->getClientOriginalName();
                $path = $request->file_name->store('uploads', 'public');
                $save['user_id'] = auth()->user()->id;
                $save['file_name'] = $name;
                $save['file_path'] = $path;

                $file = $post->file()->create($save);

            return $this->handleSuccessWithResult($file, 'Added file successfully');
            }else {
                return $this->handleError('Post has file already',401);
}
        }else {
            return $this->handleError('Post not found',404);
        }
 
    }

    
    public function DownloadFile(String $id ,Files $file){
        $uploadfile = $file->where('id',$id)->first();
        if($uploadfile){
            $path = $uploadfile->file_path;
            $storedFile = Storage::disk('public')->get($path);
      
            return  response($storedFile, 200)->header('Content-Type', Storage::mimeType($path));
        }else {
            return $this->handleError("File Not Found",404);
        }
        
    }

     /**
     * As a user I can update file
     */
    public function update(Request $request,string $id){
        try{
            $file = Files::find($id);
            
            if(auth()->id() != $file->user_id){
                return $this->handleError("anauthorised",404);
            }else{
                if($file){
                   File::delete(storage_path().'/app/'.$file->file_path);
                   $t= $file->update([
                        'file_name'=>$request->file_name->getClientOriginalName(),
                        'file_path'=>$request->file_name->store('uploads','public'),
               ]);
                    return $this->handleSuccessWithResult($t,'Update file successfully');
                } else{
                    return $this->handleError("file not found",404);
                }
            }}
            catch (Exception $error) {
                return $this->handleError($error,500);
            }  
    }


    /**
     * As a user I can delete file
     */
    public function destroy(Files $file)
    {
        try{
            if(auth()->id() != $file->user_id){
                return $this->handleError("unauthorised",401);
            }else{
                if($file->delete()){
                   $deleted = Storage::delete($file->file_path);
                   if($deleted){
                    return $this->handleSuccessWithResult($file ,'deleted successfully');
                   }
                }else{
                    return $this->handleError("the file not found",401);
                    }
            }
            }catch (Exception $error) {
                return $this->handleError($error,500);
            }    
        
    }
}
