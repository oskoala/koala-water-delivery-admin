<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\AppUser;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;

class AppUserController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new AppUser(), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('nickname');
            $grid->column('avatar')->image('', 50);
            $grid->column('mobile');
            $grid->column('is_write_off_clerk')->switch();
            $grid->column('is_disabled')->switch();
            $grid->column('created_at');
            $grid->column('updated_at')->sortable();

            $grid->filter(function (Grid\Filter $filter) {
                $filter->panel();
                $filter->like("nickname");
            });
        });
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     *
     * @return Show
     */
    protected function detail($id)
    {
        return Show::make($id, new AppUser(), function (Show $show) {
            $show->field('id');
            $show->field('openid');
            $show->field('nickname');
            $show->field('avatar')->image("", 50);
            $show->field('mobile');
            $show->field('address_id');
            $show->field('is_write_off_clerk')->using([
                0 => "否",
                1 => "是"
            ]);
            $show->field('is_disabled')->using([
                0 => "否",
                1 => "是"
            ]);;
            $show->field('created_at');
            $show->field('updated_at');
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Form::make(new AppUser(), function (Form $form) {
            $form->display('id');
            $form->text('openid');
            $form->text('nickname');
            $form->photo('avatar')
                ->path("avatars")
                ->nametype('datetime')
                ->remove(true);  //可删除;

            $form->text('mobile');
            $form->select('address_id')->options([
                0 => "无",
            ]);
            $form->switch('is_write_off_clerk')->default(0);
            $form->switch('is_disabled')->default(0);

            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
