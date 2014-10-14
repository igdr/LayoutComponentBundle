<?php
namespace Igdr\Bundle\LayoutComponentBundle\Component;

/**
 * Class Component
 */
class Component
{
    /**
     * @var string
     */
    private $controller;

    /**
     * @var array
     */
    private $routes;

    /**
     * @var int
     */
    private $ordering;

    /**
     * @param string $controller
     * @param array  $routes
     * @param null   $ordering
     */
    public function __construct($controller, $routes = array(), $ordering = null)
    {
        $this->controller = $controller;
        $this->routes     = $routes;
        $this->ordering   = $ordering;
    }

    /**
     * @return string
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @return int
     */
    public function getOrdering()
    {
        return $this->ordering;
    }

    /**
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }
}
