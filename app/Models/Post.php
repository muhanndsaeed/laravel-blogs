<?php

namespace App\Models;


use App\Models\File;
use App\Models\User;
use App\Models\Comment;
use App\Models\Category;
use App\Models\Favorite;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{   
    public $table = 'posts';
    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'description',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }
    public function comment(){
        return $this->hasMany(Comment::class);
    }

    public function file(){
        return $this->hasMany(File::class);
    }
    public function favorite(){
        return $this->hasMany(Favorite::class);
    }
      
}
