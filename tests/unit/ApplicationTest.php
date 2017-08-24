<?php
// @codingStandardsIgnoreFile

namespace Auth\Tests\Unit;

use Auth\Application;
use Auth\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Codeception\Test\Unit;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolationList;

class ApplicationTest extends Unit
{
    public function testRequestError()
    {
        $app = new Application();

        $response = $app->handle(Request::create('/'));

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals(
            '{"code":0,"message":"No route found for \u0022GET \/\u0022"}',
            $response->getContent()
        );
    }

    public function testRequestValidationError()
    {
        $app = new Application();
        $app->get('/', function() {
            throw new UnprocessableEntityHttpException(new ConstraintViolationList());
        });

        $response = $app->handle(Request::create('/'));

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertEquals(
            '{"code":0,"message":"","validation_errors":[]}',
            $response->getContent()
        );
    }
}
