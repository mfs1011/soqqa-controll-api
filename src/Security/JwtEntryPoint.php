<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;
use Symfony\Component\HttpFoundation\Response;

class JwtEntryPoint implements AuthenticationEntryPointInterface
{
    public function start(
        Request $request,
        ?\Throwable $authException = null
    ): JsonResponse {
        return new JsonResponse([
            'status' => 'error',
            'message' => 'Authentication required',
        ], Response::HTTP_UNAUTHORIZED);
    }
}
