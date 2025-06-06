<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\RequestQuote;

class DeleteQuote extends Command
{
    /**
     * The name and signature of the console command.
     *
     * You can run it with: php artisan your:command
     */
    protected $signature = 'delete:quote';

    /**
     * The console command description.
     */
    protected $description = 'Delete Quote CRON';

    /**
     * Execute the console command.
     */	
    public function handle()
    {
            \Log::info('-- Delete Quote CRON started --');

		    // Delete all quotes older than 25 minutes
		    RequestQuote::where('created_at', '<', now()->subMinutes(25))->delete();

		    \Log::info('-- Delete Quote CRON completed --');
        
        
    }
}
