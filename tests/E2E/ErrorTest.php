<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2025 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Management\E2E;

use Contentful\Management\Exception\DefaultLocaleNotDeletableException;
use Contentful\Management\Exception\FallbackLocaleNotDeletableException;
use Contentful\Management\Exception\FallbackLocaleNotRenameableException;
use Contentful\Management\Exception\MissingKeyException;
use Contentful\Management\Exception\UnknownKeyException;
use Contentful\Management\Exception\ValidationFailedException;
use Contentful\Management\Exception\VersionMismatchException;
use Contentful\Management\Resource\Asset;
use Contentful\Management\Resource\Locale;
use Contentful\Tests\Management\BaseTestCase;
use Contentful\Tests\Management\Implementation\EmptyBodyLocale;
use Contentful\Tests\Management\Implementation\FakeAsset;
use Contentful\Tests\Management\Implementation\UnknownKeyLocale;
use Contentful\Tests\Management\Implementation\ValidationFailedLocale;

class ErrorTest extends BaseTestCase
{
    /**
     * @vcr e2e_error_unknown_key.json
     */
    public function testUnknownKey()
    {
        $this->expectException(UnknownKeyException::class);
        $this->expectExceptionMessage('The body you sent contains an unknown key.');

        $proxy = $this->getReadWriteEnvironmentProxy();

        $locale = new UnknownKeyLocale('American Italian', 'it-US');
        $proxy->create($locale);
    }

    /**
     * @vcr e2e_error_missing_key.json
     */
    public function testMissingKey()
    {
        $this->expectException(MissingKeyException::class);
        $this->expectExceptionMessage('Request body is missing a required key.');

        $proxy = $this->getReadWriteEnvironmentProxy();

        $locale = new EmptyBodyLocale('American Italian', 'it-US');
        $proxy->create($locale);
    }

    /**
     * @vcr e2e_error_validation_failed.json
     */
    public function testValidationFailed()
    {
        $this->expectException(ValidationFailedException::class);
        $this->expectExceptionMessage('The resource you sent in the body is invalid.');

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
     * @vcr e2e_error_default_locale_not_deletable.json
     */
    public function testDefaultLocaleNotDeletable()
    {
        $this->expectException(DefaultLocaleNotDeletableException::class);
        $this->expectExceptionMessage('Cannot delete a default locale');

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
     * @vcr e2e_error_fallback_locale_not_deletable.json
     */
    public function testFallbackLocaleNotDeletable()
    {
        $this->expectException(FallbackLocaleNotDeletableException::class);
        $this->expectExceptionMessage('Cannot delete locale which is fallback of another one');

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
     * @vcr e2e_error_fallback_locale_not_renameable.json
     */
    public function testFallbackLocaleNotRenameable()
    {
        $this->expectException(FallbackLocaleNotRenameableException::class);
        $this->expectExceptionMessage('Cannot change the code of a locale which is fallback of another one');

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
