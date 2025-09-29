<?php

namespace App\Jobs;

// use App\Models\PasswordResetToken;
// use Illuminate\Contracts\Queue\ShouldQueue;
// use Illuminate\Foundation\Queue\Queueable;
// use Illuminate\Support\Facades\Log;

// class PrunePasswordResetTokens implements ShouldQueue
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
//         Log::channel('schedule')->info("Pruning expired password reset tokens...");

//         // 5 minutes grace period to avoid accidental deletion for tokens in queue
//         $tokenExpiryMinutes = config('auth.password_reset_token_expiry') + 5;

//         $deletedCount = PasswordResetToken::where('created_at', '<', now()->subMinutes($tokenExpiryMinutes))->delete();

//         Log::channel('schedule')->info("{$deletedCount} expired password reset tokens deleted.");
//     }
// }
