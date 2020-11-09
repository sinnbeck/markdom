<?php

use function Spatie\Snapshots\assertMatchesTextSnapshot;

uses(Tests\TestCase::class);

test('it adds an anchor tag if enabled', function () {
    $this->app->config->set('markdom.anchor_tags.enabled', true);
    $this->app->config->set('markdom.anchor_tags.elements', [
        'h1',
        'h2',
    ]);

    $converter = app('markdom');

    $markdown =
<<<MARKDOWN
# Hello World!
## Lorem ipsum
Lorem ipsum
### Foo bar!
* Foo
* Bar
#### Bar baz
MARKDOWN;

    assertMatchesTextSnapshot($converter->toHtml($markdown));
});
