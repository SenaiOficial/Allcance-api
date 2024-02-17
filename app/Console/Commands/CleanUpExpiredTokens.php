<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ResetPassword;
use Carbon\Carbon;

class CleanUpExpiredTokens extends Command
{
    protected $signature = 'tokens:cleanup';
    protected $description = 'Clean up expired reset password tokens';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        ResetPassword::where('created_at', '<=',  Carbon::now()->subHour())->delete();

        $this->info('Reset password tokens older than one minute have been cleaned up.');
    }
}
