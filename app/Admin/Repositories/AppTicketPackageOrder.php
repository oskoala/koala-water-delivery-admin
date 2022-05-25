<?php

namespace App\Admin\Repositories;

use App\Models\AppTicketPackageOrder as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class AppTicketPackageOrder extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
