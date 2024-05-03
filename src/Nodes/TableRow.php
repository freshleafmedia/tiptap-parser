<?php

declare(strict_types=1);

namespace FreshleafMedia\TiptapParser\Nodes;

use Illuminate\Support\Collection;

readonly class TableRow implements Node
{
    use RecursiveInnerHtml;

    public function __construct(
        public array $content = [],
        public array $marks = [],
    )
    {
    }

    public function render(): string
    {
        return <<<HTML
            <tr>
                {$this->getInnerHtml()}
            </tr>
        HTML;
    }

    public static function fromArray(array $array): static
    {
        return new static(
            $array['content'],
            $array['marks'],
        );
    }
}