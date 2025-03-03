<?php 

namespace App\Services;

class OutputService {

    public function handlePaginatedOutput($outputEntries, $resourceOutput) {
        return [
            'data' => $resourceOutput::collection($outputEntries),
            'meta' => [
                'current_page' => $outputEntries->currentPage(),
                'last_page' => $outputEntries->lastPage(),
                'per_page' => $outputEntries->perPage(),
                'total' => $outputEntries->total(),
            ],
            'links' => [
                'first' => $outputEntries->url(1),
                'last' => $outputEntries->url($outputEntries->lastPage()),
                'prev' => $outputEntries->previousPageUrl(),
                'next' => $outputEntries->nextPageUrl(),
            ]
        ];
    }

}
