<?php
return [
    401 => function () {
        http_response_code(401);
        echo '401 - Unauthorized';
    },
    403 => function () {
        http_response_code(403);
        echo '403 - Forbidden';
    },
    404 => function () {
        http_response_code(404);
        echo '404 - Page not found';
    },
    500 => function () {
        http_response_code(500);
        echo '500 - Internal Server Error';
    },
];