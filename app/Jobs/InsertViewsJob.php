<?php

namespace App\Jobs;

use App\Models\Store;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class InsertViewsJob implements ShouldQueue
{
    use Queueable;

    protected $storeIds;
    protected $userId;

    /**
     * Create a new job instance.
     */
    public function __construct($userId, $storeIds)
    {
        $this->userId = $userId;
        $this->storeIds = $storeIds;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $user = User::where("id", $this->userId)->first();
            if (!$user) {
                Log::error('InsertViewsJob failed: ' . "User not found");
                return;
            }

            $stores = Store::whereIn("id", $this->storeIds)->get();
            if (!$stores->count()) {
                Log::error('InsertViewsJob failed: ' . "Not stores to match views with");
                return;
            }

            $existingStoresInDb = $stores->map(fn($el) => $el->id)->toArray();

            DB::transaction(function () use ($user, $existingStoresInDb) {
                $user->views()->attach($existingStoresInDb);
            });
        } catch (\Exception $e) {
            Log::error('InsertViewsJob failed: ' . $e->getMessage());
            throw $e; // Let Laravel handle retries
        }
    }
}
