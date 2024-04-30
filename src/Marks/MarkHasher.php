<?php

declare(strict_types=1);

namespace FreshleafMedia\TiptapParser\Marks;

trait MarkHasher
{
    public function getHash(): string
    {
        return hash('md5', self::class . '#' . json_encode($this));
    }
}
