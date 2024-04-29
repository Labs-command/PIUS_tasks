<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ReportedTaskResourceCollection extends ResourceCollection
{
    protected $offset;
    protected $limit;

    public function __construct($resource, $options = [])
    {
        // Сохраняем дополнительные параметры
        $this->offset = $options['offset'];
        $this->limit = $options['limit'];

        parent::__construct($resource);
    }

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "data" => $this->collection,
            "meta" => [
                'offset' => $this->offset,
                'limit' => $this->limit
            ]
        ];
    }
}
