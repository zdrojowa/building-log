<?php

namespace Selene\Modules\BuildingLogModule;

use Jenssegers\Mongodb\Eloquent\Model;

class BuildingLogPhoto extends Model
{

    protected $connection = 'mongodb';

    protected $fillable = [
        '_sequence',
        'file'
    ];


}
