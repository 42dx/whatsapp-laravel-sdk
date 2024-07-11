<?php

namespace The42dx\Whatsapp\Contracts;

/**
 * Entity
 *
 * Interface for entities in the package.
 *
 * @package The42dx\Whatsapp\Contracts
 *
 * @see \The42dx\Whatsapp\Abstracts\Entity
 */
interface Entity {
    public function __construct(?array $attributes = []);

    public function __get(string $key): mixed;

    public function setAttributes(?array $attributes = []): self;

    public function toArray(): array;

    public function toJson(): string;
}
