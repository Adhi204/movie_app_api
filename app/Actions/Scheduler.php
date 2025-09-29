<?php

namespace App\Actions;

use App\Jobs\PrunePasswordResetTokens;
use App\Jobs\PruneUserAccessTokens;
use App\Jobs\PruneUserVerificationTokens;
use App\Library\Classes\Action;
use Illuminate\Console\Scheduling\Schedule;

class Scheduler extends Action
{
    // Execute the action
    public function __invoke(Schedule $schedule)
    {
        $schedule->job(new PruneUserAccessTokens)->hourly();
        // $schedule->job(new PruneUserVerificationTokens)->hourly();
        // $schedule->job(new PrunePasswordResetTokens)->hourly();
    }
}
