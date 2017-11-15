<?php

declare(strict_types = 1);

namespace User\Service;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Selectable;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use User\Dto\Request as Request;
use User\Dto\Response as Response;
use User\Entity\ORM\Account;
use User\Entity\ORM\User;

/**
 * Class AccountCrudService.
 *
 * @package User\Service
 * @author  Anton Pelykh <anton.pelykh.dev@gmail.com>
 */
class AccountCrudService implements CrudServiceInterface
{
    /**
     * @var EncoderFactoryInterface
     */
    private $encoderFactory;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * AccountCrudService constructor.
     *
     * @param EncoderFactoryInterface $encoderFactory
     * @param EntityManagerInterface  $entityManager
     * @param LoggerInterface         $logger
     */
    public function __construct(
        EncoderFactoryInterface $encoderFactory,
        EntityManagerInterface $entityManager,
        LoggerInterface $logger
    ) {
        $this->encoderFactory = $encoderFactory;
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    /**
     * @param Request\DtoInterface $requestDto
     *
     * @return Response\DtoInterface
     */
    public function create(Request\DtoInterface $requestDto): Response\DtoInterface
    {
        /**
         * @var Request\Account $requestDto
         */
        $this->checkSupport($requestDto);

        $user = (new User())
            ->setStatus(User::STATUS_ENABLED);
        $account = (new Account())
            ->setUser($user)
            ->setEmail($requestDto->getEmail())
            ->setPassword(
                $this->encoderFactory
                    ->getEncoder(\Symfony\Component\Security\Core\User\User::class) // TODO: Use correct user implementation.
                    ->encodePassword($requestDto->getPassword(), null)
            )
            ->setStatus((int) $requestDto->getStatus());

        $this->entityManager->persist($account);
        $this->entityManager->flush();

        return $this->writeDto($account);
    }

    /**
     * @param int $id
     *
     * @return Response\DtoInterface
     */
    public function get(int $id): ?Response\DtoInterface
    {
        /**
         * @var Account $account
         */
        $account = $this->entityManager->find(Account::class, $id);

        if (null === $account) {
            return null;
        }

        return $this->writeDto($account);
    }

    /**
     * @param Criteria $criteria
     *
     * @return Collection
     */
    public function getList(Criteria $criteria): Collection
    {
        $repository = $this->entityManager->getRepository(Account::class);

        if (!$repository instanceof Selectable) {
            throw new \UnexpectedValueException(
                sprintf('Repository must implement "%s" interface.', Selectable::class)
            );
        }

        $accounts = $repository->matching($criteria);

        return $accounts;
    }

    /**
     * {@inheritdoc}
     */
    public function update(Request\DtoInterface $requestDto): ?Response\DtoInterface
    {
        /**
         * @var Request\Account $requestDto
         */
        $this->checkSupport($requestDto);

        /**
         * @var Account $account
         */
        $account = $this->entityManager->find(Account::class, $requestDto->getId());

        if (null === $account) {
            return null;
        }

        if (null !== $requestDto->getEmail()) {
            $account->setEmail($requestDto->getEmail());
        }
        if (null !== $requestDto->getPassword()) {
            $account->setPassword(
                $this->encoderFactory
                    ->getEncoder(\Symfony\Component\Security\Core\User\User::class) // TODO: Use correct user implementation.
                    ->encodePassword($requestDto->getPassword(), null)
            );
        }
        if (null !== $requestDto->getStatus()) {
            $account->setStatus((int) $requestDto->getStatus());
        }

        $this->entityManager->persist($account);
        $this->entityManager->flush();

        return $this->writeDto($account);
    }

    /**
     * @param Request\DtoInterface $requestDto
     */
    public function delete(Request\DtoInterface $requestDto): void
    {
        /**
         * @var Request\Account $requestDto
         */
        $this->checkSupport($requestDto);

        /**
         * @var Account $account
         */
        $account = $this->entityManager->find(Account::class, $requestDto->getId());

        if (null === $account) {
            throw new \UnexpectedValueException(
                sprintf('Account with id #%s could not be found.', $requestDto->getId())
            );
        }

        $this->entityManager->remove($account);
        $this->entityManager->flush();
    }

    /**
     * @param Account $account
     *
     * @return Response\Account
     */
    private function writeDto(Account $account): Response\Account
    {
        $responseDto = (new Response\Account())
            ->setId($account->getId())
            ->setEmail($account->getEmail())
            ->setStatus((string) $account->getStatus())
            ->setCreatedAt($account->getCreatedAt())
            ->setUpdatedAt($account->getUpdatedAt());

        return $responseDto;
    }

    /**
     * @param Request\DtoInterface $requestDto
     *
     * @throws \InvalidArgumentException If DTO object not supported.
     */
    private function checkSupport(Request\DtoInterface $requestDto): void
    {
        if (!$requestDto instanceof Request\Account) {
            throw new \InvalidArgumentException(
                sprintf(
                    '"$dto" must be an instance of %s (%s given).',
                    Request\Account::class,
                    get_class($requestDto)
                )
            );
        }
    }
}
