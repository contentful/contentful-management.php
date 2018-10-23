<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2018 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Management\E2E;

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
        $fakeAsset->setProxy($proxy);

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
