<?php

$routes = [
    '/test-subskill/index.php/api/test' => 'test',
    '/test-subskill/index.php/api/get/jobs' => 'list',
    '/test-subskill/index.php/api/jobs/add' => 'add',
    '/test-subskill/index.php/api/jobs/edit' => 'edit',
    '/test-subskill/index.php/api/jobs/create' => 'create',
    '/test-subskill/index.php/api/jobs/delete/{id}' => 'delete',
    '/test-subskill/index.php/api/get/jobs/filtered' => 'filter',
];

function handleRequest($requestUri, $controller) {
    global $routes;

    foreach ($routes as $route => $action) {
        if (strpos($route, '{id}') !== false) {
            $baseRoute = str_replace('{id}', '', $route);

            if (strpos($requestUri, $baseRoute) === 0) {
                $id = str_replace($baseRoute, '', $requestUri);

                if (method_exists($controller, $action)) {
                    $controller->$action($id);
                    return;
                }
            }
        } elseif ($requestUri == $route) {
            if (method_exists($controller, $action)) {
                $controller->$action();
                return;
            }
        }
    }

    http_response_code(404);
    echo json_encode(["error" => "la route existe pas"]);
}


?>
