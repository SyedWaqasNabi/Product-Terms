<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class QueueJob extends JsonResource
{
    const  STATUS_CODE_ACCEPTED = 202;

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'type'       => $this->queue,
            'id'         => $this->id,
            'attributes' => [
                'status'     => "Pending Request",
            ]
        ];
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Http\JsonResponse $response
     */
    public function withResponse($request, $response)
    {
        $response->setStatusCode(self::STATUS_CODE_ACCEPTED);
    }
}
