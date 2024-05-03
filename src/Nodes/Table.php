<?php

declare(strict_types=1);

namespace FreshleafMedia\TiptapParser\Nodes;

use Illuminate\Support\Collection;

readonly class Table implements Node
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
            <table>
                {$this->getInnerHtml()}
            </table>
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