<?php

namespace Tests;

use App\Exceptions\TestException;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Artisan;

trait CreatesApplication
{
    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        app()->loadEnvironmentFrom('.env.testing');
        $app->make(Kernel::class)->bootstrap();

        $this->clearConfigCache();

        $connection = config('database.default');

        if($connection !== 'mysql_test') {
            throw new TestException('Wrong database connection. Prevent database from truncating. Run tests again');
        }

        return $app;
    }

    /**
     * Prevents deleting of real database if config loads from cache
     * 
     * @return void
     */
    private function clearConfigCache(): void
    {
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        Artisan::call('route:clear');
    }
}
