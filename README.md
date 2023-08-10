# contentful-management.php

[![Packagist](https://img.shields.io/packagist/v/contentful/contentful-management.svg?style=for-the-badge)](https://packagist.org/packages/contentful/contentful-management)
[![PHP version](https://img.shields.io/packagist/php-v/contentful/contentful-management.svg?style=for-the-badge)](https://packagist.org/packages/contentful/contentful-management)
[![Packagist](https://img.shields.io/github/license/contentful/contentful-management.php.svg?style=for-the-badge)](https://packagist.org/packages/contentful/contentful-management.php)
[![CircleCI](https://circleci.com/gh/contentful/contentful-management.php.svg?style=shield)](https://circleci.com/gh/contentful/contentful-management.php)

> PHP SDK for [Contentful's](https://www.contentful.com) Content Management API. The SDK requires at least PHP 7.2 or PHP 8.0 and up.

## Setup

Add this package to your application by using [Composer](https://getcomposer.org/) and executing the following command:

```bash
composer require contentful/contentful-management
```

Then, if you haven't already, include the Composer autoloader:

```php
require_once 'vendor/autoload.php';
```

## Basic concepts

The first thing that needs to be done is initiating an instance of `Contentful\Management\Client` by giving it an access token. All actions performed using this instance of the `Client` will be performed with the privileges of the user this token belongs to.

```php
$client = new \Contentful\Management\Client('access-token');
```

When working with space-scoped or environment-scoped resources, you can use proxies. They are lazy-references to a space or an environment, and they allow you to avoid repeating the space and environment ID when making API calls:

```php
// Without space proxy
$deliveryApiKeys = $client->getDeliveryApiKeys($spaceId);
$roles = $client->getRoles($spaceId);
// With space proxy
$spaceProxy = $client->getSpaceProxy($spaceId);
$deliveryApiKeys = $spaceProxy->getDeliveryApiKeys();
$roles = $spaceProxy->getRoles();

// Without environment proxy
$assets = $client->getAssets($spaceId, $environmentId);
$entries = $client->getEntries($spaceId, $environmentId);
// With environment proxy
$environmentProxy = $client->getEnvironmentProxy($spaceId, $environmentId);
$assets = $environmentProxy->getAssets();
$entries = $environmentProxy->getEntries();
```

## Usage

- [contentful-management.php](#contentful-managementphp)
  - [Setup](#setup)
  - [Basic concepts](#basic-concepts)
  - [Usage](#usage)
    - [Api Keys](#api-keys)
    - [Assets](#assets)
    - [Content types and content type snapshots](#content-types-and-content-type-snapshots)
    - [Editor interfaces](#editor-interfaces)
    - [Entries and entry snapshots](#entries-and-entry-snapshots)
    - [Environments](#environments)
    - [Locales](#locales)
    - [Organizations](#organizations)
    - [Personal access tokens](#personal-access-tokens)
    - [Roles](#roles)
    - [Spaces](#spaces)
    - [Space memberships](#space-memberships)
    - [Uploads](#uploads)
    - [UI extensions](#ui-extensions)
    - [Users](#users)
    - [Webhooks](#webhooks)
    - [Rate limits and retrying](#rate-limits-and-retrying)
  - [Contributing](#contributing)
  - [About Contentful](#about-contentful)
  - [License](#license)

### Api Keys

Fetching:

```php
$deliveryApiKeys = $spaceProxy->getDeliveryApiKeys();
$deliveryApiKey = $spaceProxy->getDeliveryApiKey($deliveryApiKeyId);

echo $deliveryApiKey->getSystemProperties()->getId();
echo $deliveryApiKey->getName();
echo $deliveryApiKey->getAccessToken();
$previewApiKeyLink = $deliveryApiKey->getPreviewApiKey();

$previewApiKey = $spaceProxy->resolveLink($previewApiKeyLink);
echo $previewApiKey->getAccessToken();
```

Creating and modifying:

```php
$deliveryApiKey = new \Contentful\Management\Resource\DeliveryApiKey('Mobile');

$spaceProxy->create($deliveryApiKey);
echo $deliveryApiKey->getSystemProperties()->getId();
echo $deliveryApiKey->getAccessToken();

$deliveryApiKey->delete();
```

### Assets

Fetching:

```php
$assets = $environmentProxy->getAssets();
// Optionally, pass a query object
$query = (new \Contentful\Management\Query())
    ->setLimit(5);
$assets = $environmentProxy->getAssets($query);

$asset = $environmentProxy->getAsset($assetId);

echo $asset->getSystemProperties()->getId();
echo $asset->getTitle('en-US');
```

Creating and modifying:

```php
$asset = new \Contentful\Management\Resource\Asset();
$file = new \Contentful\Core\File\RemoteUploadFile('Contentful.svg', 'image/svg+xml', $url);
$asset->setTitle('en-US', 'My asset')
    ->setDescription('en-US', 'My description')
    ->setFile('en-US', $file);

$environmentProxy->create($asset);

// Omit the locale to process the files for all locales
$asset->process('en-US');

$asset->setDescription('en-US', 'An even better description');
$asset->update();

$asset->archive();
$asset->unarchive();

$asset->publish();
$asset->unpublish();

$asset->delete();
```

### Content types and content type snapshots

Fetching:

```php
$contentTypes = $environmentProxy->getContentTypes();
// Optionally, pass a query object
$query = (new \Contentful\Management\Query())
    ->setLimit(5);
$contentTypes = $environmentProxy->getContentTypes($query);

$contentType = $environmentProxy->getContentType($contentTypeId);

echo $contentType->getSystemProperties()->getId();
echo $contentType->getName();

// Fetch the published version of content types
$contentTypes = $environmentProxy->getPublishedContentTypes($query);
$contentType = $environmentProxy->getPublishedContentType($contentTypeId);

// Fetch snapshots from a content type, or from an environment proxy
$snapshots = $contentType->getSnapshots();
$snapshot = $contentTy->getSnapshot($snapshotId);

$snapshots = $environmentProxy->getContentTypeSnapshots($contentTypeId);
$snapshot = $environmentProxy->getContentTypeSnapshot($contentTypeId, $snapshotId);
```

Creating and modifying:

```php
$contentType = new \Contentful\Management\Resource\ContentType('Blog Post');
$contentType->setDescription('My description');
$contentType->addNewField('Symbol', 'title', 'Title');
$contentType->addNewField('Text', 'body', 'Body');

$customContentTypeId = 'blogPost';
$environmentProxy->create($contentType, $customContentTypeId);

$contentType->addNewField('Date', 'publishedAt', 'Published At');
$contentType->update();

$contentType->publish();
$contentType->unpublish();

$contentType->delete();
```

### Editor interfaces

Fetching and updating

```php
$editorInterface = $environmentProxy->getEditorInterface($contentTypeId);

$control = $editorInterface->getControl('website');
$control->setWidgetId('urlEditor');

$editorInterface->update();
```

### Entries and entry snapshots

Fetching:

```php
$entries = $environmentProxy->getEntries();
// Optionally, pass a query object
$query = (new \Contentful\Management\Query())
    ->setLimit(5);
$entries = $environmentProxy->getEntries($query);

$entry = $environmentProxy->getEntry($entryId);

echo $entry->getSystemProperties()->getId();
echo $entry->getField('title', 'en-US');

// Fetch snapshots from an entry, or from an environment proxy
$snapshots = $entry->getSnapshots();
$snapshot = $entry->getSnapshot($snapshotId);

$snapshots = $environmentProxy->getEntrySnapshots($contentTypeId);
$snapshot = $environmentProxy->getEntrySnapshot($entryId, $snapshotId);
```

Creating and modifying:

```php
$entry = new \Contentful\Management\Resource\Entry($contentTypeId);
$entry->setField('title', 'en-US', 'My awesome blog post');
$entry->setField('body', 'en-US', 'Something something...');

//Add existing assets
$images = [
    new \Contentful\Core\Api\Link('Example-existing-asset-id', 'Asset'),
    new \Contentful\Core\Api\Link('Example-existing-asset-id-2', 'Asset'),
];
$entry->setField('productImages', 'en-US', $images);

$environmentProxy->create($entry);

$entry->setField('body', 'en-US', 'Updated body');
$entry->update();

$entry->archive();
$entry->unarchive();

$entry->publish();
$entry->unpublish();

$entry->delete();
```

### Environments

Fetching:

```php
$environments = $spaceProxy->getEnvironments();
// Optionally, pass a query object
$query = (new \Contentful\Management\Query())
    ->setLimit(5);
$environments = $spaceProxy->getEnvironments($query);

$environment = $spaceProxy->getEnvironment($environmentId);

echo $environment->getSystemProperties()->getId();
echo $environment->getName();
```

Creating and modifying:

```php
$environment = new \Contentful\Management\Resource\Environment('QA');
$spaceProxy->create($environment);

$environmentId = $environment->getSystemProperties()->getId();

// An environment might take a while to create,
// depending on the size of the master environment,
// so it might be a good idea to poll it until it's ready.
do {
    $environment = $spaceProxy->getEnvironment($environmentId);
    $status = $environment->getSystemProperties()->getStatus()->getId();
} while ($status !== 'ready');

$environment->delete();
```

Creating an environment with a different source:

```php
$environment = new \Contentful\Management\Resource\Environment('QA','source-env-id');
$spaceProxy->create($environment);

// An environment might take a while to create,
// depending on the size of the master environment,
// so it might be a good idea to poll it until it's ready.
do {
    $environment = $spaceProxy->getEnvironment($environmentId);
    $status = $environment->getSystemProperties()->getStatus()->getId();
} while ($status !== 'ready');

$environment->delete();
```

### Locales

Fetching:

```php
$locales = $environmentProxy->getLocales();
// Optionally, pass a query object
$query = (new \Contentful\Management\Query())
    ->setLimit(5);
$locales = $environmentProxy->getLocales($query);

$locale = $environmentProxy->getLocale($localeId);

echo $locale->getSystemProperties()->getId();
echo $locale->getName();
echo $locale->getCode();
```

Creating and modifying:

```php
$locale = new \Contentful\Management\Resource\Locale('English (United States)', 'en-US');
$environmentProxy->create($locale);

$locale->delete();
```

### Organizations

Fetching:

```php
$organizations = $client->getOrganizations();
$organization = $organizations[0];

echo $organization->getSystemProperties()->getId();
echo $organization->getName();
```

### Personal access tokens

Fetching:

```php
$personalAccessTokens = $client->getPersonalAccessTokens();
// Optionally, pass a query object
$personalAccessTokens = (new \Contentful\Management\Query())
    ->setLimit(5);
$personalAccessTokens = $client->getPersonalAccessTokens($query);

$personalAccessToken = $client->getPersonalAccessToken($personalAccessTokenId);

echo $personalAccessToken->getSystemProperties()->getId();
echo $personalAccessToken->getName();
```

Creating and modifying:

```php
$readOnly = false;
$personalAccessToken = new \Contentful\Management\Resource\PersonalAccessToken('Development access token', $readOnly);
$client->create($personalAccessToken);

// For security reasons, the actual token will only be available after creation.
echo $personalAccessToken->getToken();

$personalAccessToken->revoke();
```

### Roles

Fetching:

```php
$roles = $spaceProxy->getRoles();
// Optionally, pass a query object
$query = (new \Contentful\Management\Query())
    ->setLimit(5);
$roles = $spaceProxy->getRoles($query);

$role = $spaceProxy->getRole($roleId);

echo $role->getSystemProperties()->getId();
echo $role->getName();
```

Creating and modifying:

```php
$role = new \Contentful\Management\Resource\Role('Publisher');

$policy = new \Contentful\Management\Resource\Policy('allow', 'publish');
$role->addPolicy($policy);

$constraint = new \Contentful\Management\Resource\Role\Constraint\AndConstraint([
    new \Contentful\Management\Resource\Role\Constraint\EqualityConstraint('sys.type', 'Entry'),
    new \Contentful\Management\Resource\Role\Constraint\EqualityConstraint('sys.type', 'Asset'),
]);
$policy->setConstraint($constraint);

$spaceProxy->create($role);

$policy->delete();
```

### Spaces

Fetching:

```php
$spaces = $client->getSpaces();
// Optionally, pass a query object
$query = (new \Contentful\Management\Query())
    ->setLimit(5);
$spaces = $client->getSpaces($query);

$space = $client->getSpace($spaceId);

echo $space->getSystemProperties()->getId();
echo $space->getName();
```

Creating and modifying:

```php
$space = new \Contentful\Management\Resource\Space('Website', $organizationId, $defaultLocaleCode);
$client->create($space);

$space->delete();
```

### Space memberships

Fetching:

```php
$spaceMemberships = $spaceProxy->getSpaceMemberships();
// Optionally, pass a query object
$query = (new \Contentful\Management\Query())
    ->setLimit(5);
$spaceMemberships = $spaceProxy->getSpaceMemberships($query);

$spaceMembership = $spaceProxy->getSpaceMembership($spaceMembershipId);

echo $spaceMembership->getSystemProperties()->getId();
echo $spaceMembership->getUser()->getId();
```

Creating and modifying:

```php
$spaceMembership = new \Contentful\Management\Resource\SpaceMembership();
$spaceMembership->setEmail($userEmail)
    ->setAdmin(false)
    ->addRoleLink($roleId);
$spaceProxy->create($spaceMembership);

$spaceMembership->delete();
```

### Uploads

Fetching:

```php
$upload = $spaceProxy->getUpload($uploadId);

echo $upload->getSystemProperties()->getId();
```

Creating and modifying:

```php
// You can pass as argument an fopen resource, an actual string, or a PSR-7 compatible stream
$upload = new \Contentful\Management\Resource\Upload(\fopen($myFile, 'r'));
$spaceProxy->create($upload);

$asset = new \Contentful\Management\Resource\Asset();
// To use the upload as an asset, you need to supply an asset name and a mime type
$asset->setFile('en-US', $upload->asAssetFile('my-asset-name.png', 'image/png'));

$environmentProxy->create($asset);

$asset->process();

$upload->delete();
```

### UI extensions

Fetching:

```php
$extensions = $environmentProxy->getExtensions();
// Optionally, pass a query object
$query = (new \Contentful\Management\Query())
    ->setLimit(5);
$extensions = $environmentProxy->getExtensions($query);

$extension = $environmentProxy->getExtension($extensionId);

echo $extension->getSystemProperties()->getId();
echo $extension->getName();
```

Creating and modifying:

```php
$extension = new \Contentful\Management\Resource\Extension('My awesome extension');
$extension->setSource('https://www.example.com/extension-source')
    ->addNewFieldType('Symbol');

$environmentProxy->create($extension);

$extension->addNewFieldType('Link', ['Entry']);
$extension->update();

$extension->delete();
```

### Users

Fetching:

```php
$user = $client->getUserMe();

echo $user->getSystemProperties()->getId();
echo $user->getEmail();
```

### Webhooks

Fetching:

```php
$webhooks = $spaceProxy->getWebhooks();
// Optionally, pass a query object
$query = (new \Contentful\Management\Query())
    ->setLimit(5);
$webhooks = $spaceProxy->getWebhooks($query);

$webhook = $spaceProxy->getWebhook($webhookId);

echo $webhook->getSystemProperties()->getId();
echo $webhook->getName();

// You can get calls and health from a webhook or from a space proxy
$calls = $webhook->getCalls();
$call = $webhook->getCall($callId);
$health = $webhook->getHealth();

$calls = $spaceProxy->getWebhookCalls($webhookId);
$call = $spaceProxy->getWebhookCall($webhookId, $callId);
$health = $spaceProxy->getWebhookHealth($webhookId);

echo $call->getStatusCode();
echo $call->getUrl();
echo $call->getEventType();

echo $health->getTotal();
echo $health->getHealthy();
```

Creating and modifying:

```php
$webhook = new \Contentful\Management\Resource\Webhook('Publish webhook', $url, ['Entry.publish']);
$spaceProxy->create($webhook);

$webhook->addTopic('Asset.publish');
$webhook->update();

$webhook->delete();
```

### Rate limits and retrying

Some API calls are subject to rate limiting as described [here](https://www.contentful.com/developers/docs/technical-limits/). The SDK can be instructed to retry a call for a number of times via the max_rate_limit_retries option:

```php
$client = new \Contentful\Management\Client('KEY',['max_rate_limit_retries' => 2]);
$proxy = $client->getSpaceProxy('SPACE_ID');
$envName = uniqid();
$env = new \Contentful\Management\Resource\Environment($envName);
$proxy->create($env); //this call will retry two times (so three calls couting the original one), before throwing an exception
```

If the retry should happen in more than 60 seconds (as defined by the X-Contentful-RateLimit-Second-Remaining header [here](https://www.contentful.com/developers/docs/references/content-management-api/#/introduction/api-rate-limits) ), the call will throw a RateWaitTooLongException exception. This was implemented so that your scripts do not run for too long.

## Contributing

PRs are welcome! If you want to develop locally, however, you will need to install with `--ignore-platform-reqs`, as one of the libraries used for testing does currently not officially support PHP8.

## About Contentful

[Contentful](https://www.contentful.com) is a content management platform for web applications, mobile apps and connected devices. It allows you to create, edit & manage content in the cloud and publish it anywhere via powerful API. Contentful offers tools for managing editorial teams and enabling cooperation between organizations.

## License

Copyright (c) 2015-2019 Contentful GmbH. Code released under the MIT license. See [LICENSE](LICENSE) for further details.
