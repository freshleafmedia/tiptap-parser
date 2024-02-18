<?php

declare(strict_types=1);

namespace FreshleafMedia\TipTapParser\Marks;

readonly class Bold implements Mark
{
    use MarkHasher;

    public function renderOpen(): string
    {
        return '<strong>';
    }

    public function renderClose(): string
    {
        return '</strong>';
    }

    public static function fromArray(array $array): static
    {
        return new static();
    }
}