<?php

namespace App\Http\Controllers;

use Illuminate\Config\Repository;
use Illuminate\Container\Container;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/**
 *@property Request $request
 *@property Repository $config
 *@property Application $app
 */
abstract class CoreAbstractController extends Controller
{
    /**
     * @param null $view
     * @param array $data
     * @param array $mergeData
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    protected function getView($view = null, $data = [], $mergeData = []) : \Illuminate\View\View
    {
        $actionLists = explode('@', Route::currentRouteAction());
        $controller = str_replace('App\Http\Controllers\\', '', $actionLists[0]);
        $controller = str_replace('Controller', '', $controller);
        $pathList = explode('\\', strtolower($controller));
        $pathList[] = $actionLists[1];

        return view($view ?? implode('.', $pathList), $data, $mergeData);
    }

    /**
     * @param string $name
     * @return mixed|null
     */
    final public function __get(string $name)
    {
        $container = Container::getInstance();
        return $container->has($name) ? $container->get($name) : null;
    }
}
