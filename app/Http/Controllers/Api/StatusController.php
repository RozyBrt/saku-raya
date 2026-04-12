<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use OpenApi\Attributes as OA;

class StatusController extends Controller
{
    #[OA\Get(
        path: '/api/check-status',
        summary: 'Check API Status',
        tags: ['Status'],
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'API is working',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string'),
                        new OA\Property(property: 'project', type: 'string'),
                        new OA\Property(property: 'message', type: 'string'),
                        new OA\Property(property: 'db_connection', type: 'string'),
                    ]
                )
            )
        ]
    )]
    public function check()
    {
        return response()->json([
            'status' => 'success',
            'project' => 'Saku-Raya',
            'message' => 'Controller is working, bray! 🚀',
            'db_connection' => config('database.default')
        ]);
    }
}