<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public static $wrap = "application";
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'student' => [
                'name' => $this->student->user->name,
                'faculty' => $this->student->faculty,
                'study_program' => $this->student->study_program,
            ],
            'opening' => [
                'title' => $this->opening->title,
                'company' => $this->opening->company->user->name,
            ],
        ];
    }
}
