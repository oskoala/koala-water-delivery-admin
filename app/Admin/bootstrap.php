<?php

use Dcat\Admin\Admin;
use Dcat\Admin\Layout\Navbar;

/**
 * Dcat-admin - admin builder based on Laravel.
 * @author jqh <https://github.com/jqhph>
 *
 * Bootstraper for Admin.
 *
 * Here you can remove builtin form field:
 *
 * extend custom field:
 * Dcat\Admin\Form::extend('php', PHPEditor::class);
 * Dcat\Admin\Grid\Column::extend('php', PHPEditor::class);
 * Dcat\Admin\Grid\Filter::extend('php', PHPEditor::class);
 *
 * Or require js and css assets:
 * Admin::css('/packages/prettydocs/css/styles.css');
 * Admin::js('/packages/prettydocs/js/main.js');
 *
 */

Admin::navbar(function (Navbar $navbar) {
    $method            = config('admin.layout.horizontal_menu') ? 'left' : 'right';
    $setting_front_url = route("dcat.admin.setting.system");
    $navbar->$method(<<<HTML
<ul class="nav navbar-nav">
    <li class="nav-item">
        &nbsp;
        <a style="cursor: pointer" href="{$setting_front_url}">
            <i class="fa fa-spin fa-gear" style="font-size: 1.5rem"></i> 系统设置
        </a>
        &nbsp; &nbsp;
    </li>
</ul>
HTML
    );
});

\Dcat\Admin\Form\Field\Map::requireAssets();
