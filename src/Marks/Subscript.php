<?php

declare(strict_types=1);

namespace FreshleafMedia\TipTapParser\Marks;

readonly class Subscript implements Mark
{
    use MarkHasher;

    public function renderOpen(): string
    {
        return '<sub>';
    }

    public function renderClose(): string
    {
        return '</sub>';
    }

    public static function fromArray(array $array): self
    {
        return new self();
    }
}
