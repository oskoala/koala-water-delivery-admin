<?php

namespace App\Admin\Repositories;

use App\Models\AppTicketPackage as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class AppTicketPackage extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
