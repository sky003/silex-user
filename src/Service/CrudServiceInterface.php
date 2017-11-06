<?php

declare(strict_types = 1);

namespace User\Service;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;

/**
 * Interface for CRUD service to use with CRUD controller.
 *
 * @package User\Service
 * @author  Anton Pelykh <anton.pelykh.dev@gmail.com>
 */
interface CrudServiceInterface
{
    public function create(object $object): object;
    public function get(int $id): object;
    public function getList(Criteria $criteria): Collection;
    public function update(object $object): object;
    public function delete(object $object);
}