<?php
// @codingStandardsIgnoreFile

namespace User\Tests\Unit\Service;

use Codeception\Test\Unit;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use User\Dto\Request as RequestDto;
use User\Dto\Response as ResponseDto;
use User\Entity\ORM\Account;
use User\Service\AccountCrudService;

class AccountCrudServiceTest extends Unit
{
    public function testUnsupportedDto()
    {
        $encoderFactory = $this->getMockBuilder(EncoderFactoryInterface::class)
            ->getMock();
        $entityManager = $this->getMockBuilder(EntityManagerInterface::class)
            ->getMock();
        $logger = $this->getMockBuilder(LoggerInterface::class)
            ->getMock();

        $service = new AccountCrudService(
            $encoderFactory,
            $entityManager,
            $logger
        );

        $this->expectException(\InvalidArgumentException::class);
        $service->create(new class implements RequestDto\DtoInterface {});
    }

    public function testCreate()
    {
        $encoderFactory = $this->getMockBuilder(EncoderFactoryInterface::class)
            ->getMock();
        $encoderFactory
            ->expects($this->once())
            ->method('getEncoder')
            ->will($this->returnCallback(function () {
                $encoder = $this->getMockBuilder(PasswordEncoderInterface::class)
                    ->getMock();
                $encoder
                    ->expects($this->atMost(1))
                    ->method('encodePassword')
                    ->will($this->returnValue('encoded-password'));

                return $encoder;
            }));
        $entityManager = $this->getMockBuilder(EntityManagerInterface::class)
            ->getMock();
        $entityManager
            ->expects($this->once())
            ->method('persist')
            ->will($this->returnCallback(function (Account $account) {
                $account->setId(1);
                $account->setCreatedAt(new \DateTime('now'));

                return $account;
            }));
        $entityManager
            ->expects($this->once())
            ->method('flush');
        $logger = $this->getMockBuilder(LoggerInterface::class)
            ->getMock();

        $service = new AccountCrudService(
            $encoderFactory,
            $entityManager,
            $logger
        );

        $accountRequestDto = (new RequestDto\Account())
            ->setEmail('john.doe@example.com')
            ->setPassword('password')
            ->setStatus(Account::STATUS_ENABLED);
        $accountResponseDto = $service->create($accountRequestDto);

        $this->assertNotNull($accountResponseDto);
        $this->assertInstanceOf(ResponseDto\Account::class, $accountResponseDto);
    }

    public function testGet()
    {
        $encoderFactory = $this->getMockBuilder(EncoderFactoryInterface::class)
            ->getMock();
        $entityManager = $this->getMockBuilder(EntityManagerInterface::class)
            ->getMock();
        $entityManager
            ->expects($this->once())
            ->method('find')
            ->will($this->returnCallback(function () {
                $account = new Account();
                $account
                    ->setId(1)
                    ->setEmail('john.doe@example.com')
                    ->setPassword('encoded-password')
                    ->setStatus(Account::STATUS_ENABLED)
                    ->setCreatedAt(new \DateTime('now'));

                return $account;
            }));
        $logger = $this->getMockBuilder(LoggerInterface::class)
            ->getMock();

        $service = new AccountCrudService(
            $encoderFactory,
            $entityManager,
            $logger
        );

        $accountResponseDto = $service->get(1);

        $this->assertNotNull($accountResponseDto);
        $this->assertInstanceOf(ResponseDto\Account::class, $accountResponseDto);
    }

    public function testGetList()
    {
        $encoderFactory = $this->getMockBuilder(EncoderFactoryInterface::class)
            ->getMock();
        $entityManager = $this->getMockBuilder(EntityManagerInterface::class)
            ->getMock();
        $entityManager
            ->expects($this->once())
            ->method('getRepository')
            ->will($this->returnCallback(function () {
                $repository = $this->getMockBuilder(EntityRepository::class)
                    ->disableOriginalConstructor()
                    ->getMock();
                $repository
                    ->expects($this->once())
                    ->method('matching')
                    ->will($this->returnCallback(function () {
                        $collection = $this->getMockBuilder(Collection::class)
                            ->getMock();

                        return $collection;
                    }));

                return $repository;
            }));
        $logger = $this->getMockBuilder(LoggerInterface::class)
            ->getMock();

        $service = new AccountCrudService(
            $encoderFactory,
            $entityManager,
            $logger
        );

        $criteria = Criteria::create();
        $accountCollection = $service->getList($criteria);

        $this->assertNotNull($accountCollection);
        $this->assertInstanceOf(Collection::class, $accountCollection);
    }

    public function testUpdate()
    {
        $encoderFactory = $this->getMockBuilder(EncoderFactoryInterface::class)
            ->getMock();
        $encoderFactory
            ->expects($this->once())
            ->method('getEncoder')
            ->will($this->returnCallback(function () {
                $encoder = $this->getMockBuilder(PasswordEncoderInterface::class)
                    ->getMock();
                $encoder
                    ->expects($this->atMost(1))
                    ->method('encodePassword')
                    ->will($this->returnValue('new-encoded-password'));

                return $encoder;
            }));
        $entityManager = $this->getMockBuilder(EntityManagerInterface::class)
            ->getMock();
        $entityManager
            ->expects($this->once())
            ->method('find')
            ->will($this->returnCallback(function () {
                $account = new Account();
                $account
                    ->setId(1)
                    ->setEmail('john.doe@example.com')
                    ->setPassword('encoded-password')
                    ->setStatus(Account::STATUS_ENABLED)
                    ->setCreatedAt(new \DateTime('now'));

                return $account;
            }));
        $entityManager
            ->expects($this->once())
            ->method('persist');
        $entityManager
            ->expects($this->once())
            ->method('flush');
        $logger = $this->getMockBuilder(LoggerInterface::class)
            ->getMock();

        $service = new AccountCrudService(
            $encoderFactory,
            $entityManager,
            $logger
        );

        $accountRequestDto = (new RequestDto\Account())
            ->setId(1)
            ->setPassword('password')
            ->setStatus(Account::STATUS_LOCKED);
        $accountResponseDto = $service->update($accountRequestDto);

        $this->assertNotNull($accountResponseDto);
        $this->assertInstanceOf(ResponseDto\Account::class, $accountResponseDto);
        $this->assertEquals($accountRequestDto->getStatus(), $accountResponseDto->getStatus());
    }

    public function testDelete()
    {
        $encoderFactory = $this->getMockBuilder(EncoderFactoryInterface::class)
            ->getMock();
        $entityManager = $this->getMockBuilder(EntityManagerInterface::class)
            ->getMock();
        $entityManager
            ->expects($this->once())
            ->method('find')
            ->will($this->returnCallback(function () {
                return new Account();
            }));
        $entityManager
            ->expects($this->once())
            ->method('remove');
        $entityManager
            ->expects($this->once())
            ->method('flush');
        $logger = $this->getMockBuilder(LoggerInterface::class)
            ->getMock();

        $service = new AccountCrudService(
            $encoderFactory,
            $entityManager,
            $logger
        );

        $accountRequestDto = (new RequestDto\Account())
            ->setId(1);
        $service->delete($accountRequestDto);
    }
}
