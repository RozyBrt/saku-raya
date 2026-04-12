<?php

namespace App;

use OpenApi\Attributes as OA;

#[OA\SecurityScheme(
    securityScheme: 'bearerAuth',
    type: 'http',
    scheme: 'bearer',
    bearerFormat: 'JWT'
)]
#[OA\OpenApi(
    info: new OA\Info(
        title: 'Saku Raya API',
        version: '1.0.0',
        description: 'API untuk Aplikasi Saku Raya'
    ),
    servers: [
        new OA\Server(
            url: 'http://localhost',
            description: 'Local Server'
        )
    ]
)]
class Swagger
{
}