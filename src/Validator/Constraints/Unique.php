<?php

declare(strict_types=1);

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD)]
class Unique extends Constraint
{
    public string $entity;
    public string $field;
    public string $message;

    public function __construct(
        mixed $options = null,
        ?array $groups = null,
        mixed $payload = null,
        ?string $entity = null,
        ?string $field = null,
        string $message = "This <<{{ fieldName }}>> with value <<{{ value }}>> is already taken."
    )
    {
        $this->entity = $entity;
        $this->field = $field;
        $this->message = $message;
        parent::__construct($options, $groups, $payload);
    }

    public function validatedBy(): string
    {
        return static::class.'Validator';
    }
}
