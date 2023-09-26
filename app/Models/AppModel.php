<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * @method Collection get()
 * @method AppModel findOrFail(int $id)
 * @method Model create(array $data)
 * @method Builder where(array|string $conditions, $operator = null)
 */
abstract class AppModel extends Model
{
    protected $guarded = [];
    public const CREATED_AT = 'created_at';
    public const UPDATED_AT = 'updated_at';
    public $timestamps = true;
}
