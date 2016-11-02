Symfony Resource Lock Bundle
==========================

A Symfony bundle that provides resource lock implementation

[![Build Status](https://travis-ci.org/aboutcoders/resource-lock-bundle.svg?branch=master)](https://travis-ci.org/aboutcoders/resource-lock-bundle)

## Installation

Add the AbcResourceLockBundle to your `composer.json` file

```json
{
    "require": {
        "aboutcoders/resource-lock-bundle": "dev-master"
    }
}
```

Include the bundle in the AppKernel.php class

```php
public function registerBundles()
{
    $bundles = array(
        // ...
        new Abc\Bundle\ResourceLockBundle\AbcResourceLockBundle(),
    );

    return $bundles;
}
```

## Configuration

Configure the bundle

``` yaml
# app/config/config.yml
abc_resource_lock:
  db_driver: orm
```

You can define custom managers with a custom prefix within the `managers` section

``` yaml
# app/config/config.yml
abc_resource_lock:
  db_driver: orm
  managers:
    my_manager:
        prefix: my_prefix
    another_manager:
        prefix: another_prefix
```

## Usage

Use Lock manager to get, set or check locks:

``` php
$container->get('abc.resource_lock.lock_manager');
```

To retrieve the custom manager from the service container you have to specify it by its name:

``` php
$container->get('abc.resource_lock.lock_manager_my_manager');
```

## License

The MIT License (MIT). Please see [License File](./LICENSE) for more information.