<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Doctrine\Filter;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;

class CreatedByFilter extends SQLFilter
{
    public const string CREATED_BY_FILTER = 'created_by_filter';

    public function addFilterConstraint(ClassMetadata $targetEntity, string $targetTableAlias): string
    {
        if (!$targetEntity->hasField('createdById')) {
            return '';
        }

        return sprintf(
            '%s.created_by_id = %s',
            $targetTableAlias,
            $this->getParameter('createdById')
        );
    }
}
