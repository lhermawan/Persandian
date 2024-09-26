<?php

namespace App\Composers;

use Illuminate\View\View;

class IndexViewComposer
{
    public function compose(View $view)
    {
        $view->with('SPAN_ACTIVE_START', '<span class="label label-info"> ');
        $view->with('SPAN_ACTIVE_END', ' </span>');

        $view->with('SPAN_NOTACTIVE_START', '<span class="label label-warning"> ');
        $view->with('SPAN_NOTACTIVE_END', ' </span>');
    }
}