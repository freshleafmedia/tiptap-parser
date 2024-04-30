<?php

declare(strict_types=1);

namespace FreshleafMedia\TiptapParser\Marks;

readonly class Small implements Mark
{
    use MarkHasher;

    public function renderOpen(): string
    {
        return '<small>';
    }

    public function renderClose(): string
    {
        return '</small>';
    }

    public static function fromArray(array $array): static
    {
        return new static();
    }
}
