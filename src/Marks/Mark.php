<?php

declare(strict_types=1);

namespace FreshleafMedia\TiptapParser\Marks;

interface Mark
{
    public function getHash(): string;

    public function renderOpen(): string;

    public function renderClose(): string;

    public static function fromArray(array $array): static;
}
