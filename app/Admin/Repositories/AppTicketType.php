<?php

namespace App\Admin\Repositories;

use App\Models\AppTicketType as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class AppTicketType extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
