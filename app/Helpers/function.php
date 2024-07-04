<?php
function isRoute($routeList)
{
    if (!empty($routeList)) {
        foreach ($routeList as $route) {
            if (request()->is(trim($route, '/'))) {
                return true;
            }
        }
    }

    return false;
}

function activeSidebar($name, $routeList)
{
    return request()->is(trim(route('admin.' . $name . '.index', [], false), '/') . '/*') || request()->is(trim(route('admin.' . $name . '.index', [], false), '/')) || isRoute($routeList);
}

function activeMenu($name)
{
    return request()->is(trim(route($name, [], false), '/'));
}

function money($number, $currency = 'đ')
{
    return !empty($number) ? number_format($number) . ' ' . $currency : 'Miễn phí';
}

function getHour($seconds)
{
    $value = round($seconds / 60, 1);
    return $value . 'h';
}

function getSize($size, $type = 'KB')
{
    if($type == 'MB'){
        $result = round($size / 1024 / 1024, 2);
    }else{
        $result = round($size / 1024, 2);
    }

    return $result;
}
