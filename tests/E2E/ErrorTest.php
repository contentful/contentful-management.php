<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2018 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\Management\E2E;

use Contentful\Management\Exception\VersionMismatchException;
use Contentful\Management\Resource\Asset;
use Contentful\Management\Resource\Locale;
use Contentful\Management\SystemProperties;
use Contentful\Tests\Management\BaseTestCase;
use function GuzzleHttp\json_encode as guzzle_json_encode;

class ErrorTest extends BaseTestCase
{
    /**
     * @expectedException        \Contentful\Management\Exception\UnknownKeyException
     * @expectedExceptionMessage The body you sent contains an unknown key.
     *
     * @vcr e2e_error_unknown_key.json
     */
    public function testUnknownKey()
    {
        $proxy = $this->getReadWriteEnvironmentProxy();

        $locale = new UnknownKeyLocale('American Italian', 'it-US');
        $proxy->create($locale);
    }

    /**
     * @expectedException        \Contentful\Management\Exception\MissingKeyException
     * @expectedExceptionMessage Request body is missing a required key.
     *
     * @vcr e2e_error_missing_key.json
     */
    public function testMissingKey()
    {
        $proxy = $this->getReadWriteEnvironmentProxy();

        $locale = new EmptyBodyLocale('American Italian', 'it-US');
        $proxy->create($locale);
    }

    /**
     * @expectedException        \Contentful\Management\Exception\ValidationFailedException
     * @expectedExceptionMessage The resource you sent in the body is invalid.
     *
     * @vcr e2e_error_validation_failed.json
     */
    public function testValidationFailed()
    {
        $proxy = $this->getReadWriteEnvironmentProxy();

        $locale = new ValidationFailedLocale('American Italian', 'it-US');
        $proxy->create($locale);
    }

    /**
     * @vcr e2e_error_version_mismatch.json
     */
    public function testVersionMismatch()
    {
        $proxy = $this->getReadWriteEnvironmentProxy();

        $asset = new Asset();
        $proxy->create($asset);

        $fakeAsset = new FakeAsset($asset->getId(), $this->readWriteSpaceId);
        $fakeAsset->setClient($this->getClient());

        try {
            $fakeAsset->update();
        } catch (VersionMismatchException $exception) {
            $this->assertSame('The version number you supplied is invalid.', $exception->getMessage());

            return;
        } finally {
            $asset->delete();
        }

        $this->fail('Did not throw VersionMismatchException.');
    }

    /**
     * @expectedException        \Contentful\Management\Exception\DefaultLocaleNotDeletableException
     * @expectedExceptionMessage Cannot delete a default locale
     *
     * @vcr e2e_error_default_locale_not_deletable.json
     */
    public function testDefaultLocaleNotDeletable()
    {
        $proxy = $this->getReadWriteEnvironmentProxy();
        $locales = $proxy->getLocales();

        /** @var Locale $locale */
        foreach ($locales as $locale) {
            if ($locale->isDefault()) {
                $locale->delete();
            }
        }
    }

    /**
     * @expectedException        \Contentful\Management\Exception\FallbackLocaleNotDeletableException
     * @expectedExceptionMessage Cannot delete locale which is fallback of another one
     *
     * @vcr e2e_error_fallback_locale_not_deletable.json
     */
    public function testFallbackLocaleNotDeletable()
    {
        // The space has a fallback chain of en-AU -> en-GB -> en-US (default)
        $proxy = $this->getReadOnlyEnvironmentProxy();

        $locales = $proxy->getLocales();
        /** @var Locale $locale */
        foreach ($locales as $locale) {
            if ('en-GB' === $locale->getCode()) {
                $locale->delete();
            }
        }
    }

    /**
     * @expectedException        \Contentful\Management\Exception\FallbackLocaleNotRenameableException
     * @expectedExceptionMessage Cannot change the code of a locale which is fallback of another one
     *
     * @vcr e2e_error_fallback_locale_not_renameable.json
     */
    public function testFallbackLocaleNotRenameable()
    {
        // The space has a fallback chain of en-AU -> en-GB -> en-US (default)
        $proxy = $this->getReadOnlyEnvironmentProxy();

        $locales = $proxy->getLocales();
        /** @var Locale $locale */
        foreach ($locales as $locale) {
            if ('en-GB' === $locale->getCode()) {
                $locale->setCode('en-NZ');
                $locale->update();
            }
        }
    }
}

class UnknownKeyLocale extends Locale
{
    public function asRequestBody(): string
    {
        $body = $this->jsonSerialize();

        unset($body['sys']);
        unset($body['default']);

        $body['unknownKey'] = 'unknownValue';

        return guzzle_json_encode((object) $body, \JSON_UNESCAPED_UNICODE);
    }
}

class EmptyBodyLocale extends Locale
{
    public function asRequestBody(): string
    {
        return '{}';
    }
}

class ValidationFailedLocale extends Locale
{
    public function asRequestBody(): string
    {
        return '{"name":"A cool locale"}';
    }
}

class FakeAsset extends Asset
{
    public function __construct(string $id, string $spaceId)
    {
        parent::__construct();

        $this->sys = new SystemProperties([
            'type' => 'Asset',
            'id' => $id,
            'version' => 23,
            'space' => [
                'sys' => [
                    'type' => 'Link',
                    'linkType' => 'Space',
                    'id' => $spaceId,
                ],
            ],
            'environment' => [
                'sys' => [
                    'type' => 'Link',
                    'linkType' => 'Environment',
                    'id' => 'master',
                ],
            ],
        ]);
    }
}
