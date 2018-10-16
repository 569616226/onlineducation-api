<?php

namespace App\Http\Resources;

use App\Models\Guest;
use App\Models\User;
use Illuminate\Http\Resources\Json\Resource;

class Message extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray( $request )
    {

        $type = '纯文本';
        if( $this->url ){
            $type = '图文';
        }

        $data = [
            'id'         => $this->id,
            'title'      => $this->title,
            'content'    => html_entity_decode( stripslashes( $this->content ) ),
            'type'       => $this->type,
            'created_at' => $this->created_at->toDateTimeString(),
        ];

        $guest_names = $this->guests->pluck( 'nickname' )->toArray();
        if( $this->user_id ){
            $data = array_merge( [
                'label'        => $this->label,
                'message_type' => $type,
                'user'         => User::getCache( $this->user_id, 'users' )->name,
                'guest_lists'  => $guest_names ? implode( '、', $guest_names ) : null,
                'url'          => $this->url,
                'picture'      => $this->picture,

            ], $data );
        } else{
            $data = array_merge( [
                'user'  => '系统发送',
                'guest' => $guest_names ? $guest_names[0] : null,
            ], $data );
        }

        return $data;
    }
}
