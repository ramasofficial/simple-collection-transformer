<?php

declare(strict_types=1);

namespace Ramasdev\SimpleCollectionTransformer\Attributes;

use Attribute;

#[Attribute] class CollectionAttribute
{
    public function __construct(private string $class)
    {
    }

    public function getClass(): string
    {
        return $this->class;
    }
}
