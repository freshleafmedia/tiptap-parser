<?php

declare(strict_types=1);

namespace FreshleafMedia\TipTapParser\Marks;

readonly class Code implements Mark
{
    use MarkHasher;

    public function renderOpen(): string
    {
        return '<code>';
    }

    public function renderClose(): string
    {
        return '</code>';
    }

    public static function fromArray(array $array): static
    {
        return new static();
    }
}