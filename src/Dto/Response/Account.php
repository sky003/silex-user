<?php

declare(strict_types = 1);

namespace User\Dto\Response;

use JMS\Serializer\Annotation as Serializer;

/**
 * Account response DTO.
 *
 * @package User\Dto\Response
 * @author  Anton Pelykh <anton.pelykh.dev@gmail.com>
 *
 * @Serializer\ExclusionPolicy("all")
 */
class Account implements DtoInterface
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
    private $status;
    /**
     * @var \DateTime
     *
     * @Serializer\Expose()
     * @Serializer\Type("DateTime")
     */
    private $createdAt;
    /**
     * @var \DateTime
     *
     * @Serializer\Expose()
     * @Serializer\Type("DateTime")
     */
    private $updatedAt;

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

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     *
     * @return self
     */
    public function setCreatedAt(\DateTime $createdAt): Account
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     *
     * @return self
     */
    public function setUpdatedAt(\DateTime $updatedAt): Account
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
