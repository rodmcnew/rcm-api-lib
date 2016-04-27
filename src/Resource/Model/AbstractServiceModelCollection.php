<?php

namespace Reliv\RcmApiLib\Resource\Model;

use Reliv\RcmApiLib\Resource\Exception\ServiceMissingException;
use Reliv\RcmApiLib\Resource\Middleware\Middleware;
use Reliv\RcmApiLib\Resource\Options\GenericOptions;
use Reliv\RcmApiLib\Resource\Options\Options;

/**
 * Class AbstractServiceModelCollection
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   Reliv\RcmApiLib\Resource\Model
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
abstract class AbstractServiceModelCollection
{
    /**
     * @var \SplPriorityQueue
     */
    protected $servicesIndex;
    
    /**
     * @var array
     */
    protected $services = [];

    /**
     * @var array
     */
    protected $serviceOptions = [];

    /**
     * @var array
     */
    protected $servicePriorities = [];

    /**
     * AbstractServiceModelCollection constructor.
     *
     * @param array $services          ['{serviceAlias}' => {callable}]
     * @param array $serviceOptions    ['{serviceAlias}' => {Options}]
     * @param array $servicePriorities ['{serviceAlias}' => {int}]
     */
    public function __construct(
        array $services,
        array $serviceOptions,
        array $servicePriorities
    ) {
        $this->servicesIndex = new \SplPriorityQueue();

        $cnt = count($services);
        foreach ($services as $serviceAlias => $service) {
            $servicePriority = $cnt;
            if (array_key_exists($serviceAlias, $servicePriorities)) {
                $servicePriority = $servicePriorities[$serviceAlias];
            }
            $this->addService($serviceAlias, $service, $servicePriority);
            $cnt--;
        }

        foreach ($serviceOptions as $serviceAlias => $serviceOption) {
            $this->addOption($serviceAlias, $serviceOption);
        }
    }

    /**
     * addService
     *
     * @param string   $serviceAlias
     * @param callable $service
     * @param int      $priority
     *
     * @return void
     */
    protected function addService($serviceAlias, callable $service, $priority = 0)
    {
        $this->servicesIndex->insert($serviceAlias, $priority);
        $this->services[$serviceAlias] = $service;
    }

    /**
     * addOption
     *
     * @param string  $serviceAlias
     * @param Options $options
     *
     * @return void
     */
    protected function addOption($serviceAlias, Options $options)
    {
        $this->serviceOptions[$serviceAlias] = $options;
    }

    /**
     * getServices
     *
     * @return array ['{serviceAlias}' => {callable}]
     */
    public function getServices()
    {
        $services = [];
        foreach ($this->servicesIndex as $serviceAlias) {
            $services[$serviceAlias] = $this->services[$serviceAlias];
        }

        return $services;
    }

    /**
     * getService
     *
     * @param string $serviceAlias
     *
     * @return Middleware
     * @throws ServiceMissingException
     */
    public function getService($serviceAlias)
    {
        if ($this->hasService($serviceAlias)) {
            return $this->services[$serviceAlias];
        }

        throw new ServiceMissingException('Service not set');
    }

    /**
     * hasPreService
     *
     * @param string $serviceAlias
     *
     * @return bool
     */
    public function hasService($serviceAlias)
    {
        return array_key_exists($serviceAlias, $this->services);
    }

    /**
     * getPreOptions
     *
     * @param string $serviceAlias
     *
     * @return Options
     */
    public function getOptions($serviceAlias)
    {
        if (array_key_exists($serviceAlias, $this->serviceOptions)) {
            return $this->serviceOptions[$serviceAlias];
        }

        return new GenericOptions();
    }

    /**
     * getPriority
     *
     * @param string $serviceAlias
     *
     * @return int
     */
    public function getPriority($serviceAlias)
    {
        if (array_key_exists($serviceAlias, $this->servicePriorities)) {
            return $this->servicePriorities[$serviceAlias];
        }

        return 0;
    }
}
