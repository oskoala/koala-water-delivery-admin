<?php

namespace App\Admin\Repositories;

use App\Models\AppUserTicket as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class AppUserTicket extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
