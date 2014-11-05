Layout component Bundle
========================
Installation
------------

Add the bundle to your `composer.json`:

    "igdr/layout-component-bundle" : "dev-master"

and run:

    php composer.phar update

Then add the LayoutComponentBundle to your application kernel:

    // app/IgdrKernel.php
    public function registerBundles()
    {
        return array(
            // ...
            new Igdr\Bundle\LayoutComponentBundle\IgdrLayoutComponentBundle(),
            // ...
        );
    }


Add layout.yml to Resources/config directory of your bundle

    places:
        left:
            components:
                filter:
                    controller: AppShopBundle:Frontend/Filter:filter
                    routes:
                        - "category_[\d]+"
                navigation:
                    controller: AppShopBundle:Frontend/Product/Navigation:index
                    routes:
                        - "category_products_[\d]+"

And mark places for yours components in layout.html.twig

    {{ igdr_layout_component_place('left') }}