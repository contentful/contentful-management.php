<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\Management\E2E;

use Contentful\Management\Exception\VersionMismatchException;
use Contentful\Management\Resource\Asset;
use Contentful\Management\Resource\Locale;
use Contentful\Management\SystemProperties;
use Contentful\Tests\Management\BaseTestCase;

class ErrorTest extends BaseTestCase
{
    /**
     * @expectedException \Contentful\Management\Exception\UnknownKeyException
     * @expectedExceptionMessage The body you sent contains an unknown key.
     * @vcr e2e_error_unknown_key.json
     */
    public function testUnknownKeyError()
    {
        $client = $this->getDefaultClient();

        $locale = new UnknownKeyLocale('American Italian', 'it-US');
        $client->locale->create($locale);
    }

    /**
     * @expectedException \Contentful\Management\Exception\MissingKeyException
     * @expectedExceptionMessage Request body is missing a required key.
     * @vcr e2e_error_missing_key.json
     */
    public function testMissingKeyError()
    {
        $client = $this->getDefaultClient();

        $locale = new EmptyBodyLocale('American Italian', 'it-US');
        $client->locale->create($locale);
    }

    /**
     * @expectedException \Contentful\Management\Exception\ValidationFailedException
     * @expectedExceptionMessage The resource you sent in the body is invalid.
     * @vcr e2e_error_validation_failed.json
     */
    public function testValidationFailedError()
    {
        $client = $this->getDefaultClient();

        $locale = new ValidationFailedLocale('American Italian', 'it-US');
        $client->locale->create($locale);
    }

    /**
     * @vcr e2e_error_version_mismatch.json
     */
    public function testVersionMismatchError()
    {
        $client = $this->getDefaultClient();

        $asset = new Asset();
        $client->asset->create($asset);

        $fakeAsset = new FakeAsset($asset->getId(), $this->defaultSpaceId);
        $fakeAsset->setProxy($client->asset);

        try {
            $fakeAsset->update();
        } catch (VersionMismatchException $e) {
            $this->assertEquals('The version number you supplied is invalid.', $e->getMessage());

            return;
        } finally {
            $asset->delete();
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
        $client = $this->getDefaultClient();
        $defaultLocale = $client->locale->get('6khdsfQbtrObkbrgWDTGe8');

        $defaultLocale->delete();
    }

    /**
     * @expectedException \Contentful\Management\Exception\FallbackLocaleNotDeletableException
     * @expectedExceptionMessage Cannot delete locale which is fallback of another one
     * @vcr e2e_error_fallback_locale_not_deletable.json
     */
    public function testFallbackLocaleNotDeletableError()
    {
        $client = $this->getDefaultClient();
        // The space has a fallback chain of en-AU -> en-GB -> en-US (default)
        $enGbLocale = $client->locale->get('71wkZKqgktY9Uzg76CtsBK');

        $enGbLocale->delete();
    }

    /**
     * @expectedException \Contentful\Management\Exception\FallbackLocaleNotRenameableException
     * @expectedExceptionMessage Cannot change the code of a locale which is fallback of another one
     * @vcr e2e_error_fallback_locale_not_renameable.json
     */
    public function testFallbackLocaleNotRenameableError()
    {
        $client = $this->getDefaultClient();
        // The space has a fallback chain of en-AU -> en-GB -> en-US (default)
        $enGbLocale = $client->locale->get('71wkZKqgktY9Uzg76CtsBK');

        $enGbLocale->setCode('en-NZ');
        $enGbLocale->update();
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

        return json_encode((object) $body, JSON_UNESCAPED_UNICODE);
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
        ]);
    }
}
