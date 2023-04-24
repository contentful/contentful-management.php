# Changelog

All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased](https://github.com/contentful/contentful-management.php/compare/4.0.0...HEAD)

<!-- PENDING-CHANGES -->
> No meaningful changes since last release.
<!-- /PENDING-CHANGES -->

## [4.1.0](https://github.com/contentful/contentful-management.php/tree/4.1.0) (2023-04-24)

* Added support for `contentful/core` v4.
* Fixed documentation for `Upload::asAssetFile()`
* Fixed code generation
* Minor CI and code smell cleanup


## [4.0.0](https://github.com/contentful/contentful-management.php/tree/4.0.0) (2022-01-21)

### Added

* Support for Symfony Version 6
* **Breaking change** The range validator now uses floats instead of integers to be in line with the API. See [#165](https://github.com/contentful/contentful-management.php/issues/165) for details. Thanks @emsyzz !

### Removed

* **Breaking change** Support for PHP 7.2 and 7.3 was dropped, since they are now end-of-life. You can continue to use version 3.2.0 or install it with `--ignore-platform-reqs`, but compatibility will no longer be checked explicitely. Version 3 will still be maintained in case of security issues for the forseeable future.

### Internal

* Minor fixes to CI

## [3.2.0](https://github.com/contentful/contentful-management.php/tree/3.2.0) (2021-03-25)

### Added

* Added support for PHP8

### Internal

* Small code clean-ups
* Fixed CI jobs

## [3.1.4](https://github.com/contentful/contentful-management.php/tree/3.1.4) (2020-07-29)

### Added

* Upgraded code to allow passing the latest PHPCS validation

## [3.1.3](https://github.com/contentful/contentful-management.php/tree/3.1.3) (2020-07-29)

### Added

* Prohibit regexp validations

## [3.1.2](https://github.com/contentful/contentful-management.php/tree/3.1.2) (2020-05-12)

### Added

* documentation on how to add existing assets
* add documentation step in the internal build process
* new PHP CS validations

## [3.1.0](https://github.com/contentful/contentful-management.php/tree/3.1.0) (2020-03-24)

### Added

* stubs for new validators
* allow genertaing richtext fields

## [3.0.1](https://github.com/contentful/contentful-management.php/tree/3.0.1) (2020-03-13)

### Changed

> Upgraded symfony/console component

## [3.0.0](https://github.com/contentful/contentful-management.php/tree/3.0.0) (2020-03-13)

### Changed

> Added support for PHP 7.4. Removed support for PHP 7.0 & 7.1. Updated dependencies.

## [2.2.1](https://github.com/contentful/contentful-management.php/tree/2.2.1) (2020-02-12)

### Added

> Stub for NodesValidation to fix edge case when getting rich content types with validators threw errors

## [2.2.0](https://github.com/contentful/contentful-management.php/tree/2.2.0) (2020-01-27)

### Added

> Feature to allow retrying throttled API calls

### Fixed

> Refactored code for branching environments

## [2.1.0](https://github.com/contentful/contentful-management.php/tree/2.1.0) (2020-01-16)

### Added

* Support for Environment Cloning

## [2.0.1](https://github.com/contentful/contentful-management.php/tree/2.0.1) (2019-02-28)

### Fixed

* Entries with rich text fields could serialize to JSON in an incorrect manner, causing an exception. This should now be fixed.

## [2.0.0](https://github.com/contentful/contentful-management.php/tree/2.0.0) (2019-01-25)

**ATTENTION**: This release contains breaking changes. Please take extra care when updating to this version. See [the upgrade guide](UPGRADE-2.0.md) for more.

### Added

* `DeliveryApiKey` and `PreviewApiKey` now expose the `environments` property.
* Added support for role constraint `PathsConstraint`.
* Extensions can now be configured with installation or instance parameters, using the `Contentful\Management\Resource\Extension\Parameter` class.
* Webhooks can now define filters, see classes defined in the `Contentful\Management\Resource\Webhook` namespace for more.
* Webhooks now accept a transformations array, see the [documentation](https://www.contentful.com/developers/docs/references/content-management-api/#/reference/webhooks) for more.
* Field type `Contentful\Management\Resource\ContentType\Field\RichText` was added.

### Changed

* Link resolution is now delegated to the `Contentful\Management\LinkResolver` class.
* The SDK now uses version 2 of the `contentful/core` package. **[BREAKING]**
* System properties are no longer handled by a single class for all resources. Now each resource will have its own corresponding class. For instance, `Contentful\Management\Resource\ContentType::getSystemProperties()` will return an object of class `Contentful\Management\SystemProperties\ContentType` which will contain only appropriate methods. **[BREAKING]**

### Removed

* `BaseMapper::hydrate()` has been removed. Use `$this->hydrator->hydrate()` instead. **[BREAKING]**

## [1.0.0](https://github.com/contentful/contentful-management.php/tree/1.0.0) (2018-04-18)

No significant changes compared to previous release.

## [1.0.0-beta1](https://github.com/contentful/contentful-management.php/tree/1.0.0-beta1) (2018-03-26)

### Changed

* The way that the client interacts with the API was overhauled, and the proxy system was replaced by client extension traits. Please refer to the documentation for how to interact with the SDK. **[BREAKING]**

### Added

* The SDK now fully supports handling of space environments.

## [0.9.0-beta1](https://github.com/contentful/contentful-management.php/tree/0.9.0-beta2) (2017-09-12)

### Added

* The SDK now provides code generation capabilities

## [0.9.0-beta1](https://github.com/contentful/contentful-management.php/tree/0.9.0-beta1) (2017-09-12)

Initial release
