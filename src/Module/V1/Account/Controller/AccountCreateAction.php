<?php

namespace App\Module\V1\Account\Controller;

use App\Module\V1\Account\DTO\Input\AccountCreateDTO;
use App\Module\V1\Account\Mapper\AccountMapper;
use App\Module\V1\Account\UseCase\CreateAccount;
use App\Shared\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/accounts', methods: ["POST"])]
class AccountCreateAction extends AbstractController
{
    public function __invoke(
        #[MapRequestPayload(
            serializationContext: ['groups' => ['account:write']]
        )] AccountCreateDTO $accountData,
        CreateAccount $createAccount,
        AccountMapper $accountMapper
    ): Response
    {
        $account = $createAccount->execute($accountData, $this->getUser());

        return $this->itemResponse(
            data: $accountMapper->fromAccount($account),
            message: 'User successfully created.',
            statusCode: Response::HTTP_CREATED
        );
    }
}
