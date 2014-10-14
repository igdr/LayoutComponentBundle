<?php
namespace Igdr\Bundle\LayoutComponentBundle\Twig;

use Igdr\Bundle\LayoutComponentBundle\Component\ComponentManager;

/**
 * Twig component extension
 */
class ComponentExtension extends \Twig_Extension
{
    /**
     * @var ComponentManager
     */
    private $componentManager;

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('igdr_layout_component_place', array($this, 'render'), array(
                'is_safe' => array('html')
            ))
        );
    }

    /**
     * @param string $name
     *
     * @return string
     */
    public function render($name)
    {
        return $this->componentManager->render($name);
    }

    /**
     * @param \Igdr\Bundle\LayoutComponentBundle\Component\Manager $componentManager
     *
     * @return $this
     */
    public function setComponentManager($componentManager)
    {
        $this->componentManager = $componentManager;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'igdr_layout_component_component_extension';
    }
}