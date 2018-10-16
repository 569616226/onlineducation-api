<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Venturecraft\Revisionable\RevisionableTrait;

class Message extends BaseModel
{
    use RevisionableTrait;

    protected $revisionCreationsEnabled = true;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'type',
        'content',
        'user_id',
        'label',
        'url',
        'picture',
    ];

    public function guests()
    {
        return $this->belongsToMany( Guest::class );
    }

}
