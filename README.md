# TipTap JSON Parser

Convert TipTap JSON to HTML.

```php
$tipTapArray = [
    'type' => 'doc',
    'content' => [
        [
            'type' => 'paragraph',
            'attrs' => [],
            'content' => [
                [
                    'type' => 'text',
                    'text' => 'Hello world',
                    'marls' => [],
                ],
            ],
        ],
    ],
];

$html = \FreshleafMedia\TipTapParser\Parser::fromArray($tipTapArray)->toHtml();
$html = \FreshleafMedia\TipTapParser\TiptapContent::fromArray($tipTapArray)->toHtml();
```


# Customising or Creating New Nodes/Marks

Create a custom Node class:

```php
readonly class CustomParagraph extends \FreshleafMedia\TipTapParser\Nodes\Paragraph
{
    public function render(): string
    {
        return <<<HTML
            <p class="paragraph">
                {$this->getInnerHtml()}
            </p>
        HTML;
    }
}
```

```php
$html = Parser::fromArray($tipTapArray)
    ->registerNode('paragraph', CustomParagraph::class)
    ->toHtml();
```

Marks work in the same way:

```php
$html = Parser::fromArray($tipTapArray)
    ->registerMark('link', CustomLink::class)
    ->toHtml();
```


# Accessing Custom Attributes

Each Node/Mark is instantiated from the TipTap array via the `fromArray` method. This allows access you to access all 
context when creating Nodes/Marks.

The array typically takes this form:

```php
use Illuminate\Support\Collection;
use FreshleafMedia\TipTapParser\Nodes\Node;
use FreshleafMedia\TipTapParser\Marks\Mark;

[
    'type' => 'paragraph',
    'attrs' => [
        'class' => 'pull-left',
        'style' => null,
        ...
    ],
    'content' => Collection<Node>,
    'marks' => Collection<Mark>
]
```

For example if TipTap exposed a `lang` attribute on the paragraph Node you could make use of it like this:

```php
readonly class CustomParagraph implements Node
{
    use RecursiveInnerHtml;

    public function __construct(
        public string $language,
        public Collection $content,
        public Collection $marks,
    )
    {
    }

    public function render(): string
    {
        return <<<HTML
            <p lang="{$this->language}">
                {$this->getInnerHtml()}
            </p>
        HTML;
    }

    public static function fromArray(array $array): self
    {
        return new self(
            $array['attrs']['lang'] ?? 'en',
            $array['content'],
            $array['marks'],
        );
    }
}
```
