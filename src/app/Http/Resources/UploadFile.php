<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UploadFile extends JsonResource
{
    const  STATUS_CODE_CREATED = 201;

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'type'       => "product_terms_upload",
            'id'         => $this->id,
            'attributes' => [
                'name' => $this->name
            ]
        ];
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Http\JsonResponse $response
     */
    public function withResponse($request, $response)
    {
        $response->setStatusCode(self::STATUS_CODE_CREATED);
    }
}
