<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class showOrdersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'User_id'=>$this->User_id,
            'Order_Status'=>$this->Order_Status,
            'Payment_Status'=>$this->Payment_Status,
        ];
    }
}
