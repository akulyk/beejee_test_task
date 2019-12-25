<?php


use app\template\TemplateRenderer;
use app\template\twig\TwigRenderer;
use Illuminate\Container\Container;
use Illuminate\Validation\DatabasePresenceVerifier;
use JeffOchoa\ValidatorFactory;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$app = app();

$app->bind(ValidatorFactory::class, function (Container $container) {
    $factory = new ValidatorFactory();
    $verifier = $container->make(DatabasePresenceVerifier::class);
    $factory->factory->setPresenceVerifier($verifier);

    return $factory;
});

$app->bind(DatabasePresenceVerifier::class, function (Container $container) {
    $databaseManager = $container->make('db')->getDatabaseManager();
    return new DatabasePresenceVerifier($databaseManager);
});

$app->bind(TemplateRenderer::class, function (Container $container) {
    $loader = new FilesystemLoader();
    $loader->addPath(realpath(__DIR__ . '/../templates'));
    $twig = new Environment($loader, [
        'debug' => true,
        'cache' => realpath(__DIR__ . '/../var/cache/twig'),
    ]);
    return new TwigRenderer($twig, '.html.twig');
});

return $app;
