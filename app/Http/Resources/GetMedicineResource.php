<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GetMedicineResource extends JsonResource
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
            'Classification_id'=>$this->Classification_id,
            'Scientific_name'=>$this->Scientific_name,
            'Commercial_name'=>$this->Commercial_name,
            'Manufacturer'=>$this->Manufacturer,
            'Available_Quantity'=>$this->Available_Quantity,
            'Expiry_date'=>$this->Expiry_date,
            'Price'=>$this->Price,


        ];    }
}
