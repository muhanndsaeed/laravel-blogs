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
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FileRequest $request)
    {
        $post= Post::where('user_id',auth()->user()->id)->find($request->post_id);
        if($post){
            $name = $request->file_name->getClientOriginalName();
            $path = $request->file_name->store('uploads','public');;
            $save['user_id'] = auth()->user()->id;
            $save['file_name'] = $name;
            $save['file_path'] = $path;
                        
           $file = $post->file()->create($save);

            return $this->handleSuccessWithResult($file,'Added file successfully');
        }else {
            return $this->handleError('Post not found',404);
        }
    
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
    }

    public function DownloadFile(String $id ,String $post_id ,File $file){
        $uploadfile = $file->where('id',$id)->where('user_id',auth()->user()->id)->where('post_id',$post_id)->first();
        if($uploadfile){
            $path = $uploadfile->file_path;
            $file = Storage::disk('public')->get($path);
      
            return  response($file, 200)->header('Content-Type', Storage::mimeType($path));
        }else {
            return $this->handleError("File Not Found",404);
        }
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
