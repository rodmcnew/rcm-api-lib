<?php

namespace Reliv\RcmApiLib\Resource\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Reliv\RcmApiLib\Model\ApiSerializableInterface;
use Reliv\RcmApiLib\Resource\Http\BasicDataResponse;
use Reliv\RcmApiLib\Resource\Http\DataResponse;
use Reliv\RcmApiLib\Resource\Options\GenericOptions;
use Reliv\RcmApiLib\Resource\Options\Options;
use Zend\Stdlib\JsonSerializable;

/**
 * Class AbstractMiddleware
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   MiddlewareInterface
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
abstract class AbstractMiddleware
{
    /**
     * getResourceKey
     *
     * @param Request $request
     * @param null    $default
     *
     * @return mixed
     */
    protected function getResourceKey(Request $request, $default = null)
    {
        return $request->getAttribute(Middleware::REQUEST_ATTRIBUTE_RESOURCE_KEY, $default);
    }

    /**
     * getMethodKey
     *
     * @param Request $request
     * @param null    $default
     *
     * @return mixed
     */
    protected function getMethodKey(Request $request, $default = null)
    {
        return $request->getAttribute(Middleware::REQUEST_ATTRIBUTE_RESOURCE_METHOD_KEY, $default);
    }

    /**
     * getOptions
     *
     * @param Request $request
     *
     * @return Options
     */
    protected function getOptions(Request $request)
    {
        /** @var Options $options */
        $options = $request->getAttribute(
            OptionsMiddleware::REQUEST_ATTRIBUTE_OPTIONS,
            new GenericOptions()
        );

        return $options;
    }

    /**
     * getControllerOption
     *
     * @param Request $request
     * @param string  $key
     * @param null    $default
     *
     * @return Options
     */
    protected function getOption(Request $request, $key, $default = null)
    {
        /** @var Options $options */
        $options = $this->getOptions($request);

        return $options->get($key, $default);
    }

    /**
     * getRequestData
     *
     * @param Request    $request
     * @param mixed|null $default
     *
     * @return mixed
     */
    protected function getRequestData(Request $request, $default = null)
    {
        return $request->getAttribute('dataBody', $default);
    }

    /**
     * getDataModel
     *
     * @param Response   $response
     * @param mixed|null $default
     *
     * @return mixed|null
     */
    public function getDataModel(Response $response, $default = null)
    {
        if ($response instanceof DataResponse) {
            return $response->getDataBody();
        }

        return $default;
    }

    /**
     * getDataModelArray
     *
     * @param Response $response
     * @param array    $ignore
     * @param array    $default
     *
     * @return array|mixed|null
     */
    public function getDataModelArray(Response $response, $ignore = [], $default = [])
    {
        $dataModel = $this->getDataModel($response, []);

        if (is_array($dataModel)) {
            return $dataModel;
        }

        if ($dataModel instanceof ApiSerializableInterface) {
            return $dataModel->toArray($ignore);
        }
        
        $toArrayMethod = null;

        if (method_exists($dataModel, '__toArray')) {
            $toArrayMethod = '__toArray';
        }

        if (method_exists($dataModel, 'toArray')) {
            $toArrayMethod = 'toArray';
        }

        if ($toArrayMethod !== null) {
            $array = $dataModel->toArray();
            $return = [];
            foreach ($array as $key => $value) {
                if (!in_array($key, $ignore)) {
                    $return[$key] = $value;
                }
            }

            return $return;
        }

        return $default;
    }

    /**
     * withDataResponse
     *
     * @param Response $response
     * @param mixed    $dataModel
     *
     * @return DataResponse
     */
    protected function withDataResponse(Response $response, $dataModel)
    {
        if (!$response instanceof DataResponse) {
            $response = new BasicDataResponse($response);
        }

        return $response->withDataBody($dataModel);
    }
}