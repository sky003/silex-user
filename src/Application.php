<?php

declare(strict_types = 1);

namespace User;

use Symfony\Component\HttpFoundation\Request;
use User\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Silex\Application as SilexApplication;
use Silex\Provider\ServiceControllerServiceProvider;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * The service application class.
 *
 * @package User
 * @author  Anton Pelykh <anton.pelykh.dev@gmail.com>
 */
class Application extends SilexApplication
{
    const ENV_DEV = 'dev';
    const ENV_TEST = 'test';
    const ENV_STAGE = 'stage';
    const ENV_PROD = 'prod';

    /**
     * Application constructor.
     *
     * @param array $values
     */
    public function __construct(array $values = [])
    {
        parent::__construct($values);

        $this->register(new ServiceControllerServiceProvider());

        // Prepare the request attributes you need.
        $this->before(function (Request $request) {
            if ($request->isMethod('GET')) {
                $request->attributes->set(
                    '_limit',
                    (int) $request->query->get('limit', 50)
                );
                $request->attributes->set(
                    '_offset',
                    (int) $request->query->get('offset', 0)
                );
            }
        });

        // Handle the validation errors.
        $this->error(function (UnprocessableEntityHttpException $e) {
            $validationErrors = [];
            /**
             * @var \Symfony\Component\Validator\ConstraintViolationInterface $error
             */
            foreach ($e->getConstraintViolationList() as $error) {
                $validationErrors[] = [
                    'property' => trim($error->getPropertyPath(), '[]'),
                    'message' => $error->getMessage(),
                ];
            }

            $error = [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
                'validation_errors' => $validationErrors,
            ];

            return new JsonResponse($error, $e->getStatusCode());
        });

        $this->error(function (HttpException $e) {
            $error = [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ];

            return new JsonResponse($error, $e->getStatusCode());
        });
    }

    /**
     * Returns the service name.
     *
     * @return string
     */
    public static function getName(): string
    {
        return 'User';
    }

    /**
     * Returns the service version.
     *
     * @return string
     */
    public static function getVersion(): string
    {
        return '0.0.1';
    }
}
