<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;
use Symfony\Component\Security\Core\Exception\InsufficientAuthenticationException;

class JwtEntryPoint implements AuthenticationEntryPointInterface
{
    public function start(
        Request $request,
        ?\Throwable $authException = null
    ): JsonResponse {

        if ($authException instanceof BadCredentialsException) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Login yoki parol noto‘g‘ri'
            ], Response::HTTP_UNAUTHORIZED);
        }

        if ($authException instanceof AuthenticationCredentialsNotFoundException) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Token topilmadi'
            ], Response::HTTP_UNAUTHORIZED);
        }

        if ($authException instanceof InsufficientAuthenticationException) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Avval tizimga kiring'
            ], Response::HTTP_UNAUTHORIZED);
        }

        // default fallback
        return new JsonResponse([
            'status' => 'error',
            'message' => 'Authentication required'
        ], Response::HTTP_UNAUTHORIZED);
    }
}
