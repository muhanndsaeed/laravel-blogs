<?php

namespace App\Http\Controllers\API\Files;

use Exception;
use App\Models\File;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\FileRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\BaseController;

class FileController extends BaseController
{
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(FileRequest $request)
    {   
       
        $post= Post::where('user_id',auth()->user()->id)->find($request->post_id);
        if($post){
            $name = $request->file_name->getClientOriginalName();
            $path = $request->file_name->store('public/posts');
            $save['user_id'] = auth()->user()->id;
            $save['file_name'] = $name;
            $save['file_path'] = $path;
                        
           $file = $post->file()->create($save);

            return $this->handleSuccessWithResult($file,'Added file successfully');
        }else {
            return $this->handleError('Post not found',404);
        }
    
    }

    
    public function DownloadFile(String $id ,File $file){
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
            $file = File::find($id);
            if(auth()->id() != $file->user_id){
                return $this->handleError("anauthorised",404);
            }else{
                if($file){
                   $t= $file->update([
                        'file_name'=>$request->file_name->getClientOriginalName(),
                        'file_path'=>$request->file_name->store('public/posts'),
               ]);
                    return $this->handleSuccessWithResult($t,'Update file successfully');
                } else{
                    return $this->handleError("file not found",404);
                }
            }  
            }catch (Exception $error) {
                return $this->handleError($error,500);
            }  
    }


    /**
     * As a user I can delete file
     */
    public function destroy(File $file)
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
