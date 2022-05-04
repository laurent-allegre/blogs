<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
            new TwigFilter('taille', [$this, 'getLength']),
        ];
    }

    public function getFunctions(): array
    {
        return [
          //  new TwigFunction('function_name', [$this, 'doSomething']),
        ];
    }

    public function getLength(array  $tableau)
    {
        return "notre tableau contient " . count($tableau) . " Articles";
    }
}
