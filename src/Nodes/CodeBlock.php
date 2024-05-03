<?php

declare(strict_types=1);

namespace FreshleafMedia\TiptapParser\Nodes;

use Illuminate\Support\Collection;

readonly class CodeBlock implements Node
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
            <pre>
                <code>{$this->getInnerHtml()}</code>
            </pre>
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