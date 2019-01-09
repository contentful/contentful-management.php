<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2019 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\Resource\Role\Constraint;

/**
 * OrConstraint class.
 *
 * The OrConstraint evaluates if one if its conditions returns true.
 * The value of the OrConstraint needs to be an array,
 * with an arbitrary amount of constraints.
 *
 * OrConstraint can be used to enable an effect for various different resources.
 * E.g. a user should only be allowed to read entries of a specific content type
 * or all assets:
 *
 * ``` json
 * {
 *   "or": [
 *     {"equals": [{"doc": "sys.type"}, "Entry"]},
 *     {"equals": [{"doc": "sys.type"}, "Asset"]}
 *   ]
 * }
 * ```
 */
class OrConstraint extends LogicConstraint
{
    /**
     * {@inheritdoc}
     */
    protected function getOperator(): string
    {
        return 'or';
    }
}
