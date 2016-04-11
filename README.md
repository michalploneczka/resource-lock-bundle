Symfony Resource Lock Bundle
==========================

A Symfony bundle that provides resource lock implementation

[![Build Status](https://travis-ci.org/aboutcoders/resource-lock-bundle.svg?branch=master)](https://travis-ci.org/aboutcoders/resource-lock-bundle)

## Configuration

Add the bundle:

``` json
{
    "require": {
        "aboutcoders/resource-lock-bundle": "dev-master"
    }
}
```

Enable the bundles in the kernel:

``` php
# app/AppKernel.php
public function registerBundles()
{
    $bundles = array(
        // ...
        new Abc\Bundle\ResourceLockBundle\AbcResourceLockBundle(),
        // ...
    );
}
```

Configure the bundle

``` yaml
# app/config/config.yml
abc_resource_lock:
  db_driver: orm
```

If you like to define your manager with custom prefix you can use `managers` section

``` yaml
# app/config/config.yml
abc_resource_lock:
  db_driver: orm
  managers:
    my_manager:
        prefix: my_prefix
    another_manager
        prefix: another_prefix
```

## Usage

Use Lock manager to get, set or check locks

``` php
$container->get('abc.resource_lock.lock_manager');
```

To access custom manager you need to use its name as following

``` php
$container->get('abc.resource_lock.lock_manager_my_manager');
```