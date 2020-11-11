<?php

namespace Sinnbeck\Markdom;

use Highlight\Highlighter;
use Illuminate\Support\Str;
use Sinnbeck\Markdom\Exceptions\MethodNotAllowedException;
use Wa72\HtmlPageDom\HtmlPageCrawler;
use League\CommonMark\CommonMarkConverter;
use function HighlightUtilities\getStyleSheet;
use function HighlightUtilities\getAvailableStyleSheets;

class Markdom
{

    /**
     * @var string
     */
    protected $markdown;
    /**
     * @var \Highlight\Highlighter
     */
    protected $highlighter;
    /**
     * @var \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed
     */
    protected $classes;
    /**
     * @var \League\CommonMark\CommonMarkConverter
     */
    protected $converter;

    public function __construct(Highlighter $highlighter, CommonMarkConverter $converter)
    {
        $this->highlighter = $highlighter;
        $this->converter = $converter;
    }

    public function setClasses($classes): self
    {
        $this->classes = $classes;
        return $this;
    }

    public function toHtml($markdown)
    {
        $this->markdown = $markdown;
        $html = $this->converter->convertToHtml($this->markdown);

        $dom = HtmlPageCrawler::create('<div>' . $html . '</div>');

        $this->addCodeHighlights($dom);

        $this->addClasses($dom);

        $this->addAnchorTags($dom);

        return trim($dom->first()->html());
    }

    protected function addClasses($dom)
    {
        foreach ($this->classes as $element => $class) {
            $dom->filter($element)->addClass($class);
        }
    }

    protected function addCodeHighlights($dom)
    {
        if (config('markdom.code_highlight.enabled')) {
            $dom->filter('code')->each(function ($element) {
                $html = htmlspecialchars_decode($element->html());
                $highlightResult = $this->highlightHtml($html);
                $value = $highlightResult->value;
                $element->setInnerHtml($value);
                $element->addClass('hljs ' . $highlightResult->language);
            });
        }
    }

    protected function highlightHtml($html)
    {
        return $this->highlighter->highlightAuto($html);
    }

    public function getHighlighter()
    {
        return $this->highlighter;
    }

    public function getStyles($theme = null)
    {
        if (!$theme) {
            $theme = config('markdom.code_highlight.theme', 'default');
        }
        return getStyleSheet($theme) . $this->fixInlineCode();
    }

    protected function fixInlineCode(): string
    {
        return
<<<CSS

/* Fix for inline code */
p > code.hljs { display: inline; }
CSS;

    }

    public function getAvailableThemes()
    {
        return getAvailableStyleSheets();
    }

    protected function addAnchorTags($dom)
    {
        if (!config('markdom.anchor_tags.enabled')) {
            return;
        }

        $method = config('markdom.anchor_tags.position', 'before');

        collect(config('markdom.anchor_tags.elements'))
            ->each(function ($tag) use ($dom, $method) {
                $dom->filter($tag)->each(function($element) use ($method){
                    throw_if(!method_exists($element, $method),
                        MethodNotAllowedException::class
                    );

                    $element->$method('<a name="' . Str::slug($element->html(),
                        config('markdom.anchor_tags.slug_delimiter')) . '"/>'
                    );
                });
            });
    }
}