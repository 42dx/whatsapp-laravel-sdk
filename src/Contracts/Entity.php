<?php

namespace The42dx\Whatsapp\Contracts;

use Illuminate\Support\Collection;

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
    public function __construct(array $attributes = []);

    public function __get(string $key): Collection|Entity|string|int|float|null;

    public function setAttributes(array $attributes = []): self;

    public function toArray(): array;

    public function toJson(): string;
}
