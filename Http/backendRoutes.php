<?php

use Illuminate\Routing\Router;

/** @var Router $router */
$router->bind('page', function ($id) {
    return app(\Modules\Page\Repositories\PageRepository::class)->find($id);
});

$router->bind('page_type', function ($slug) {
    return app(\Modules\Page\Repositories\PageTypeRepository::class)->findBySlug($slug);
});

$router->group(['prefix' => 'page'], function (Router $router) {
    get('pages', ['as' => 'admin.page.page.index', 'uses' => 'PageController@index']);

    $router->group(['prefix' => 'pages/{page_type}'], function () {
        get('create', ['as' => 'admin.page.page.create', 'uses' => 'PageController@create']);
        post('', ['as' => 'admin.page.page.store', 'uses' => 'PageController@store']);
    });
    get('pages/{page}/edit', ['as' => 'admin.page.page.edit', 'uses' => 'PageController@edit']);
    put('pages/{page}/edit', ['as' => 'admin.page.page.update', 'uses' => 'PageController@update']);
    delete('pages/{page}', ['as' => 'admin.page.page.destroy', 'uses' => 'PageController@destroy']);
});
