<?php

namespace App\Admin\Controllers;

use App\Admin\Metrics\Examples;
use App\Http\Controllers\Controller;
use Dcat\Admin\Http\Controllers\Dashboard;
use Dcat\Admin\Layout\Column;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Layout\Row;

class HomeController extends Controller
{
    public function index(Content $content)
    {
        return $content
            ->header('数据统计')
            ->description('平台数据统计')
            ->body(function (Row $row) {
                $row->column(12, function (Column $column) {
                    $column->row(Examples\NewTicketOrder::make());
                });

                $row->column(12, function (Column $column) {
                    $column->row(Examples\NewWaterOrder::make());
                });

                $row->column(12, function (Column $column) {
                    $column->row(Examples\NewUser::make());
                });
                $row->column(3, function (Column $column) {
                    $column->row(Examples\CreatedWaterOrder::make());
                });

                $row->column(3, function (Column $column) {
                    $column->row(Examples\ReceivedWaterOrder::make());
                });

                $row->column(3, function (Column $column) {
                    $column->row(Examples\FinishedWaterOrder::make());
                });

                $row->column(3, function (Column $column) {
                    $column->row(Examples\CanceledWaterOrder::make());
                });
            });
    }
}
