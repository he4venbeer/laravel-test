<?php

namespace App\Modules\Property\Models;

use App\Models\AppModel;

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
