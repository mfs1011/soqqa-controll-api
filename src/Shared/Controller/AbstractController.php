<?php

declare(strict_types=1);

namespace App\Shared\Controller;

use App\Shared\Controller\Constants\ResponseFormat;
use App\Shared\DTO\BaseResponseDTO;
use App\Shared\DTO\CollectionResponseDTO;
use App\Shared\DTO\ItemResponseDTO;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class AbstractController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly ValidatorInterface $validator
    ) {
    }

    protected function collectionResponse(
        array $data,
        ?object $meta = null,
        string $message = 'Success',
        int $statusCode = 200,
        array $context = []
    ): JsonResponse {
        return $this->json(
            new CollectionResponseDTO(
                message: $message,
                data: $data,
                meta: $meta
            ),
            $statusCode,
            context: $context
        );
    }

    protected function itemResponse(
        object $data,
        string $message = 'Success',
        int $statusCode = 200,
        array $context = []
    ): JsonResponse {
        return $this->json(
            new ItemResponseDTO(
                message: $message,
                data: $data
            ),
            $statusCode,
            context: $context
        );
    }


    protected function success(
        mixed $data,
        string $message = 'Operation successful',
        int $status = 200,
        array $context = []
    ): JsonResponse
    {
        return $this->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
        ], status: $status, context: $context);
    }

    protected function error(
        array $errors = [],
        string $message = 'An error occurred',
        int $status = 500
    ): JsonResponse
    {
        return $this->json([
            'status' => 'error',
            'message' => $message,
            'errors' => $errors,
        ], $status);
    }

//    protected function simpleResponsePagination(
//        Paginator $paginator,
//        Request $request,
//        string $message = 'Operation successful',
//        int $status = 200,
//    ): JsonResponse
//    {
//        $page = $request->query->getInt('page', 1);
//        $total = $paginator->count();
//        $limit = $request->query->getInt('limit', 10);
//
//        $pagination = [
//            'current_page' => $page,
//            'total_pages' => (int) ceil($total / $limit),
//            'total' => $total,
//            'per_page' => $limit,
//        ];
//
//        return $this->json([
//            'status' => 'success',
//            'message' => $message,
//            'data' => $paginator,
//            'pagination' => $pagination,
//        ], $status);
//    }

    protected function getSerializer(): SerializerInterface
    {
        return $this->serializer;
    }

    protected function validate(object $data): ConstraintViolationListInterface
    {
        return $this->validator->validate($data);
    }

    /**
     * @param Request $request
     * @param string $dtoClass
     * @param string $format
     * @return object
     * @throws ExceptionInterface
     */
    protected function getDtoFromRequest(
        Request $request,
        string $dtoClass,
        string $format = ResponseFormat::JSON
    ): object {
        return $this->getSerializer()->deserialize(
            $request->getContent(),
            $dtoClass,
            $format
        );
    }

}
