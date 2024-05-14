<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\PostResource;


class EmployerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'role'=>$this->user->role,
            'company_name'=>$this->company_name,
            'company_logo'=>$this->logo,
            'user_id'=>$this->user->id,
            'name'=>$this->user->name,
            'email'=>$this->user->email,
            'username'=>$this->user->username,
            'image'=>$this->user->image,
            // 'posts'=>PostResource::collection($this->posts)
        ];
    }
}
