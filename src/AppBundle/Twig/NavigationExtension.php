<?php
declare(strict_types=1);

namespace AppBundle\Twig;

class NavigationExtension extends \Twig_Extension
{

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('activeRoute', array($this, 'activeRouteClass')),
        );
    }

    /**
     * @param $route
     * @param $actual
     * @return null|string
     */
    public function activeRouteClass(string $route, string $actual)
    {
        return $route === $actual ? 'class=active ' : null;
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'twig_navigation_extension';
    }
}