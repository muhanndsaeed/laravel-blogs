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

    public function index(){

        $categories = Category::all();
        if (!empty($categories)){
            return $this->handleSuccess($categories);
        }else{
            return $this->handleError('No Records Found',200); 
        }
    }



    public function store(CategoryRequest $request){

        try {
        
            Category::create([
                'user_id'=> Auth::user()->id,
                'title'=>$request->title,
                'description'=>$request->description,
            ]);
            $success = $request->all();
            
            return $this->handleSuccess($success,'Create category successfully');
        } catch (\Throwable $th) {
            //throw $th;
            return $this->handleError($th,500);
        }
        

    }



    public function update(CategoryRequest $request,$id){

       $category = Category::find($id);
        if($category){
            $category->update([
                'user_id'=> Auth::user()->id,
                'title'=>$request->title,
                'description'=>$request->description,
       ]);

       return $this->handleSuccess($category,'Update category successfully');

        } else{
            return $this->handleError("Category not found",404);
        }
    
    }


    public function destroy(string $id)
    {   
        $categories = Category::find($id);
        
        if($categories){
            $categories -> delete();
            return $this->handleSuccessWithResult($categories ,'deleted successfully');
        }else{
            return $this->handleError("the category not found",401);
            }
    }
    
    
}
