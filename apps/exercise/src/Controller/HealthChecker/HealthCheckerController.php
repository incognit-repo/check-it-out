<?php

declare(strict_types=1);

namespace CheckItOut\Apps\Exercise\Controller\HealthChecker;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

final class HealthCheckerController
{
    public function __invoke(Request $request): Response
    {
        return new JsonResponse(
            [
                'exercise' => 'ok',
            ]
        );
    }
}
