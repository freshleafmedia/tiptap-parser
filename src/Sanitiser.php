<?php

declare(strict_types=1);

namespace FreshleafMedia\TiptapParser;

class Sanitiser
{
    public static function escape(?string $value): string
    {
        return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}