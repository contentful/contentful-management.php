<?php
/**
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Tests\E2E;

use Contentful\Management\Asset;
use Contentful\Management\Exception\VersionMismatchException;
use Contentful\Management\Locale;
use Contentful\Management\SystemProperties;
use Contentful\Tests\End2EndTestCase;

class ErrorTest extends End2EndTestCase
{
    /**
     * @expectedException \Contentful\Management\Exception\UnknownKeyException
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
     * @vcr e2e_error_missing_key.json
     */
    public function testMissingKeyError()
    {
        $spaceManager = $this->getReadWriteSpaceManager();

        $locale = new EmptyBodyLocale('American German', 'de-US');
        $spaceManager->create($locale);
    }

    /**
     * @expectedException \Contentful\Management\Exception\BadRequestException
     * @vcr e2e_error_bad_request.json
     */
    public function testBadRequestError()
    {
        $spaceManager = $this->getReadWriteSpaceManager();

        $asset = new NoBodyAsset();
        $spaceManager->create($asset);
    }

    /**
     * @expectedException \Contentful\Management\Exception\ValidationFailedException
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

        $asset = new Asset;
        $spaceManager->create($asset);

        $fakeAsset = new FakeAsset($asset->getSystemProperties()->getId());

        try {
            $spaceManager->update($fakeAsset);
        } catch (VersionMismatchException $e) {
            return;
        } finally {
            $spaceManager->delete($asset);
        }

        $this->fail('Did not throw VersionMismatchException');
    }
}

class UnknownKeyLocale extends Locale
{
    public function jsonSerialize()
    {
        $data = parent::jsonSerialize();

        $data->default = true;
        $data->avx = 'def';

        return $data;
    }
}

class NoBodyAsset extends Asset
{
    public function jsonSerialize()
    {
        return null;
    }
}

class EmptyBodyLocale extends Locale
{
    public function jsonSerialize()
    {
        return (object) [];
    }
}

class ValidationFailedLocale extends Locale
{
    public function jsonSerialize()
    {
        return (object) [
            'name' => 'A cool locale'
        ];
    }
}

class FakeAsset extends Asset
{
    private $fakeSys;

    public function __construct(string $id)
    {
        parent::__construct();

        $this->fakeSys = new SystemProperties([
            'type' => 'Asset',
            'id' => $id,
            'version' => 23
        ]);
    }

    public function getSystemProperties(): SystemProperties
    {
        return $this->fakeSys;
    }
}
