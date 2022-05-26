<?php

namespace App\Admin\Controllers;

use App\Admin\Renderable\TicketTypeTable;
use App\Admin\Repositories\AppTicketType;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;

class AppTicketTypeController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new AppTicketType(), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('name');
            $grid->column('image')->image("", 50);
            $grid->column('price');
            $grid->column('min_buy_num');
            $grid->column('show')->switch();
            $grid->column('order');
            $grid->column('recommend')->switch();
            $grid->column('created_at');
            $grid->column('updated_at')->sortable();

            $grid->filter(function (Grid\Filter $filter) {
                $filter->panel();
                $filter->like('name');
            });

            $grid->disableBatchDelete();
            $grid->disableDeleteButton();
            $grid->disableViewButton();
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
        return Show::make($id, new AppTicketType(), function (Show $show) {
            $show->field('id');
            $show->field('name');
            $show->field('image');
            $show->field('price');
            $show->field('min_buy_num');
            $show->field('show');
            $show->field('order');
            $show->field('recommend');
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
        return Form::make(new AppTicketType(), function (Form $form) {

            $form->block(8, function (Form\BlockForm $form) {
                $form->title("基本信息设置");
                $form->text('name');
                $form->photo('image')->path("ticket")->required();
                $form->editor('detail')->required();
                $form->showFooter();
            });
            $form->block(4, function (Form\BlockForm $form) {
                $form->number('min_buy_num')->required();
                $form->decimal('price')->required();
                $form->switch('show')->default(true);
                $form->number('order')->default(99);
                $form->switch('recommend')->default(false);

                $form->display('created_at');
                $form->display('updated_at');
            });
        });
    }
}
