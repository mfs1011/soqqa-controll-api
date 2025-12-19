<?php

namespace App\Validator\Constraints;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class UniqueValidator extends ConstraintValidator
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof Unique) {
            throw new UnexpectedTypeException($constraint, Unique::class);
        }

        if ($value === null || $value === '') {
            return;
        }

        if (!is_scalar($value)) {
            throw new UnexpectedValueException($value, 'scalar');
        }

        $repository = $this->entityManager->getRepository($constraint->entity);

        $existing = $repository->findOneBy([
            $constraint->field => $value
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
