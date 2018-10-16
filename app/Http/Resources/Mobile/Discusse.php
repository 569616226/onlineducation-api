<?php

namespace App\Http\Resources\Mobile;


use Illuminate\Http\Resources\Json\Resource;

class Discusse extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'content'    => $this->content,
            'is_better'  => $this->is_better,
            'guest'      => $this->guest->nickname,
            'avatar'     => $this->guest->picture,
            'is_vip'     => $this->guest->vip_id ? true : false,
            'lesson'     => $this->lesson->name,
            $this->mergeWhen($this->pid == 0, [
                'teacher_msg' => $this->teacher_msg,
            ]),
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
