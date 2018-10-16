<?php

namespace App\Http\Resources\Mobile;

use App\Models\Lesson;
use App\Models\Video;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Cache;

class Section extends Resource
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
			'id'                    => $this->id,
			'name'                  => $this->name,
			'lesson_name'           => $this->lesson->name,
			'is_free'               => $this->section_is_free,
			'is_learned'            => $this->section_is_learned,
			'is_last_learned'       => $this->section_is_last_learned,
			'duration'              => $this->section_duration,
		];
	}
}
