<?php

namespace App\Admin\Controllers;

use App\Admin\Renderable\UserTable;
use App\Admin\Repositories\AppWaterOrder;
use App\Models\AppTicketType;
use App\Models\AppUser;
use App\Status\WaterOrderStatus;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;

class AppWaterOrderController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new AppWaterOrder(), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('user_id', "用户")->display(function ($user_id) {
                return AppUser::query()->find($user_id)->nickname ?? "";
            });
            $grid->column('ticket_type_id', "水票")->display(function ($ticket_type_id) {
                return AppTicketType::query()->find($ticket_type_id)->name ?? "";
            });
            $grid->column('receipt_user_id', "配送员")->display(function ($receipt_user_id) {
                return AppUser::query()->find($receipt_user_id)->nickname ?? "";
            });
            $grid->column('no');
            $grid->column('num');
            $grid->column('address')->display(function ($address) {
                return $address['province'] . " <br> " . $address['city'] . " <br> " . $address['district'] . " <br> " . $address['detail'];
            });
            $grid->column('status')->using(WaterOrderStatus::getMap());
            $grid->column('closed_at');
            $grid->column('finished_at');
            $grid->column('receipt_at');
            $grid->column('created_at');
            $grid->column('updated_at')->sortable();

            $grid->filter(function (Grid\Filter $filter) {
                $filter->panel();
                $filter->equal('user_id', "用户")->selectTable(UserTable::make())->model(AppUser::class, "id", "nickname");
                $filter->equal('receipt_user_id', "配送员")->selectTable(UserTable::make())->model(AppUser::class, "id", "nickname");
                $filter->like("no");
                $filter->equal("status")->select(WaterOrderStatus::getMap());
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
        return Show::make($id, new AppWaterOrder(), function (Show $show) {
            $show->field('id');
            $show->field('user_id');
            $show->field('ticket_type_id');
            $show->field('receipt_user_id');
            $show->field('no');
            $show->field('num');
            $show->field('address');
            $show->field('status');
            $show->field('closed_at');
            $show->field('finished_at');
            $show->field('receipt_at');
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
        return Form::make(new AppWaterOrder(), function (Form $form) {
            $form->display('id');
            $form->text('user_id');
            $form->text('ticket_type_id');
            $form->text('receipt_user_id');
            $form->text('no');
            $form->text('num');
            $form->text('address');
            $form->text('status');
            $form->text('closed_at');
            $form->text('finished_at');
            $form->text('receipt_at');

            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
