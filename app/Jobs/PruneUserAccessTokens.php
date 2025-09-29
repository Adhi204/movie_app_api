<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\Sanctum;

class PruneUserAccessTokens implements ShouldQueue
{
    use Queueable;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 120;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::channel('schedule')->info("Pruning expired user access tokens...");

        $accessTokenModel = Sanctum::personalAccessTokenModel();

        $deletedCount = $accessTokenModel::where('expires_at', '<', now())->delete();

        Log::channel('schedule')->info("{$deletedCount} expired user access tokens deleted.");
    }
}
