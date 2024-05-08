<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\SkillResource;


class PostResource extends JsonResource
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
            'id'=> $this->id,
            'company_logo'=> $this->employer->logo,
            'company_name'=> $this->employer->company_name,
            'job_title'=>$this->job_title,
            'description'=>$this->description,
            'responsibilities'=>$this->responsibilities,
            'qualifications'=>$this->qualifications,
            'start_salary'=>$this->start_salary,
            'end_salary'=>$this->end_salary,
            'location'=>$this->location,
            'work_type'=>$this->work_type,
            'application_deadline'=>$this->application_deadline,
            'status'=>$this->status,
            // 'skills'=>$this->skills
            // 'skills'=>SkillResource::collection($this->skills)
            'skills'=>$this->skills->pluck('skill')->toArray() 
        ];
    }
}
