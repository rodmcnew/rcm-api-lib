<?php

namespace Reliv\RcmApiLib\Http;

use Reliv\RcmApiLib\Model\ApiMessages;
use Zend\Http\Headers;
use Zend\Http\Response as HttpResponse;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class ApiResponse extends HttpResponse implements ApiResponseInterface
{
    use BasicApiResponseTrait;

    /**
     * @var mixed
     */
    protected $data = null;

    /**
     * @var ApiMessages
     */
    protected $messages;

    /**
     * ApiResponse constructor.
     *
     * @param null $apiMessages
     *
     * @throws \Exception
     */
    public function __construct($apiMessages = null)
    {
        /** @var Headers $headers */
        $headers = $this->getHeaders();
        $headers->addHeaderLine('Content-Type', 'application/json');

        $this->messages = $apiMessages;

        if ($apiMessages instanceof ApiMessages) {
            $this->setApiMessages($apiMessages);
        } else {
            $this->setApiMessages(new ApiMessages());
        }
    }

    /**
     * @param ApiMessages $apiMessages
     *
     * @return ApiResponse|ApiResponseInterface
     */
    public function withApiMessages(ApiMessages $apiMessages)
    {
        $new = clone $this;
        $new->messages = $apiMessages;

        return $new;
    }

    /**
     * setDataOject
     *
     * @param \JsonSerializable $data
     *
     * @return void
     */
    public function setDataOject(\JsonSerializable $data)
    {
        $this->data = $data;
    }

    /**
     * setContent
     *
     * @param mixed $content
     *
     * @return void
     * @throws \Exception
     */
    public function setContent($content)
    {
        throw new \Exception(
            'Content cannot be set directly, use setData, or addMssages'
        );
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getContent()
    {
        $content = [
            'data' => $this->getData(),
            'messages' => $this->getApiMessages(),
        ];

        $json = json_encode($content);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception(json_last_error_msg());
        }

        return $json;
    }

    /**
     * withStatus
     *
     * @param int    $statusCode
     * @param string $reasonPhrase
     *
     * @return $this
     */
    public function withStatus($statusCode, $reasonPhrase = '')
    {
        parent::setStatusCode($statusCode);
        parent::setReasonPhrase($reasonPhrase);

        return $this;
    }
}
