<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    final public function callNonPublicMethod(mixed $object, string $method, array $args = []): mixed
    {
        return (fn (array $args) => $this->$method(...$args))->call($object, $args);
    }
}
