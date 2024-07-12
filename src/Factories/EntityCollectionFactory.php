<?php

namespace The42dx\Whatsapp\Factories;

use Illuminate\Support\Collection;
use The42dx\Whatsapp\Contracts\Entity;

/**
 * EntityCollectionFactory
 *
 * Factory class to create a collection of
 * Whatsapp Business API entities.
 *
 * @package The42dx\Whatsapp\Factories
 *
 */
class EntityCollectionFactory {
    /**
     * make
     *
     * Factory method to create a collection of entities.
     *
     * @param  class-string<\The42dx\Whatsapp\Contracts\Entity> $entity The entity class to create
     * @param  array  $items  The items to create the collection from
     * @return \Illuminate\Support\Collection The collection of entities
     * @throws \InvalidArgumentException If the entity class does not implement the \The42dx\Whatsapp\Contracts\Entity contract
     */
    public static function make(string $entityClass = Entity::class, ?array $items = null): ?Collection {
        /**
         * entry
         *
         * @var \The42dx\Whatsapp\Contracts\Entity
         */
        $entity = new $entityClass;

        if (!($entity instanceof Entity)) {
            // TODO: Create specific exceptions for each case
            throw new \InvalidArgumentException('Entity class [' . $entity . '] does not implement the [' . Entity::class . '] contract');
        }

        $collection = new Collection();

        if (is_null($items) || empty($items)) {
            return null;
        }

        foreach ($items as $item) {
            $collection->add($entity->setAttributes($item));
        }

        return $collection;
    }
}
