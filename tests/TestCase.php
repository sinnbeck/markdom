<?php
namespace Tests;

use Sinnbeck\Markdom\MarkdomServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [MarkdomServiceProvider::class];
    }
}