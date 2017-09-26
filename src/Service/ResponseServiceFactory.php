<?php

namespace Reliv\RcmApiLib\Service;

use Interop\Container\ContainerInterface;
use Reliv\RcmApiLib\Api\Translate\Translate;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @deprecated
 * @author James Jervis - https://github.com/jerv13
 */
class ResponseServiceFactory
{
    /**
     * __invoke
     *
     * @param $container ContainerInterface|ServiceLocatorInterface
     *
     * @return ResponseService
     */
    public function __invoke($container)
    {
        return new ResponseService(
            $container,
            $container->get(Translate::class)
        );
    }
}
