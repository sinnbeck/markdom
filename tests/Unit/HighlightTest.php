<?php

use function Spatie\Snapshots\assertMatchesTextSnapshot;

uses(Tests\TestCase::class);

beforeEach(function () {
    $this->app->config->set('markdom.code_highlight.enabled', true);
});

test('it can highlight php', function () {
    $converter = app('markdom');

    $markdown =
<<<MARKDOWN
This is a code block
```
\$arr = [1, 2, 3];
```
MARKDOWN;

    assertMatchesTextSnapshot($converter->toHtml($markdown));
});

test('it can highlight javascript', function () {
    $converter = app('markdom');

    $markdown =
<<<MARKDOWN
This is a code block
```
var foo = {
    thing: 'bar'
};

```
MARKDOWN;

    assertMatchesTextSnapshot($converter->toHtml($markdown));
});

test('it can highlight inline', function () {
    $converter = app('markdom');

    $markdown =
<<<MARKDOWN
This is inline code `\$this->foo = 'bar'` right here
MARKDOWN;

    assertMatchesTextSnapshot($converter->toHtml($markdown));
});