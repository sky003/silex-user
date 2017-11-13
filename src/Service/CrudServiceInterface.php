<?php

declare(strict_types = 1);

namespace User\Service;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use User\Dto\Request as Request;
use User\Dto\Response as Response;

/**
 * Interface for CRUD service to use with CRUD controller.
 *
 * @package User\Service
 * @author  Anton Pelykh <anton.pelykh.dev@gmail.com>
 */
interface CrudServiceInterface
{
    /**
     * @param Request\DtoInterface $requestDto
     *
     * @return Response\DtoInterface
     */
    public function create(Request\DtoInterface $requestDto): Response\DtoInterface;

    /**
     * @param int $id
     *
     * @return Response\DtoInterface
     */
    public function get(int $id): ?Response\DtoInterface;

    /**
     * @param Criteria $criteria
     *
     * @return Collection
     */
    public function getList(Criteria $criteria): Collection;

    /**
     * @param Request\DtoInterface $requestDto
     *
     * @return Response\DtoInterface
     */
    public function update(Request\DtoInterface $requestDto): ?Response\DtoInterface;

    /**
     * @param Request\DtoInterface $requestDto
     */
    public function delete(Request\DtoInterface $requestDto): void;
}
