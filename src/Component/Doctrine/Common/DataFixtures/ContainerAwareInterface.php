<?php

declare(strict_types = 1);

namespace User\Component\Doctrine\Common\DataFixtures;

use Psr\Container\ContainerInterface;

/**
 * Interface should be implemented by fixtures that depends on a Container.
 *
 * @package User\Component\Doctrine\Common\DataFixtures
 * @author  Anton Pelykh <anton.pelykh.dev@gmail.com>
 */
interface ContainerAwareInterface
{
    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container);
}
