<?php


namespace App\Admin\Renderable;


use App\Admin\Repositories\AppUser;
use Dcat\Admin\Grid;
use Dcat\Admin\Grid\LazyRenderable;

class UserTable extends LazyRenderable
{

    public function grid(): Grid
    {
        return Grid::make(new AppUser(), function (Grid $grid) {
            $grid->column("id");
            $grid->column("nickname", "用户昵称");
            $grid->filter(function (Grid\Filter $filter) {
                $filter->panel();
                $filter->like("nickname");
            });
        });
    }
}
