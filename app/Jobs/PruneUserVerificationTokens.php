<?php

namespace App\Jobs;

// use App\Models\UserVerification;
// use Illuminate\Contracts\Queue\ShouldQueue;
// use Illuminate\Foundation\Queue\Queueable;
// use Illuminate\Support\Facades\Log;

// class PruneUserVerificationTokens implements ShouldQueue
// {
//     use Queueable;

//     /**
//      * The number of times the job may be attempted.
//      *
//      * @var int
//      */
//     public $tries = 3;

//     /**
//      * The number of seconds the job can run before timing out.
//      *
//      * @var int
//      */
//     public $timeout = 120;

//     /**
//      * Create a new job instance.
//      */
//     public function __construct()
//     {
//         //
//     }

//     /**
//      * Execute the job.
//      */
//     public function handle(): void
//     {
//         Log::channel('schedule')->info("Pruning expired user verification tokens...");

//         // Grace period to avoid accidental deletion for tokens in queue
//         $gracePeriodMinutes = 5;

//         $deletedCount = UserVerification::where('expires_at', '<', now()->addMinutes($gracePeriodMinutes))->delete();

//         Log::channel('schedule')->info("{$deletedCount} expired user verification tokens deleted.");
//     }
// }
