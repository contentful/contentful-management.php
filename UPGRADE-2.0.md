# UPGRADE FROM 1.x to 2.0

## Change of handling for SystemProperties

Previously, all resources shared a common implementation of the system properties object, located in `Contentful\Management\SystemProperties`. The issue with implementation was that in order to accommodate for all possible resources, the class was unnecessarily big and contained many nullable properties.

To fix this and make the handling of system properties more robust, now every resource declares a specific system properties class, which is enforced through strict typing in their corresponding `->getSystemProperties()` methods:

```php
/** @var \Contentful\Management\SystemProperties\Entry $sys */
$sys = $entry->getSystemProperties();

/** @var \Contentful\Management\SystemProperties\Asset $sys */
$sys = $asset->getSystemProperties();

/** @var \Contentful\Management\SystemProperties\ContentType $sys */
$sys = $contentType->getSystemProperties();
```

The system properties objects will work just like before, but pay attention to two things:

* If you were type hinting against general `Contentful\Management\SystemProperties` class, you need to change that to something more specific. If you still need something generic, you can use the base `Contentful\Core\Resource\SystemPropertiesInterface`, but be careful as it only defines the `getId()` and `getType()` methods.
* Methods that conceptually did not belong to a resource's system properties will no longer exist. For instance, you will not find the `getPublishedAt()` method in the `Contentful\Management\SystemProperties\Space` class.

## Change in handling of object hydration

If implementing a custom mapper (for instance using the code generation capabilities) you need to upgrade your `Mapper::map()` methods:

```php
// Before
return $this->hydrate(...);
// After
return $this->hydrator->hydrate(...);
```

The parameters of the two functions are the same, so no change there is necessary.

## Upgrade of contentful-core.php

The SDK now uses version 2 of the `contentful/core` package. We encourage users to check its [changelog](https://github.com/contentful/contentful-core.php/blob/2.0.0/CHANGELOG.md) and [upgrade guide](https://github.com/contentful/contentful-core.php/blob/2.0.0/UPGRADE-2.0.md).
