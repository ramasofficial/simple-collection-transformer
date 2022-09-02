<?php

declare(strict_types=1);

namespace Ramasdev\SimpleCollectionTransformer;

use Illuminate\Support\Collection;
use Ramasdev\SimpleCollectionTransformer\Attributes\CollectionAttribute;
use Ramasdev\SimpleCollectionTransformer\Exceptions\CannotFindAttributeException;
use ReflectionClass;
use ReflectionException;

class CollectionTransformer
{
    /**
     * @throws ReflectionException
     */
    public function transform(array $data, string $class, callable $callable): Collection
    {
        $collectionClass = $this->scanClass($class);

        if (!$collectionClass) {
            throw new CannotFindAttributeException('Cannot find collection attribute!');
        }

        /** @var Collection $collection */
        $collection = new $collectionClass();
        foreach ($data as $item) {
            if (!$callable($item)) {
                continue;
            }

            $collection->add($callable($item));
        }

        return $collection;
    }

    /**
     * @throws ReflectionException
     */
    private function scanClass(string $class): ?string
    {
        $reflection = new ReflectionClass($class);
        $classAttributes = $reflection->getAttributes();

        foreach ($classAttributes as $attribute) {
            if ($attribute->getName() === CollectionAttribute::class) {
                return $attribute->getArguments()[0];
            }
        }

        return null;
    }
}
