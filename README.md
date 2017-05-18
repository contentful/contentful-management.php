contentful-management.php
===============

PHP SDK for [Contentful's][1] Content Management API.

[Contentful][1] is a content management platform for web applications, mobile apps and connected devices. It allows you to create, edit & manage content in the cloud and publish it anywhere via powerful API. Contentful offers tools for managing editorial teams and enabling cooperation between organizations.

The SDK requires at least PHP 7.0.

The SDK is currently in beta. The API might change at any time.

Setup
=====

To add this package to your `composer.json` and install it execute the following command:

```bash
php composer.phar require contentful/contentful-management:@dev
````

Then, if not already done, include the Composer autoloader:

```php
require_once 'vendor/autoload.php';
```

License
=======

Copyright (c) 2015-2017 Contentful GmbH. Code released under the MIT license. See [LICENSE][2] for further details.

 [1]: https://www.contentful.com
 [2]: LICENSE
