# Artscore Studio Scheduler Bundle

Scheduler Bundle is a Symfony 2/3 bundle for create and manage calendar events in your Symfony 2/3 application. This package is a part of Artscore Studio Framework.

> IMPORTANT NOTICE: This bundle is still under development. Any changes will be done without prior notice to consumers of this package. Of course this code will become stable at a certain point, but for now, use at your own risk.

> BE CARREFUL : This bundle does not include external libraries, you must install the libraries via Compoer, in accordance with best practices of Symfony documentation.

## Prerequisites

This version of the bundle requires :
* Symfony >= 2.8 / >= 3+

### Translations

If you wish to use default texts provided in this bundle, you have to make sure you have translator enabled in your config.

```yaml
# app/config/config.yml
framework:
    translator: ~
```

For more information about translations, check [Symfony documentation](https://symfony.com/doc/current/book/translation.html).

## Installation

### Step 1 : Download ASFSchedulerBundle using composer

Require the bundle with composer :

```bash
$ composer require artscorestudio/scheduler-bundle "dev-master"
```

Composer will install the bundle to your project's *vendor/artscorestudio/scheduler-bundle* directory. It also install dependencies. 

### Step 2 : Enable the bundle

Enable the bundle in the kernel :

```php
// app/AppKernel.php

public function registerBundles()
{
	$bundles = array(
		// ...
		new ASF\SchedulerBundle\ASFSchedulerBundle()
		// ...
	);
}
```

### Step 3 : Import ASFSchedulerBundle routing files

Now that you have activated and configured the bundle, all that is left to do is import the ASFSchedulerBundle routing files.

By importing the routing files you will have ready made pages for things such as Scheduler homepage, etc.

```yaml
asf_scheduler:
    resource: "@ASFSchedulerBundle/Resources/config/routing/routing.yml"
```

### Next Steps

Now you have completed the basic installation and configuration of the ASFSchedulerBundle, you are ready to learn about more advanced features and usages of the bundle.

The following documents are available :
* [Overriding Default ASFSchedulerBundle Templates](templates.md)
* [Overriding Default ASFSchedulerBundle Controllers](controllers.md)