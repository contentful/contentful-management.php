<?php


declare(strict_types=1);

namespace Contentful\Management\Resource\Behavior;

use Contentful\Management\Client;
use Contentful\Management\SystemProperties\VersionableSystemPropertiesInterface;
use Contentful\Management\Resource\ResourceReferences;

/**
 * ArchivableTrait.
 *
 * This trait is supposed to be applied to resources that may locate references to it.
 *
 * @property Client $client
 *
 * @method VersionableSystemPropertiesInterface getSystemProperties()
 */
trait FindReferencesTrait
{
    /**
     * Locates references to the resource
     * @return ResourceReferences
     */
    public function findReferences(int $nestedLevels = 10)
    {
        if ($nestedLevels > 10) {
          throw new Exception("Can only retrieve references nested up to 10 levels deep. Set a smaller value for nestedLevels argument not exceeding 10.");
        }

        return $this->client->requestWithResource($this, 'GET', '/references', [
            'query' => ['include' => $nestedLevels],
        ]);
    }
}
