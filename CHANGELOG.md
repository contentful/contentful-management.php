# Changelog

All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased](https://github.com/contentful/contentful-management.php/compare/2.0.1...HEAD)

<!-- PENDING-CHANGES -->
> No meaningful changes since last release.
<!-- /PENDING-CHANGES -->

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
