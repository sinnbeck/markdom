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
];