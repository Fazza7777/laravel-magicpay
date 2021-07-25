<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $title = '';
        $source = $this->source ? $this->source->name : '-';
        if($this->type == 1){
            $sign = '+';
           $title = 'From '.$source;
        }else if($this->type==2){
            $sign = '-';
            $title = 'To '.$source;
        }
        return [
            'trx_id' => $this->trx_id,
            'amount' => $sign.number_format($this->amount,2).' MMK' ,
            'type' => $this->type ,  // 1 => income , 2 => expense
            'title'=>$title,
            'date_time'=> Carbon::parse($this->created_at)->format("Y-m-d H:i:s")
        ];
    }
}
