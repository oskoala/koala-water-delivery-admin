<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\AppTicketPackageOrder;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;

class AppTicketPackageOrderController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new AppTicketPackageOrder(), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('user_id');
            $grid->column('ticket_package_id');
            $grid->column('no');
            $grid->column('transaction_id');
            $grid->column('total_price');
            $grid->column('status');
            $grid->column('snapshot');
            $grid->column('paid_at');
            $grid->column('closed_at');
            $grid->column('created_at');
            $grid->column('updated_at')->sortable();
        
            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('id');
        
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
        return Show::make($id, new AppTicketPackageOrder(), function (Show $show) {
            $show->field('id');
            $show->field('user_id');
            $show->field('ticket_package_id');
            $show->field('no');
            $show->field('transaction_id');
            $show->field('total_price');
            $show->field('status');
            $show->field('snapshot');
            $show->field('paid_at');
            $show->field('closed_at');
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
        return Form::make(new AppTicketPackageOrder(), function (Form $form) {
            $form->display('id');
            $form->text('user_id');
            $form->text('ticket_package_id');
            $form->text('no');
            $form->text('transaction_id');
            $form->text('total_price');
            $form->text('status');
            $form->text('snapshot');
            $form->text('paid_at');
            $form->text('closed_at');
        
            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
