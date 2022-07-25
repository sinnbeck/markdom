<?php

namespace Sinnbeck\Markdom;

use Highlight\Highlighter;
use Illuminate\Support\Str;
use League\CommonMark\MarkdownConverter;
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
     * @var \League\CommonMark\MarkdownConverter
     */
    protected $converter;

    public function __construct(Highlighter $highlighter, MarkdownConverter $converter)
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

        $this->addAnchorTags($dom);

        $this->addClasses($dom);

        return trim($dom->html());
    }

    protected function addAnchorTags($dom)
    {
        if (!config('markdom.links.enabled')) {
            return;
        }

        $method = config('markdom.links.position', 'before');

        collect(config('markdom.links.elements'))
            ->each(function ($tag) use ($dom, $method) {
                $dom->filter($tag)->each(function($element) use ($method){
                    $slug = Str::slug(
                        $element->html(),
                        config('markdom.links.slug_delimiter')
                    );

                    $element->setAttribute('id', $slug);

                    if (config('markdom.links.add_anchor')) {
                        throw_if(!method_exists($element, $method),
                            MethodNotAllowedException::class
                        );

                        $element->$method($this->makeAnchorTag($slug));

                    }

                });
            });

    }

    protected function addClasses($dom)
    {
        foreach ($this->classes as $element => $class) {
            $dom->filter($element)->addClass($class);
        }
    }

    protected function makeAnchorTag($slug)
    {
        return '<a href="#' . $slug . '" />';
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

}