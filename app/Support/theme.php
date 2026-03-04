<?php

function theme_view(string $view, array $data = [])
{
    $theme = config('theme.active', 'default');
    return view("themes.$theme.$view", $data);
}