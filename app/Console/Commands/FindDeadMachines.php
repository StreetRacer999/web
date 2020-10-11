<?php

namespace App\Console\Commands;

use App\Models\Machine;
use Illuminate\Console\Command;

class FindDeadMachines extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dead-machines:find';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Finds dead machines';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Machine::where('updated_at', '<', now()->subMinutes(30))->chunk(10, function ($machines) {
            foreach ($machines as $machine) {
                $machine->servers->update(['status' => 'unknown']);
                $machine->update(['status' => 'unknown']);
            }
        });

        Machine::where('updated_at', '<', now()->subMinutes(30));
    }
}
