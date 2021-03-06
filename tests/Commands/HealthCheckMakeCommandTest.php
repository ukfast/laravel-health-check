<?php

namespace Tests\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Facades\File;
use UKFast\HealthCheck\HealthCheckServiceProvider;
use Tests\TestCase;

class HealthCheckMakeCommandTest extends TestCase
{
    public function getPackageProviders($app)
    {
        return [HealthCheckServiceProvider::class];
    }

    /**
     * @test
     */
    public function creates_a_new_check()
    {   
        $checkName = "TestCheck";
        $checkClassFile = $this->app->basePath("app/Checks/{$checkName}.php");

        $this->assertFalse(File::exists($checkClassFile));

        $this->artisan("make:check", ["name" => $checkName]);

        $this->assertTrue(File::exists($checkClassFile));

        // Checking right file is created.
        $this->assertTrue(is_int(strpos(File::get($checkClassFile), "class {$checkName}")));

        // Cleaning the file.
        unlink($checkClassFile);
    }

    /**
     * @test
     */
    public function php_reserved_name_check_does_not_get_created()
    {
        if (!property_exists(GeneratorCommand::class, 'reservedNames')) {
            $this->markTestSkipped('GeneratorCommand does not support reservedNames.');
        }

        $checkName = "array";
        $checkClassFile = $this->app->basePath("app/Checks/{$checkName}.php");
        $this->assertFalse(File::exists($checkClassFile));

        $this->artisan("make:check", ["name" => $checkName]);

        $this->assertFalse(File::exists($checkClassFile));
    }
}
