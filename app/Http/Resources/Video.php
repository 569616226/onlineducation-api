<?php

namespace App\Http\Resources;

use App\Models\Section;
use App\Models\VideoUrl;
use Illuminate\Http\Resources\Json\Resource;

class Video extends Resource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @param  \Illuminate\Http\Request
	 * @return array
	 */
	public function toArray( $request )
	{
        return [
			'id'         => $this->id,
			'name'       => $this->name,
			'status'     =>  $this->video_status,
			'url'        => $this->video_url,
			'size'       => $this->video_size,
			'duration'   => $this->video_duration,
			'created_at' => $this->created_at->toDateTimeString(),
		];
    }
}
