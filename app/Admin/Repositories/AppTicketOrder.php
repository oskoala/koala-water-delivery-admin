<?php

namespace App\Admin\Repositories;

use App\Models\AppTicketOrder as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class AppTicketOrder extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
