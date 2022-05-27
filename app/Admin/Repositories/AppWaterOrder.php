<?php

namespace App\Admin\Repositories;

use App\Models\AppWaterOrder as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class AppWaterOrder extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
