<?php

namespace Reliv\RcmApiLib\Api\ApiResponse;

use Reliv\RcmApiLib\Http\ApiResponseInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface WithApiMessages
{
    /**
     * @param ApiResponseInterface $apiResponse
     * @param array                $apiMessagesData
     *
     * @return ApiResponseInterface
     */
    public function __invoke(
        ApiResponseInterface $apiResponse,
        array $apiMessagesData
    ): ApiResponseInterface;
}
