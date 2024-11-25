<?php declare(strict_types=1);

namespace Framework312\Template;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class TwigRenderer implements Renderer {
    private Environment $twig;

    public function __construct(string $templatePath) {
        $loader = new FilesystemLoader($templatePath);
        $this->twig = new Environment($loader);
    }

    public function render(mixed $data, string $template): string {
        return $this->twig->render($template, ['data' => $data]);
    }

    public function register(string $tag) {
        // À implémenter si nécessaire pour les tags personnalisés
    }
}