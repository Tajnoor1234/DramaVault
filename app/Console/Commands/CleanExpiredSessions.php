<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanExpiredSessions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sessions:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean expired sessions from the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $lifetime = config('session.lifetime') * 60; // Convert to seconds
        $expiredTime = time() - $lifetime;

        $count = DB::table(config('session.table', 'sessions'))
            ->where('last_activity', '<', $expiredTime)
            ->delete();

        $this->info("Cleaned {$count} expired sessions.");

        return 0;
    }
}
