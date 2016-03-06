# Overriding Default ASFSchedulerBundle Templates 

As you start to incorporate ASFSchedulerBundle into your application, you will probably find that you need to override the default templates that are provided by the bundle. Although the template names are not configurable, the Symfony framework provides two ways to override the templates of a bundle.
* Define a new template of the same name in the app/Resources directory
* Create a new bundle that is defined as a child of ASFSchedulerBundle

## Overriding The Default layout.html.twig

It is highly recommended that you override the Resources/views/layout.html.twig template so that the pages provided by the ASFSchedulerBundle have a similar look and feel to the rest of your application. An example of overriding this layout template is demonstrated below using both of the overriding options listed above.

Here is the default layout.html.twig provided by the ASFSchedulerBundle :

```django
{% extends "ASFLayoutBundle::frontend_model.html.twig" %}
```

> As you can see, this bundle is linked to ASFLayoutBundle.

### Define New Template In app/Resources

The easiest way to override a bundle's template is to simply place a new one in your app/Resources folder. To override the layout template located at Resources/views/layout.html.twig in the ASFSchedulerBundle directory, you would place your new layout template at app/Resources/ASFSchedulerBundle/views/layout.html.twig.

As you can see the pattern for overriding templates in this way is to create a folder with the name of the bundle class in the app/Resources directory. Then add your new template to this folder, preserving the directory structure from the original bundle.

### Create A Child Bundle And Override Template

> This method is more complicated than the one outlined above. Unless you are planning to override the controllers as well as the templates, it is recommended that you use the other method.

As listed above, you can also create a bundle defined as child of ASFSchedulerBundle and place the new template in the same location that is resides in the ASFSchedulerBundle. The first thing you want to do is override the getParent method to your bundle class.

```php
// src/Acme/SchedulerBundle/AcmeSchedulerBundle.php

namespace Acme\SchedulerBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class AcmeSchedulerBundle extends Bundle
{
    public function getParent()
    {
        return 'ASFSchedulerBundle';
    }
}
```

By returning the name of the bundle in the getParent method of your bundle class, you are telling the Symfony Framework that your bundle is a child of the ASFSchedulerBundle.

Now that you have declared your bundle as a child of the ASFSchedulerBundle, you can override the parent bundle's templates. To override the layout template, simply create a new file in the src/Acme/SchedulerBundle/Resources/views directory named layout.html.twig. Notice how this file resides in the same exact path relative to the bundle directory as it does in the ASFSchedulerBundle.

After overriding a template in your child bundle, you must clear the cache for the override to take effect, even in a development environment.

Overriding all of the other templates provided by the ASFSchedulerBundle can be done in a similar fashion using either of the two methods shown in this document.