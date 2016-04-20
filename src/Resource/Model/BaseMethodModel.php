<?php

namespace Reliv\RcmApiLib\Resource\Model;

/**
 * class BaseMethodModel
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
class BaseMethodModel implements MethodModel
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $httpVerb;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var PreServiceModel
     */
    protected $preService;

    /**
     * BaseMethodModel constructor.
     *
     * @param string          $name
     * @param string          $description
     * @param string          $httpVerb
     * @param string          $path
     * @param PreServiceModel $preService
     */
    public function __construct(
        $name,
        $description,
        $httpVerb,
        $path,
        PreServiceModel $preService
    ) {
        $this->name = $name;
        $this->description = $description;
        $this->httpVerb = $httpVerb;
        $this->path = $path;
        $this->preService = $preService;
    }

    /**
     * getName
     *
     * @return string
     */
    public function getName()
    {
        return (string)$this->name;
    }

    /**
     * getDescription
     *
     * @return string
     */
    public function getDescription()
    {
        return (string)$this->description;
    }

    /**
     * getHttpVerb
     *
     * @return string
     */
    public function getHttpVerb()
    {
        return strtoupper((string)$this->httpVerb);
    }

    /**
     * getPath
     *
     * @return string
     */
    public function getPath()
    {
        return (string)$this->path;
    }

    /**
     * getPreService
     *
     * @return PreServiceModel
     */
    public function getPreService()
    {
        return $this->preService;
    }
}
