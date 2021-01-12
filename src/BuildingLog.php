<?php

namespace Selene\Modules\BuildingLogModule;

use Jenssegers\Mongodb\Eloquent\Model;

/**
 * @method create(array $all)
 */
class BuildingLog extends Model {

    protected $connection = 'mongodb';

    protected $table = 'building_logs';

    protected $fillable = [
        'year',
        'month'
    ];

    public function photos() {
        return $this->embedsMany(BuildingLogPhoto::class);
    }
}
