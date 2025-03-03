<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StoreResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        //return parent::toArray($request);
        return [
            "id" => $this->id,
            "title" => $this->title,
            "description" => $this->displayDescription($this->description, $request->isSingular),
            "views" => $this->views,
            "reviews" => [
                "positive" => $this->positive_reviews,
                "negative" => $this->negative_reviews
            ],
            "created_at" => $this->created_at->format('Y-m-d H:i:s')
        ];
    }

    private function displayDescription($description, $isSingular) {
        if($isSingular) return $description;
        if (strlen($description) > 100) return trim(substr($description, 0, 100)) . "...";
        return $description;
    }
}
