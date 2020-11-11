<?php

namespace Sinnbeck\Markdom;

use Highlight\Highlighter;
use Illuminate\Support\Arr;
use League\CommonMark\Environment;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use League\CommonMark\CommonMarkConverter;
use Illuminate\Contracts\Container\Container;

class MarkdomServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/markdom.php' => config_path('markdom.php'),
        ], 'markdom-config');

        $this->registerDirectives();

    }

    private function registerDirectives(): void
    {
        Blade::directive('markdom', function ($markdown) {
            return "<?php echo app('markdom')->toHtml($markdown); ?>";
        });

        Blade::directive('markdomStyles', function ($theme = null) {
            return "<style>\n<?php echo app('markdom')->getStyles($theme); ?>\n</style>";
        });
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/config/markdom.php', 'markdom'
        );

        $this->registerBindings();
    }

    private function registerBindings()
    {
        $this->app->bind(Markdom::class , function ($app) {
            $highlighter = new Highlighter();
            $highlighter->setAutodetectLanguages($app->config->get('markdom.code_highlight.languages'));

            $markdom = new Markdom($highlighter, $app->get('commonmark'));
            $markdom->setClasses($app->config->get('markdom.classes', []));

            return $markdom;
        });

        $this->app->alias(Markdom::class, 'markdom');

        $this->app->singleton('commonmark.environment', function ($app) {
            $environment = Environment::createCommonMarkEnvironment();

            collect($app->config->get('markdom.commonmark.extensions', []))
                ->map(function($extension) use ($environment) {
                    $environment->addExtension(new $extension());
                });

            return $environment;
        });

        $this->app->singleton('commonmark', function ($app) {
            $environment = $app->get('commonmark.environment');
            $config = $app->config->get('markdom.commonmark');

            return new CommonMarkConverter(Arr::except($config, ['render_engine', 'extensions']), $environment);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return string[]
     */
    public function provides()
    {
        return [
            'markdom',
            'commonmark.environment',
            'commonmark'
        ];
    }
}
