<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Management\Mapper;

use Contentful\File\File;
use Contentful\File\FileInterface;
use Contentful\File\ImageFile;
use Contentful\File\LocalUploadFile;
use Contentful\File\RemoteUploadFile;
use Contentful\Link;
use Contentful\Management\Resource\Asset as ResourceClass;
use Contentful\Management\SystemProperties;

/**
 * Asset class.
 *
 * This class is responsible for converting raw API data into a PHP object
 * of class Contentful\Management\Resource\Asset.
 */
class Asset extends BaseMapper
{
    /**
     * {@inheritdoc}
     */
    public function map($resource, array $data): ResourceClass
    {
        $fields = $data['fields'];

        return $this->hydrate($resource ?: ResourceClass::class, [
            'sys' => new SystemProperties($data['sys']),
            'title' => $fields['title'] ?? null,
            'description' => $fields['description'] ?? null,
            'file' => isset($fields['file']) ? \array_map([$this, 'buildFile'], $fields['file']) : null,
        ]);
    }

    /**
     * Returns a PHP object representation of a file stored in Contentful.
     *
     * @param array $data The contents of the "asset.file.[locale]" array
     *
     * @return FileInterface
     */
    protected function buildFile(array $data): FileInterface
    {
        if (isset($data['uploadFrom'])) {
            return new LocalUploadFile(
                $data['fileName'],
                $data['contentType'],
                new Link(
                    $data['uploadFrom']['sys']['id'],
                    $data['uploadFrom']['sys']['linkType']
                )
            );
        }

        if (isset($data['upload'])) {
            return new RemoteUploadFile(
                $data['fileName'],
                $data['contentType'],
                $data['upload']
            );
        }

        $details = $data['details'];
        if (isset($details['image'])) {
            return new ImageFile(
                $data['fileName'],
                $data['contentType'],
                $data['url'],
                $details['size'],
                $details['image']['width'],
                $details['image']['height']
            );
        }

        return new File(
            $data['fileName'],
            $data['contentType'],
            $data['url'],
            $details['size']
        );
    }
}
