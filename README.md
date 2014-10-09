Layout component Bundle
========================
Installation
------------

Add the bundle to your `composer.json`:

    "repositories": [
        {
            "type": "git",
            "url": "git@github.com:igdr/LayoutComponentBundle.git"
        }
    ],

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
