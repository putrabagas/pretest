<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class APIResource extends JsonResource
{
    public $status;
    public $code;
    public $data;

    public function __construct($status, $code, $resource)
    {
        parent::__construct($resource);
        $this->status = $status;
        $this->code = $code;
    }
    
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if ($this->status) {
            return [
                'code' => $this->code,
                'status' => $this->status,
                'data' => $this->resource
            ];
        }
        return [
            'code' => $this->code,
            'status' => $this->status,
            'errors' => $this->resource
        ];
    }
}
