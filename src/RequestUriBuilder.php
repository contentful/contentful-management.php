<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2019 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management;

class RequestUriBuilder
{
    /**
     * Given a configuration array an a set of parameters,
     * builds the URI that identifies the current request.
     *
     * @param array    $config
     * @param string[] $parameters
     * @param string   $resourceId
     *
     * @return string
     */
    public function build(array $config, array $parameters, string $resourceId = ''): string
    {
        $idParameter = $config['id'];
        $parameters[$idParameter] = $parameters[$idParameter] ?? $resourceId;

        $parameters = $this->validateParameters(
            $config['parameters'],
            $parameters,
            $idParameter,
            $config['class']
        );

        $replacements = [];
        foreach ($parameters as $key => $value) {
            $replacements['{'.$key.'}'] = $value;
        }

        return \strtr($config['uri'], $replacements);
    }

    /**
     * Validates given parameters for the API request,
     * and throws an exception if they are not correctly set.
     *
     * @param string[] $required    The parameters required from the configuration of a certain endpoint
     * @param string[] $current     The parameters supplied to the current query
     * @param string   $idParameter The name of the parameter that identifies the resource ID
     * @param string   $class       The resource class
     *
     * @throws \RuntimeException When some parameters are missing
     *
     * @return string[]
     */
    private function validateParameters(array $required, array $current, string $idParameter, string $class): array
    {
        $missing = [];
        $valid = [];
        foreach ($required as $parameter) {
            if (!isset($current[$parameter])) {
                $missing[] = $parameter;

                continue;
            }

            $valid[$parameter] = $current[$parameter];
        }

        if ($missing) {
            throw new \RuntimeException(\sprintf(
                'Trying to make an API call on resource of class "%s" without required parameters "%s".',
                $class,
                \implode(', ', $missing)
            ));
        }

        if ($idParameter && isset($current[$idParameter])) {
            $valid[$idParameter] = $current[$idParameter];
        }

        return $valid;
    }
}
