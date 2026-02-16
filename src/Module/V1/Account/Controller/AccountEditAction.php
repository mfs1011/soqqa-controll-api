<?php

declare(strict_types=1);

namespace App\Module\V1\Account\Controller;

use App\Module\V1\Account\DTO\Input\AccountEditDTO;
use App\Module\V1\Account\Repository\AccountRepository;
use App\Module\V1\Account\Service\Manager\AccountManager;
use App\Shared\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/accounts/{id}', requirements: ['id' => '\d+'], methods: ['PATCH'])]
class AccountEditAction extends AbstractController
{
    public function __invoke(
        #[MapRequestPayload] AccountEditDTO $dto,
        int $id,
        AccountRepository $repository,
        AccountManager $manager
    ): Response
    {
        $account = $repository->findNotDeleted($id);
        $account->setName($dto->getName());

        $manager->save($account, true);

        return $this->itemResponse(
            data: $account,
            message: 'Account successfully updated.',
            statusCode: Response::HTTP_OK
        );
    }
}
