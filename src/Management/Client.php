<?php
/**
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management;

use Contentful\Client as BaseClient;

class Client extends BaseClient
{
    const VERSION = '0.6.0-dev';

    /**
     * Client constructor.
     *
     * @param string $token
     * @param array $options
     *
     * @api
     */
    public function __construct(string $token, array $options = [])
    {
        $baseUri = 'https://api.contentful.com/';
        $api = 'MANAGEMENT';

        $options = array_replace([
            'guzzle' => null,
            'logger' => null,
            'uriOverride' => null
        ], $options);

        $guzzle = $options['guzzle'];
        $logger = $options['logger'];
        $uriOverride = $options['uriOverride'];

        if ($uriOverride !== null) {
            $baseUri = $uriOverride;
        }
        $baseUri .= 'spaces/';

        parent::__construct($token, $baseUri, $api, $logger, $guzzle);
    }

    /**
     * The name of the library to be used in the User-Agent header.
     *
     * @return string
     */
    protected function getSdkNameAndVersion()
    {
        return 'contentful-management.php/' . self::VERSION;
    }
}
