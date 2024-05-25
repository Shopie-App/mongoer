<?php

declare(strict_types=1);

namespace Shopie\Mongoer;

use Shopie\Mongoer\Contracts\CollectionInterface;

abstract class Collection implements CollectionInterface, \Iterator, \Countable
{
    private $position;

    protected array $items;

    public function add(Entity $entity): void
    {
        $this->items[] = $entity;
    }

    public function items(): array
    {
        return $this->items;
    }

    public function current(): mixed
    {
        return $this->items[$this->position];
    }

    public function key(): mixed
    {
        return $this->position;
    }

    public function next(): void
    {
        ++$this->position;
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    public function valid(): bool
    {
        return isset($this->items[$this->position]);
    }

    public function count(): int
    {
        return count($this->items);
    }
}