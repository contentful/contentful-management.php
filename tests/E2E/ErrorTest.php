<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\E2E\Management;

use Contentful\Management\Exception\VersionMismatchException;
use Contentful\Management\Resource\Asset;
use Contentful\Management\Resource\Locale;
use Contentful\Management\SystemProperties;
use Contentful\Tests\End2EndTestCase;
use function GuzzleHttp\json_encode;

class ErrorTest extends End2EndTestCase
{
    /**
     * @expectedException \Contentful\Management\Exception\UnknownKeyException
     * @expectedExceptionMessage The body you sent contains an unknown key.
     * @vcr e2e_error_unknown_key.json
     */
    public function testUnknownKeyError()
    {
        $spaceManager = $this->getReadWriteSpaceManager();

        $locale = new UnknownKeyLocale('American German', 'de-US');
        $spaceManager->create($locale);
    }

    /**
     * @expectedException \Contentful\Management\Exception\MissingKeyException
     * @expectedExceptionMessage Request body is missing a required key.
     * @vcr e2e_error_missing_key.json
     */
    public function testMissingKeyError()
    {
        $spaceManager = $this->getReadWriteSpaceManager();

        $locale = new EmptyBodyLocale('American German', 'de-US');
        $spaceManager->create($locale);
    }

    /**
     * @expectedException \Contentful\Management\Exception\ValidationFailedException
     * @expectedExceptionMessage The resource you sent in the body is invalid.
     * @vcr e2e_error_validation_failed.json
     */
    public function testValidationFailedError()
    {
        $spaceManager = $this->getReadWriteSpaceManager();

        $locale = new ValidationFailedLocale('American German', 'de-US');
        $spaceManager->create($locale);
    }

    /**
     * @vcr e2e_error_version_mismatch.json
     */
    public function testVersionMismatchError()
    {
        $spaceManager = $this->getReadWriteSpaceManager();

        $asset = new Asset();
        $spaceManager->create($asset);

        $fakeAsset = new FakeAsset($asset->getSystemProperties()->getId(), $this->readWriteSpaceId);

        try {
            $spaceManager->update($fakeAsset);
        } catch (VersionMismatchException $e) {
            $this->assertEquals('The version number you supplied is invalid.', $e->getMessage());

            return;
        } finally {
            $spaceManager->delete($asset);
        }

        $this->fail('Did not throw VersionMismatchException.');
    }

    /**
     * @expectedException \Contentful\Management\Exception\DefaultLocaleNotDeletableException
     * @expectedExceptionMessage Cannot delete a default locale
     * @vcr e2e_error_default_locale_not_deletable.json
     */
    public function testDefaultLocaleNotDeletableError()
    {
        $spaceManager = $this->getReadWriteSpaceManager();
        $defaultLocale = $spaceManager->getLocale('6khdsfQbtrObkbrgWDTGe8');

        $spaceManager->delete($defaultLocale);
    }

    /**
     * @expectedException \Contentful\Management\Exception\FallbackLocaleNotDeletableException
     * @expectedExceptionMessage Cannot delete locale which is fallback of another one
     * @vcr e2e_error_fallback_locale_not_deletable.json
     */
    public function testFallbackLocaleNotDeletableError()
    {
        $spaceManager = $this->getReadWriteSpaceManager();
        // The space has a fallback chain of en-AU -> en-GB -> en-US (default)
        $enGbLocale = $spaceManager->getLocale('71wkZKqgktY9Uzg76CtsBK');

        $spaceManager->delete($enGbLocale);
    }

    /**
     * @expectedException \Contentful\Management\Exception\FallbackLocaleNotRenameableException
     * @expectedExceptionMessage Cannot change the code of a locale which is fallback of another one
     * @vcr e2e_error_fallback_locale_not_renameable.json
     */
    public function testFallbackLocaleNotRenameableError()
    {
        $spaceManager = $this->getReadWriteSpaceManager();
        // The space has a fallback chain of en-AU -> en-GB -> en-US (default)
        $enGbLocale = $spaceManager->getLocale('71wkZKqgktY9Uzg76CtsBK');

        $enGbLocale->setCode('en-NZ');
        $spaceManager->update($enGbLocale);
    }
}

class UnknownKeyLocale extends Locale
{
    public function jsonSerialize(): array
    {
        $data = parent::jsonSerialize();

        $data['default'] = true;
        $data['avx'] = 'def';

        return $data;
    }
}

class EmptyBodyLocale extends Locale
{
    public function jsonSerialize(): array
    {
        return [];
    }
}

class ValidationFailedLocale extends Locale
{
    public function jsonSerialize(): array
    {
        return [
            'name' => 'A cool locale',
        ];
    }
}

class FakeAsset extends Asset
{
    private $fakeSys;

    public function __construct(string $id, string $spaceId)
    {
        parent::__construct();

        $this->fakeSys = new SystemProperties([
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
        ]);
    }

    public function getSystemProperties(): SystemProperties
    {
        return $this->fakeSys;
    }
}
