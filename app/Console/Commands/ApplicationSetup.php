<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;

class ApplicationSetup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:app';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup your application with one simple command';

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
     * @return mixed
     */
    public function handle()
    {
        $this->warn("·· Setting up your application");

        exec('composer install');

        $this->callSilent('config:cache');
        if (! App::environment('production')) {
            // 00.Turn off foreign key check
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            // 01. Migrate fresh version from the database
            $this->call('migrate:fresh');
            $this->info("🌱 Database Migration is Done");
            // 02.Turn foreign key check back on
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            // 03.Create dummy data to fill the database columns
            $this->warn("·· Dummy Data is being generated");
            $this->call('db:seed');
        } else {
            $this->call('migrate');
        }

        if (! App::environment('production')) {
            $this->call('test');
        }

        $this->comment("🎉 All Done 🎉");
    }
}
