<?php

namespace App\Admin\Repositories;

use App\Models\AppUser as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class AppUser extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
