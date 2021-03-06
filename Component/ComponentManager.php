<?php
namespace Igdr\Bundle\LayoutComponentBundle\Component;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\HttpKernel\Controller\ControllerReference;

/**
 * Class Manager
 */
class ComponentManager implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @var ArrayCollection
     */
    private $places;

    /**
     * @param array $configuration
     */
    public function __construct(array $configuration)
    {
        foreach ($configuration as $place => $components) {
            $this->places[$place] = array();
            foreach ($components['components'] as $name => $component) {
                $this->places[$place][$name] = new Component($component['controller'], $component['routes'], $component['ordering']);
            }
        }
    }

    /**
     * @param string $place
     *
     * @return array
     */
    public function getComponents($place)
    {
        return !empty($this->places[$place]) ? $this->places[$place] : array();
    }

    /**
     * @param string $place
     *
     * @return string
     */
    public function render($place)
    {
        $components = $this->getComponents($place);

        $result = array();
        foreach ($components as $component) {
            /* @var $component Component */
            if ($this->isRendered($component)) {
                $result[] = $this->renderComponent($component);
            }
        }

        return join('', $result);
    }

    /**
     * @param Component $component
     *
     * @return bool
     */
    private function isRendered(Component $component)
    {
        $routes = $component->getRoutes();
        if (empty($routes)) {
            return true;
        }

        $route = $this->container->get('request_stack')->getCurrentRequest()->get('_route');
        foreach ($routes as $regex) {
            if (preg_match('#' . $regex . '#', $route)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param Component $component
     *
     * @return string
     */
    private function renderComponent(Component $component)
    {
        $request = $this->container->get('request_stack')->getMasterRequest();

        $reference = new ControllerReference($component->getController(), $request->attributes->all(), $request->query->all());

        return $this->container->get('fragment.handler')->render($reference);
    }
}
