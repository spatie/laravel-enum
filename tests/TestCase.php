<?php

namespace Spatie\Enum\Laravel\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Spatie\Enum\Laravel\Tests\Extra\Post;

abstract class TestCase extends OrchestraTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase();
    }

    protected function setUpDatabase()
    {
        Post::migrate();
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'sqlite');

        config()->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }
}
