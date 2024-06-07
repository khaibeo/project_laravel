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
