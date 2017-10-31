<?php

declare(strict_types = 1);

namespace User\Entity\ORM;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class to represent the token entity.
 *
 * @package User\Entity\ORM
 * @author Anton Pelykh <anton.pelykh.dev@gmail.com>
 *
 * @ORM\Entity()
 * @ORM\Table(name="token", schema="public")
 */
class Token
{
    const STATUS_ENCODING = 1;
    const STATUS_ENABLED = 2;
    const STATUS_BLACKLISTED = 4;

    /**
     * @var integer
     *
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @var integer
     *
     * @ORM\Column(type="integer", name="user_id")
     */
    private $userId;
    /**
     * @var string
     *
     * @ORM\Column(type="string", name="access_token", nullable=true)
     */
    private $accessToken;
    /**
     * @var string
     *
     * @ORM\Column(type="string", name="refresh_token", nullable=true)
     */
    private $refreshToken;
    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", name="access_token_expires_in", nullable=true)
     */
    private $accessTokenExpiresIn;
    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", name="refresh_token_expires_in", nullable=true)
     */
    private $refreshTokenExpiresIn;
    /**
     * @var integer
     *
     * @ORM\Column(type="smallint")
     */
    private $status;
    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", name="issued_at")
     */
    private $issuedAt;
    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", name="created_at")
     */
    private $createdAt;
    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", name="updated_at", nullable=true)
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
    public function setId(int $id): Token
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     *
     * @return self
     */
    public function setUserId(int $userId): Token
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * @return string
     */
    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    /**
     * @param string $accessToken
     *
     * @return self
     */
    public function setAccessToken(string $accessToken): Token
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    /**
     * @return string
     */
    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

    /**
     * @param string $refreshToken
     *
     * @return self
     */
    public function setRefreshToken(string $refreshToken): Token
    {
        $this->refreshToken = $refreshToken;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getAccessTokenExpiresIn(): \DateTime
    {
        return $this->accessTokenExpiresIn;
    }

    /**
     * @param \DateTime $accessTokenExpiresIn
     *
     * @return self
     */
    public function setAccessTokenExpiresIn(\DateTime $accessTokenExpiresIn): Token
    {
        $this->accessTokenExpiresIn = $accessTokenExpiresIn;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getRefreshTokenExpiresIn(): \DateTime
    {
        return $this->refreshTokenExpiresIn;
    }

    /**
     * @param \DateTime $refreshTokenExpiresIn
     *
     * @return self
     */
    public function setRefreshTokenExpiresIn(\DateTime $refreshTokenExpiresIn): Token
    {
        $this->refreshTokenExpiresIn = $refreshTokenExpiresIn;

        return $this;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     *
     * @return self
     */
    public function setStatus(int $status): Token
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getIssuedAt(): \DateTime
    {
        return $this->issuedAt;
    }

    /**
     * @param \DateTime $issuedAt
     *
     * @return self
     */
    public function setIssuedAt(\DateTime $issuedAt): Token
    {
        $this->issuedAt = $issuedAt;

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
    public function setCreatedAt(\DateTime $createdAt): Token
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
    public function setUpdatedAt(\DateTime $updatedAt): Token
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
