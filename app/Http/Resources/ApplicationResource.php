<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class ApplicationResource extends JsonResource
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
            'candidate_id'=>$this->candidate_id,
            'post'=>$this->post,
            'post_id'=>$this->post->id,
            'resume'=>$this->resume ?? null,
            'email'=>$this->email ?? null,
            'phone'=>$this->phone ?? null,
            'status'=>$this->status,
            'application_date'=>Carbon::parse($this->created_at)->toDateString(),
        ];
    }
}
