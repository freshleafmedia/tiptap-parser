<?php

declare(strict_types=1);

namespace FreshleafMedia\TiptapParser\Nodes;

use Illuminate\Support\Collection;

readonly class TableRow implements Node
{
    use RecursiveInnerHtml;

    public function __construct(
        public Collection $content,
        public Collection $marks = new Collection(),
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