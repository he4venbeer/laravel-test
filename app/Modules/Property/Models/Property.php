<?php

namespace App\Modules\Property\Models;

use App\Models\AppModel;

/**
 * @property int $id
 * @property string $name
 * @property string $address
 * @property int $owner_id
 * @property boolean $is_sold
 */
class Property extends AppModel
{
    protected $fillable = [
        'name',
        'address',
        'owner_id',
        'is_sold',
    ];

    protected $casts = [
        'created_at' => 'datetime: Y-m-d H:i:s',
        'updated_at' => 'datetime: Y-m-d H:i:s',
    ];
}
