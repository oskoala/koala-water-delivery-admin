<?php

namespace App\Admin\Controllers;

use App\Admin\Renderable\TicketTypeTable;
use App\Admin\Renderable\UserTable;
use App\Admin\Repositories\AppUserTicket;
use App\Models\AppTicketType;
use App\Models\AppUser;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;

class AppUserTicketController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new AppUserTicket(), function (Grid $grid) {

            $grid->column('id')->sortable();
            $grid->column('user_id', "用户")->display(function ($user_id) {
                return AppUser::query()->find($user_id)->nickname ?? "";
            });
            $grid->column('ticket_type_id', "水票")->display(function ($ticket_type_id) {
                return AppTicketType::query()->find($ticket_type_id)->name ?? "";
            });
            $grid->column('num');
            $grid->column('created_at');
            $grid->column('updated_at')->sortable();

            $grid->filter(function (Grid\Filter $filter) {
                $filter->panel();
                $filter->equal('user_id', "用户")->selectTable(UserTable::make())->model(AppUser::class, "id", "nickname");
            });

            $grid->disableViewButton();
            $grid->disableDeleteButton();
            $grid->disableCreateButton();
            $grid->disableBatchDelete();
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
        return Show::make($id, new AppUserTicket(), function (Show $show) {
            $show->field('id');
            $show->field('user_id');
            $show->field('ticket_type_id');
            $show->field('num');
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
        return Form::make(new AppUserTicket(), function (Form $form) {
            $form->display('id');
            $form->selectTable('user_id')->from(UserTable::make())->model(AppUser::class, "id", "nickname");
            $form->selectTable('ticket_type_id')->from(TicketTypeTable::make())->model(AppTicketType::class, "id", "name");
            $form->number('num');
            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
