# Tiptap JSON Parser

Convert JSON from a Tiptap editor to HTML.

```php
use FreshleafMedia\TiptapParser\TiptapContent;

$tiptapArray = [
    'type' => 'paragraph',
    'content' => [
        [
            'type' => 'text',
            'text' => 'Hello world',
        ],
    ],
];

TiptapContent::fromArray($tiptapArray)->toHtml(); // <p>Hello world</p>
```


# Creating Custom Nodes

```php
use FreshleafMedia\TiptapParser\Nodes\Paragraph;

readonly class CustomParagraph extends Paragraph
{
    public function render(): string
    {
        return <<<HTML
            <p class="paragraph">
                {$this->renderInnerHtml()}
            </p>
            HTML;
    }
}

$html = Parser::fromArray($tiptapArray)
    ->registerNode('paragraph', CustomParagraph::class)
    ->toHtml(); // <p class="paragraph">Hello world</p>
```


# Accessing Custom Attributes

Nodes are instantiated via the `fromArray` method, the method is passed all the data from the original array.

For example given this array:

```php
[
    'type' => 'paragraph',
    'attrs' => [
        'lang' => 'en',
    ]
]
```

We can easily add the `lang` attribute to the `p` element like this:

```php
use FreshleafMedia\TiptapParser\Nodes\Paragraph;

readonly class LocalisedParagraph extends Paragraph
{
    public function __construct(
        public string $language,
        public array $children = [],
    )
    {
    }

    public function render(): string
    {
        return <<<HTML
            <p lang="{$this->language}">
                {$this->renderInnerHtml()}
            </p>
            HTML;
    }

    public static function fromArray(array $array): self
    {
        return new self(
            $array['attrs']['lang'] ?? 'en',
            $array['children'] ?? [],
        );
    }
}
```


# Text Content

Plain text can be extracted available via the `toText` method. This is useful for things like populating a search index.

```php
use FreshleafMedia\TiptapParser\TiptapContent;

$tiptapArray = [
    'type' => 'paragraph',
    'content' => [
        [
            'type' => 'text',
            'text' => 'Hello world',
            'marks' => [
                ['type' => 'bold'],
            ],
        ],
    ],
];

TiptapContent::fromArray($tiptapArray)->toHtml(); // <p><strong>Hello world</strong></p>
TiptapContent::fromArray($tiptapArray)->toText(); // Hello world
```