<?php

namespace App\models;


/**
 * Class Menu
 * @package App\models
 */
class Menu extends BaseModel
{


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

}
