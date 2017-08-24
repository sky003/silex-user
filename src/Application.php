<?php

declare( strict_types = 1);

namespace Auth;

use Auth\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Silex\Application as SilexApplication;
use Silex\Provider\ServiceControllerServiceProvider;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * The service application class.
 *
 * @package Auth
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
    public static function getName()
    {
        return 'Auth';
    }

    /**
     * Returns the service version.
     *
     * @return string
     */
    public static function getVersion()
    {
        return '0.0.1';
    }
}
