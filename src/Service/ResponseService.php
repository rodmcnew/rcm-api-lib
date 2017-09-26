<?php

namespace Reliv\RcmApiLib\Service;

use Interop\Container\ContainerInterface;
use Reliv\RcmApiLib\Api\Hydrator\HydrateApiMessages;
use Reliv\RcmApiLib\Api\Translate\OptionsTranslate;
use Reliv\RcmApiLib\Api\Translate\Translate;
use Reliv\RcmApiLib\Http\ApiResponseInterface;
use Reliv\RcmApiLib\Model\ApiMessage;
use Reliv\RcmApiLib\Model\ApiMessages;

/**
 * @deprecated Use \Reliv\RcmApiLib\Api\ApiResponse
 * @author James Jervis - https://github.com/jerv13
 */
class ResponseService
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var Translate
     */
    protected $translate;

    /**
     * ResponseService constructor.
     *
     * @param ContainerInterface $container
     * @param Translate          $translate
     */
    public function __construct(
        $container,
        Translate $translate
    ) {
        $this->container = $container;
        $this->translate = $translate;
    }

    /**
     * getHydrator
     *
     * @return \Reliv\RcmApiLib\Api\Hydrator\HydrateApiMessages
     */
    protected function getHydrator()
    {
        return $this->container->get(HydrateApiMessages::class);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    protected function buildStringParams(array $params = [])
    {
        $stringParams = [];

        foreach ($params as $key => $value) {
            if (is_string($value) || is_numeric($value)) {
                $stringParams[$key] = $value;
                continue;
            }
            $stringParams[$key] = json_encode($value);
        }

        return $stringParams;
    }

    /**
     * @deprecated Use Reliv\RcmApiLib\Api\Translate
     * translateMessage
     *
     * @param        $message
     * @param array  $params
     * @param string $textDomain
     * @param null   $locale
     *
     * @return mixed
     */
    public function translateMessage(
        $message,
        $params = [],
        $textDomain = 'default',
        $locale = null
    ) {
        $stringParams = $this->buildStringParams($params);

        return $this->translate->__invoke(
            $message,
            [
                OptionsTranslate::OPTIONS_PARAMS => $stringParams,
                OptionsTranslate::OPTIONS_TEXT_DOMAIN => $textDomain,
                OptionsTranslate::OPTIONS_LOCALE => $locale,
            ]
        );
    }

    /**
     * @deprecated Use
     * @param ApiResponseInterface $response
     *
     * @return ApiResponseInterface
     */
    public function translateApiResponseMessages(
        ApiResponseInterface $response
    ) {
        $apiMessages = $response->getApiMessages();

        /** @var ApiMessage $apiMessage */
        foreach ($apiMessages as $apiMessage) {
            $apiMessage->setValue(
                $this->translateMessage(
                    $apiMessage->getValue(),
                    $apiMessage->getParams()
                )
            );
        }

        return $response;
    }

    /**
     * @deprecated
     * getMethodNotAllowed
     *
     * @param ApiResponseInterface $response
     *
     * @return ApiResponseInterface
     */
    public function getMethodNotAllowed(
        ApiResponseInterface $response
    ) {
        $apiMessage = new ApiMessage(
            'Http',
            'Method Not Allowed',
            'Method_Not_Allowed',
            '405',
            true
        );

        return $this->getApiResponse(
            $response,
            null,
            405,
            $apiMessage
        );
    }

    /**
     * @deprecated Use NewZfResponseWithTranslatedMessages
     * getApiResponse
     *
     * @param ApiResponseInterface $response
     * @param                      $data
     * @param int                  $statusCode
     * @param null                 $apiMessagesData
     *
     * @return ApiResponseInterface
     */
    public function getApiResponse(
        ApiResponseInterface $response,
        $data,
        $statusCode = 200,
        $apiMessagesData = null
    ) {
        $response->setData($data);

        if (!empty($apiMessagesData)) {
            $this->addApiMessage(
                $response,
                $apiMessagesData
            );
        }

        $this->translateApiResponseMessages(
            $response
        );

        return $response->withStatus($statusCode);
    }

    /**
     * @deprecated
     * setApiMessage
     *
     * @param ApiResponseInterface $response
     * @param ApiMessage           $apiMessage
     *
     * @return void
     */
    public function setApiMessage(
        ApiResponseInterface $response,
        ApiMessage $apiMessage
    ) {
        $response->addApiMessage($apiMessage);
    }

    /**
     * @deprecated Use Reliv\RcmApiLib\Api\ApiResponse\WithApiMessage
     * addApiMessage
     *
     * @param ApiResponseInterface $response
     * @param mixed                $apiMessagesData
     *
     * @return void
     */
    public function addApiMessage(
        ApiResponseInterface $response,
        $apiMessagesData
    ) {
        $hydrator = $this->getHydrator();

        $apiMessages = $response->getApiMessages();

        $hydrator->__invoke($apiMessagesData, $apiMessages);
    }

    /**
     * @deprecated
     * setApiMessages
     *
     * @param ApiResponseInterface $response
     * @param ApiMessages          $apiMessages
     *
     * @return void
     */
    public function setApiMessages(
        ApiResponseInterface $response,
        ApiMessages $apiMessages
    ) {
        $response->setApiMessages($apiMessages);
    }

    /**
     * @deprecated Use Reliv\RcmApiLib\Api\ApiResponse\WithApiMessages
     * addApiMessages
     *
     * @param ApiResponseInterface $response
     * @param array|\ArrayIterator $apiMessagesData
     *
     * @return void
     */
    public function addApiMessages(
        ApiResponseInterface $response,
        $apiMessagesData
    ) {
        foreach ($apiMessagesData as $apiMessage) {
            $this->addApiMessage($response, $apiMessage);
        }
    }

    /**
     * @deprecated Use ApiResponseInterface->getApiMessages()
     * getApiMessages
     *
     * @param ApiResponseInterface $response
     *
     * @return ApiMessages
     */
    public function getApiMessages(
        ApiResponseInterface $response
    ) {
        return $response->getApiMessages();
    }

    /**
     * @deprecated Use ApiResponseInterface->getApiMessages()->has()
     *
     * hasApiMessages
     *
     * @param ApiResponseInterface $response
     *
     * @return bool
     */
    public function hasApiMessages(
        ApiResponseInterface $response
    ) {
        $apiMessages = $this->getApiMessages(
            $response
        );

        return $apiMessages->has();
    }
}
