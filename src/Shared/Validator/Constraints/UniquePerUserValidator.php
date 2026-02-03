<?php

namespace App\Shared\Validator\Constraints;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class UniquePerUserValidator extends ConstraintValidator
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly Security $security,
    )
    {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof UniquePerUser) {
            throw new UnexpectedTypeException($constraint, UniquePerUser::class);
        }

        if ($value === null || $value === '') {
            return;
        }

        if (!is_scalar($value)) {
            throw new UnexpectedValueException($value, 'scalar');
        }

        $repository = $this->entityManager->getRepository($constraint->entity);
        $user = $this->security->getUser();

        if (!$user) {
            throw new UnauthorizedHttpException('Unauthenticated');
        }

        // TODO case-insensitive qilish kerak bo'lishi mumkin
        $existing = $repository->findOneBy([
            $constraint->field => $value,
            'createdById' =>$user->getId(),
        ]);

        if ($existing !== null) {
            $this->context
                ->buildViolation($constraint->message)
                ->setParameter('{{ fieldName }}', (string) $constraint->field)
                ->setParameter('{{ value }}', (string) $value)
                ->addViolation();
        }
    }
}
