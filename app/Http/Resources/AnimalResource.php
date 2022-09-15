<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AnimalResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'type_id' => $this->type_id,
            // 使用animal物件的type方法，取得分類資料以後，再到分類的名稱，並設定type_name欄位存放內容。
            'type_name' => $this->type->name,
            'name' => $this->name,
            'birthday' => $this->birthday,
            // age預計顯示一個"X歲X月"的格式，但我們的資料表裡面沒有這個欄位，我們將後續介紹一個Laravel Model的功能。
            'age' => $this->age,
            'area' => $this->area,
            'fix' => $this->fix,
            'description' => $this->description,
            'personality' => $this->personality,
            // 在變數前面使用(string)可以將變數強制轉型為文字型態，如以下將日期型態轉換為字串型態，可以持尚將其中一個強制轉換(string)字樣拿掉，觀察與原始的差別。
            'created_at' => (string)$this->created_at,
            'updated_at' => (string)$this->updated_at,
            'user_id' => $this->user_id,
        ];
    }
}
