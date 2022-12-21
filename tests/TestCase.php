<?php

namespace Tests;

use App\Http\Middleware\PrivateAuthorisationMiddleware;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithFaker;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, WithFaker;

    protected function mockPrivateAuthToken(string $token): void
    {
        $authMiddleware = $this->getMockBuilder(PrivateAuthorisationMiddleware::class)
            ->onlyMethods(['token'])
            ->getMock();

        $authMiddleware->method('token')->willReturn($token);

        $this->app->instance(PrivateAuthorisationMiddleware::class, $authMiddleware);
    }

    protected function getAuthHeader(string $token): array
    {
        return [
            'Authorization' => sprintf('Bearer %s', $token)
        ];
    }
}
