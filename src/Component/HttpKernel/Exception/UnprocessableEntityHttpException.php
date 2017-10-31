<?php

declare(strict_types = 1);

namespace User\Component\HttpKernel\Exception;

use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException as BaseUnprocessableEntityHttpException;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * UnprocessableEntityHttpException.
 *
 * @package User\Component\HttpKernel\Exception
 * @author  Anton Pelykh <anton.pelykh.dev@gmail.com>
 */
class UnprocessableEntityHttpException extends BaseUnprocessableEntityHttpException
{
    /**
     * @var ConstraintViolationListInterface
     */
    private $constraintViolationList;

    /**
     * UnprocessableEntityHttpException constructor.
     *
     * @param ConstraintViolationListInterface $constraintViolationList
     * @param string|null                      $message
     * @param \Exception|null                  $previous
     * @param int                              $code
     */
    public function __construct(
        ConstraintViolationListInterface $constraintViolationList,
        string $message = null,
        \Exception $previous = null,
        int $code = 0
    ) {
        parent::__construct($message, $previous, $code);

        $this->constraintViolationList = $constraintViolationList;
    }

    /**
     * @return ConstraintViolationListInterface
     */
    public function getConstraintViolationList(): ConstraintViolationListInterface
    {
        return $this->constraintViolationList;
    }
}
