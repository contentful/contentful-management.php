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
 * AndConstraint class.
 *
 * The AndConstraint evaluates if all its conditions are true.
 * The value of the AndConstraint needs to be an array,
 * with an arbitrary amount of constraints.
 *
 * The AndConstraint is necessary and useful for a broad variety of use cases.
 * An example for the constraint is the limitation to a specific content type:
 *
 * ``` json
 * {
 *   "and": [
 *     {"equals": [{"doc": "sys.type"}, "Entry"]},
 *     {"equals": [{"doc": "sys.contentType.sys.id"}, "content-type-id"]}
 *   ]
 * }
 * ```
 */
class AndConstraint extends LogicConstraint
{
    /**
     * {@inheritdoc}
     */
    protected function getOperator(): string
    {
        return 'and';
    }
}
