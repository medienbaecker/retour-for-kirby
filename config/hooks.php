<?php

namespace distantnative\Retour;

use Kirby\Http\Router;

return [
    'route:after' => function ($route, $path, $method, $result, $final) {
        if ($final === true && empty($result) === true) {

            // skip ignored paths
            $ignore = option('distantnative.retour.ignore');
            if (in_array($path, $ignore) === true) {
                return $result;
            }

            try {
                $routes  = Retour::instance()->routes();
                $manual  = $routes->toRules('manual', false);
                $tracked = $routes->toRules('tracked', false);
                $routes  = $manual + $tracked;
                $router  = new Router($routes);
                return $router->call($path, $method);

            } catch (\Throwable $e) {
                if (option('distantnative.retour.logs') === true) {
                    Retour::instance()->log()->create(['path' => $path]);
                }
            }
        }
    },
    'page.changeSlug:after' => function ($newPage, $oldPage) {
        if (option('distantnative.retour.tracking') !== false) {
            Retour::instance()->routes()->track('slug', [
                'from' => $oldPage->id(),
                'to'   => $newPage->id()
            ]);
        }
    },
    'page.delete:after' => function ($status, $page) {
        if (option('distantnative.retour.deletions') !== false) {
            Retour::instance()->routes()->track('delete', [
                'from' => $page->id()
            ]);
        }
    }
];