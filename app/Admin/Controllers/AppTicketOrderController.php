<?php

namespace App\Admin\Controllers;

use App\Admin\Renderable\UserTable;
use App\Admin\Repositories\AppTicketOrder;
use App\Models\AppUser;
use App\Status\TicketOrderStatus;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;

class AppTicketOrderController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new AppTicketOrder(), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('user_id', "下单用户")->display(function ($user_id) {
                return AppUser::query()->find($user_id)->nickname ?? "";
            });
            $grid->column('snapshot', "订单快照")->display(function ($snapshot) {
                return $snapshot['name'];
            });
            $grid->column('no');
            $grid->column('transaction_id');
            $grid->column('total_price');
            $grid->column('num');
            $grid->column('status')->using(TicketOrderStatus::getMap());
            $grid->column('paid_at');
            $grid->column('closed_at');
            $grid->column('created_at');
            $grid->column('updated_at')->sortable();

            $grid->filter(function (Grid\Filter $filter) {
                $filter->panel();
                $filter->equal("status")->select(TicketOrderStatus::getMap());
                $filter->like("no");
                $filter->equal("user_id", "下单用户")->selectTable(UserTable::make())->model(AppUser::class, "id", "nickname");
            });
            $grid->model()->orderByDesc("id");
            $grid->disableViewButton();
            $grid->disableDeleteButton();
            $grid->disableBatchDelete();
            $grid->disableCreateButton();
            $grid->disableEditButton();
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
        return Show::make($id, new AppTicketOrder(), function (Show $show) {
            $show->field('id');
            $show->field('user_id');
            $show->field('ticket_type_id');
            $show->field('no');
            $show->field('transaction_id');
            $show->field('total_price');
            $show->field('num');
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
        return Form::make(new AppTicketOrder(), function (Form $form) {
            $form->display('id');
            $form->text('user_id');
            $form->text('ticket_type_id');
            $form->text('no');
            $form->text('transaction_id');
            $form->text('total_price');
            $form->text('num');
            $form->text('status');
            $form->text('snapshot');
            $form->text('paid_at');
            $form->text('closed_at');

            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
