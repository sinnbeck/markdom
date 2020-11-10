<?php

use Gajus\Dindent\Indenter;
use function Spatie\Snapshots\assertMatchesTextSnapshot;

uses(Tests\TestCase::class);

test('it allows html', function () {
    app()->config->set('markdom.commonmark.html_input', 'allow');
    $converter = app('markdom');
    $markdown =
<<<MARKDOWN
<h1>Headline!</h1>
MARKDOWN;

    assertMatchesTextSnapshot($converter->toHtml($markdown));
});

test('it removes html', function () {
    app()->config->set('markdom.commonmark.html_input', 'strip');
    $converter = app('markdom');
    $markdown =
<<<MARKDOWN
<h1>Headline!</h1>

* Foo <b>bold text</b>
* Bar
MARKDOWN;

    $html = $converter->toHtml($markdown);
    $indent = new Indenter();
    assertMatchesTextSnapshot( $indent->indent($html));
});

test('it escapes html', function () {

    app()->config->set('markdom.commonmark.html_input', 'escape');
    $converter = app('markdom');
    $markdown =
        <<<MARKDOWN
<h1>Headline!</h1>

* Foo <b>bold text</b>
* Bar
MARKDOWN;

    $html = $converter->toHtml($markdown);
    $indent = new Indenter();
    assertMatchesTextSnapshot( $indent->indent($html));
});

test('it can add strong text', function () {

    app()->config->set('markdom.render_options.html_input', 'escape');
    $converter = app('markdom');
    $markdown =
        <<<MARKDOWN
Some __strong__ text
MARKDOWN;

    assertMatchesTextSnapshot($converter->toHtml($markdown));
});