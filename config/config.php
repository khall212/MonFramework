<?php
use Framework\Renderer\RendererInterface;
use Framework\Renderer\TwigRendererFactory;

return [
    'database.host' => 'localhost',
    'database.username' => 'root',
    'database.password' => '',
    'database.name' => 'monsupersite',
    'views.path' => dirname(__DIR__). '/views',
    'twig.extensions' => [
        \DI\get(\Framework\Router\RouterTwigExtension::class),
        \DI\get(\Framework\Twig\PagerFantaExtension::class),
        \DI\get(\Framework\Twig\TextExtension::class),
        \DI\get(\Framework\Twig\TimeExtension::class)
    ],
    \Framework\Router::class => \DI\object(),
    RendererInterface::class => \DI\factory(TwigRendererFactory::class),
    \PDO::class => function(\Psr\Container\ContainerInterface $c) {
    return new \PDO(
        'mysql:host='. $c->get('database.host'). ';dbname='. $c->get('database.name'),
        $c->get('database.username'),
        $c->get('database.password'),
        [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]
    );
    }
];