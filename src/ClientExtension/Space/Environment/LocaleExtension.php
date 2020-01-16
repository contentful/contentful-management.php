<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2020 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\ClientExtension\Space\Environment;

use Contentful\Core\Resource\ResourceArray;
use Contentful\Management\Query;
use Contentful\Management\Resource\Locale as ResourceClass;
use Contentful\Management\Resource\ResourceInterface;

/**
 * LocaleExtension trait.
 *
 * This extension is supposed to be applied to Client class which provides a `fetchResource` method.
 *
 * @method ResourceInterface|ResourceArray fetchResource(string $class, array $parameters, Query $query = null, ResourceInterface $resource = null)
 */
trait LocaleExtension
{
    /**
     * Returns a Locale resource.
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/locales/locale
     */
    public function getLocale(string $spaceId, string $environmentId, string $localeId): ResourceClass
    {
        return $this->fetchResource(ResourceClass::class, [
            'space' => $spaceId,
            'environment' => $environmentId,
            'locale' => $localeId,
        ]);
    }

    /**
     * Returns a ResourceArray object containing Locale resources.
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/locales/locale-collection
     */
    public function getLocales(string $spaceId, string $environmentId): ResourceArray
    {
        return $this->fetchResource(ResourceClass::class, [
            'space' => $spaceId,
            'environment' => $environmentId,
        ]);
    }
}
