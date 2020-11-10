<?php

return [
    /**
     * Mapping elements to class names
     * For instance
     * 'p' => 'lead',
     * will give all <p> elements the class "lead"
     * <p class="lead">
     */
    'classes' => [
        //
    ],

    'commonmark' => [
        /**
         * Options for CommonMark parser
         * https://commonmark.thephpleague.com/1.5/configuration/#configuration
         */
        'enable_em' => true,
        'enable_strong' => true,
        'use_asterisk' => true,
        'use_underscore' => true,
        'unordered_list_markers' => ['-', '*', '+'],
        'html_input' => 'escape',
        'allow_unsafe_links' => false,
        'max_nesting_level' => INF,

        /**
         * CommonMark extensions to use
         * https://commonmark.thephpleague.com/1.5/extensions/overview/
         */
        'extensions' => [
            //League\CommonMark\Extension\Autolink\AutolinkExtension::class,
            //League\CommonMark\Extension\Strikethrough\StrikethroughExtension::class,

        ]
    ],

    /**
     * It is possible to have code tags automatically
     * parsed and highlighted
     *
     * Remember to add the stylesheet to your page, if using this!
     * @markdomStyles()
     */
    'code_highlight' => [
        'enabled' => env('MARKDOM_CODE_HIGHLIGHT', false),
        'theme' => 'default',
        'languages' => [
            'javascript',
            'php',
            'css',
        ],
    ],

    /**
     * this being enabled adds an (invisible) anchor tag to configured elements
     *   by default this is h1, h2 and h3 to make it easy to target them in a
     *   navigation
     */
    'anchor_tags' => [
        'enabled' => env('MARKDOM_ADD_ANCHORS', false),

        /**
         * here you can define which elements shall be used to add anchor tags
         */
        'elements' => [
            'h1',
            'h2',
            'h3',
        ],

        /**
         * set the delimiter which will be used to create the slugs from your elements content
         * Lets say you have a headline "About Us", using '-' will create a slug like 'about-us'
         */
        'slug_delimiter' => '-',

        /**
         * here you can define where the anchor shall be placed, possible values:
         * - before: the anchor tag will be placed right before the element itself
         *     - Example: <a name="foo"></a><h1>Foo</h1>
         * - after: the anchor tag will be placed right after the element itself
         *     - Example: <h1>Foo</h1><a name="foo"></a>
         * - wrap: the anchor tag will wrap the element
         *     - Example: <a name="foo"><h1>Foo</h1></a>
         * - prepend: the anchor tag will be placed before the content of the element
         *     - Example: <h1><a name="foo"></a>Foo</h1>
         * - append: the anchor tag will be placed after the content of the element
         *     - Example: <h1>Foo<a name="foo"></a></h1>
         */
        'position' => 'before',
    ],
];