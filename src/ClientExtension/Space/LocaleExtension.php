<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2018 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Management\ClientExtension\Space;

use Contentful\Core\Resource\ResourceArray;
use Contentful\Management\Resource\Locale as ResourceClass;

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
     * @param string $spaceId
     * @param string $localeId
     *
     * @return ResourceClass
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/locales/locale
     */
    public function getLocale(string $spaceId, string $localeId): ResourceClass
    {
        return $this->fetchResource(ResourceClass::class, [
            'space' => $spaceId,
            'locale' => $localeId,
        ]);
    }

    /**
     * Returns a ResourceArray object containing Locale resources.
     *
     * @param string $spaceId
     *
     * @return ResourceArray
     *
     * @see https://www.contentful.com/developers/docs/references/content-management-api/#/reference/locales/locale-collection
     */
    public function getLocales(string $spaceId): ResourceArray
    {
        return $this->fetchResource(ResourceClass::class, [
            'space' => $spaceId,
        ]);
    }
}
