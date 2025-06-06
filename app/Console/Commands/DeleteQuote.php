<?php

namespace App\Console\Commands;

use App\Events\SendMessageEvent;
use Illuminate\Console\Command;
use App\Models\RequestQuote;
use Symfony\Component\Mailer\Messenger\SendEmailMessage;

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

               event(new SendMessageEvent('hgvh',null));

		    \Log::info('-- Delete Quote CRON completed --');
        
        
    }
}
