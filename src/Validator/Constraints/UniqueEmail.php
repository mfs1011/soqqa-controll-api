<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD)]
class UniqueEmail extends Constraint
{
    public string $message = "This email with value {{ value }} is already taken.";
}
