<?php


namespace App\Admin\Renderable;


use App\Admin\Repositories\AppTicketType;
use Dcat\Admin\Grid;
use Dcat\Admin\Grid\LazyRenderable;

class TicketTypeTable extends LazyRenderable
{

    public function grid(): Grid
    {
        return Grid::make(new AppTicketType(), function (Grid $grid) {
            $grid->column("id");
            $grid->column("name");
        });
    }
}
