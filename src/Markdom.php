<?php

namespace Sinnbeck\Markdom;

use Highlight\Highlighter;
use Wa72\HtmlPageDom\HtmlPageCrawler;
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

    public function __construct(Highlighter $highlighter)
    {
        $this->highlighter = $highlighter;
        $this->highlighter->setAutodetectLanguages(config('markdom.code_highlight.languages'));
        $this->classes = config('markdom.classes', []);
    }

    public function setClasses($classes): self
    {
        $this->classes = $classes;
        return $this;
    }

    public function toHtml($markdown)
    {
        $this->markdown = $markdown;
        $html = app('commonmark')->convertToHtml($this->markdown);

        $dom = HtmlPageCrawler::create($html);

        $this->addCodeHighlights($dom);

        $this->addClasses($dom);

        return trim($dom->saveHTML());
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
}