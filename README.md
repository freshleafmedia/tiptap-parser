# Tiptap JSON Parser

Convert Tiptap JSON to HTML.

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
                    'marks' => [],
                ],
            ],
        ],
    ],
];

$html = \FreshleafMedia\TiptapParser\TiptapContent::fromArray($tipTapArray)->toHtml();
// <p>Hello world</p>
```


# Customising or Creating New Nodes/Marks

Create a custom Node class:

```php
readonly class CustomParagraph extends \FreshleafMedia\TiptapParser\Nodes\Paragraph
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

Each Node/Mark is instantiated from the Tiptap array via the `fromArray` method. This allows access you to access all 
context when creating Nodes/Marks.

The array typically takes this form:

```php
use Illuminate\Support\Collection;
use FreshleafMedia\TiptapParser\Nodes\Node;
use FreshleafMedia\TiptapParser\Marks\Mark;

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

For example if Tiptap exposed a `lang` attribute on the paragraph Node you could make use of it like this:

```php
readonly class CustomParagraph implements Node
{
    use RecursiveInnerHtml;

    public function __construct(
        public string $language,
        public Collection $content,
        public Collection $marks = new Collection(),
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
