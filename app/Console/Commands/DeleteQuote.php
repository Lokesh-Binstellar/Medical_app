<?php

namespace App\Console\Commands;

use App\Events\RoleMessageEvent;
use Illuminate\Console\Command;
use App\Models\RequestQuote;
use App\Events\SendMessageEvent;
use Illuminate\Support\Facades\Log;

class DeleteQuote extends Command
{
    protected $signature = 'delete:quote';
    protected $description = 'Delete RequestQuotes older than 25 minutes and broadcast via Pusher';

    public function handle()
    {
        Log::info('-- Delete Quote CRON started --');

        // Delete and get the count
        $deletedCount = RequestQuote::where('created_at', '<', now()->subMinutes(1))->delete();

        Log::info("Quotes deleted: {$deletedCount}");

        if ($deletedCount > 0) {
            // Fire Pusher event with message
            event(new RoleMessageEvent('Enable request quote'));
            Log::info('-- Pusher event fired after quote deletion --');
        }

        Log::info('-- Delete Quote CRON completed --');
    }
}
