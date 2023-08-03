<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_name' => $this -> user -> user_name,
            'name' => $this -> user -> name,
            'email' => $this -> user -> email,
            'category' => $this -> category-> title,
            'title' => $this -> title,
            'description' => $this -> description,
            'created_at' => $this -> created_at,
            
        ];
    }
    
}
