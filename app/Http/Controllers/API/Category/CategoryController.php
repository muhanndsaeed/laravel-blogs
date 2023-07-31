<?php

namespace App\Http\Controllers\API\Category;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CategoryRequest;
use App\Http\Controllers\API\BaseController;

class CategoryController extends BaseController
{
    //
    public function store(CategoryRequest $request){

        try {
            //code...
        
            Category::create([
                'user_id'=> Auth::user()->id,
                'title'=>$request->title,
                'description'=>$request->description,
            ]);
            $success['title'] = $request->title;
            $success['description'] = $request->description;
            return $this->handleSuccess($success,'Create category successfully');
        } catch (\Throwable $th) {
            //throw $th;
            return $this->handleError($th,500);
        }
        

    }
    
}
