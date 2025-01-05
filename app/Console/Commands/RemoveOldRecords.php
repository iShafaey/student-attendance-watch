<?php

namespace App\Console\Commands;

use App\Models\StudentRecord;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class RemoveOldRecords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:remove-old-records';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'remove old students records';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        StudentRecord::whereNotNull('attendance_in_datetime')->orWhereNotNull('absence_datetime')->where('created_at', '<=', Carbon::now()->subMonths(1))->delete();
        Log::info("Student records have been removed");
    }
}
