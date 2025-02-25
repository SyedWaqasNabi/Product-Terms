<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class DownloadFile
 * @package App\Http\Resources
 */
class DownloadFile extends JsonResource
{
    const STATUS_CODE_OK = 200;
    const STATUS_CODE_NOT_FOUND = 404;

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'links' => [
                'related' => [
                    'href' => $this->link,
                    'meta' => [
                        'expiration_time' => $this->expirationTime
                    ]
                ]
            ]
        ];
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Http\JsonResponse $response
     */
    public function withResponse($request, $response)
    {
        $response->setStatusCode($this->link ? self::STATUS_CODE_OK : self::STATUS_CODE_NOT_FOUND);
    }
}
