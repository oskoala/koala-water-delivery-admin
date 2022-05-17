<?php

namespace App\Admin\Controllers;

use App\Admin\Renderable\TicketTypeTable;
use App\Admin\Repositories\AppTicketPackage;
use App\Models\AppTicketType;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;

class AppTicketPackageController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new AppTicketPackage(), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('title');
            $grid->column('image')->image('', 50);
            $grid->column('ticket_type_id')->display(function ($ticket_type_id) {
                return AppTicketType::query()->find($ticket_type_id)->name ?? '';
            });
            $grid->column('num');
            $grid->column('price');
            $grid->column('scribing_price');
            $grid->column('show')->switch();
            $grid->column('order');
            $grid->column('recommend')->switch();
            $grid->column('created_at');
            $grid->column('updated_at')->sortable();

            $grid->filter(function (Grid\Filter $filter) {
                $filter->panel();
                $filter->like("title");
                $filter->equal("ticket_type_id")->selectTable(TicketTypeTable::make())->model(AppTicketType::class, "id", "name");
            });


            $grid->disableDeleteButton();
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
        return Show::make($id, new AppTicketPackage(), function (Show $show) {
            $show->field('id');
            $show->field('title');
            $show->field('image')->image("", 50);
            $show->field('detail')->unescape();
            $show->field('ticket_type_id')->as(function ($ticket_type_id){
                return AppTicketType::query()->find($ticket_type_id)->name ?? '';
            });
            $show->field('num');
            $show->field('price');
            $show->field('scribing_price');
            $show->field('show')->using([
                0 => "否",
                1 => "是"
            ]);
            $show->field('order');
            $show->field('recommend')->using([
                0 => "否",
                1 => "是"
            ]);
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
        return Form::make(new AppTicketPackage(), function (Form $form) {
            $form->block(8, function (Form\BlockForm $form) {
                $form->title("基本信息设置");
                $form->text('title')->required();
                $form->photo('image')->path("ticket")->required();
                $form->selectTable("ticket_type_id")->title("水票类型")
                    ->from(TicketTypeTable::make())
                    ->model(AppTicketType::class, 'id', 'name')
                    ->required();
                $form->editor('detail')->required();
                $form->showFooter();
            });
            $form->block(4, function (Form\BlockForm $form) {
                $form->number('num')->required();
                $form->decimal('price')->required();
                $form->decimal('scribing_price')->required();
                $form->switch('show')->default(true);
                $form->number('order')->default(99);
                $form->switch('recommend')->default(false);

                $form->display('created_at');
                $form->display('updated_at');
            });
        });
    }
}
