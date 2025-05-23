<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan;

class CreatePassportPersonalClient extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Run the passport:client command after migrations
        Artisan::call('passport:client', [
            '--personal' => true,
            '--name' => 'WebSec Personal Access Client',
            '--no-interaction' => true
        ]);

        $this->command->info('Personal access client created successfully.');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Nothing to reverse
    }
}
