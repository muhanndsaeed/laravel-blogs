<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'user_id' =>$this->user->id,
            'post_id' =>$this->post->id,
            'user_name' => $this->user->user_name,
            'name' => $this->user->name,
            'email'=> $this->user->email,
            'content'=>$this->content,
            'created_at' => $this -> created_at,
        ];
    }
}
