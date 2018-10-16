<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class Order extends BaseModel
{
    use SoftDeletes, RevisionableTrait;

    protected $revisionCreationsEnabled = true;/*记录增加动作*/

    /*记录删除动作*/
    protected $keepRevisionOf = array(
        'type',
        'deleted_at',
    );

    /**
     * 应该被调整为日期的属性
     *
     * @var array
     */
    protected $dates = [ 'deleted_at', 'pay_date', 'start', 'end', ];

    public $fillable = [
        'name',
        'order_no',
        'type',
        'status',
        'order_type_id',
        'guest_id',
        'pay_type',
        'pay_date',
        'mouth',
        'start',
        'end',
        'price',
        'title',
        'pictrue',
    ];

    public function guest()
    {
        return $this->belongsTo( Guest::class );
    }
}
