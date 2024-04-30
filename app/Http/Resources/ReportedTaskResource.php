<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportedTaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "task_id" => $this->task_id,
            "subject" => $this->subject,
            "text" => $this->text,
            "answer" => $this->answer,
            "reason_comment" => $this->reason_comment,
            "author_id" => $this->author_id,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
        ];
    }
}
