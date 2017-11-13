<?php

declare(strict_types = 1);

namespace User\Dto\Request;

use JMS\Serializer\Annotation as Serializer;

/**
 * Account request DTO.
 *
 * @package User\Dto\Request
 * @author  Anton Pelykh <anton.pelykh.dev@gmail.com>
 *
 * @Serializer\ExclusionPolicy("all")
 */
class Account
{
    /**
     * @var int
     *
     * @Serializer\Expose()
     * @Serializer\ReadOnly()
     * @Serializer\Type("integer")
     */
    private $id;
    /**
     * @var string
     *
     * @Serializer\Expose()
     * @Serializer\Type("string")
     */
    private $email;
    /**
     * @var string
     *
     * @Serializer\Expose()
     * @Serializer\Type("string")
     */
    private $password;
    /**
     * @var string
     *
     * @Serializer\Expose()
     * @Serializer\Type("string")
     */
    private $status;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return self
     */
    public function setId(int $id): Account
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return self
     */
    public function setEmail(string $email): Account
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     *
     * @return self
     */
    public function setPassword(string $password): Account
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     *
     * @return self
     */
    public function setStatus(string $status): Account
    {
        $this->status = $status;

        return $this;
    }
}
