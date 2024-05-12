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
            'post_id'=>$this->post_id,
            'resume'=>$this->resume,
            'email'=>$this->email,
            'phone'=>$this->phone,
            'status'=>$this->status,
            'application_date'=>Carbon::parse($this->created_at)->toDateString(),
        ];
    }
}
