<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2019 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\Resource\Role;

use Contentful\Management\Resource\Role\Constraint\ConstraintInterface;

/**
 * Policy class.
 */
class Policy implements \JsonSerializable
{
    /**
     * @var string[]
     */
    const EFFECTS = [
        'allow',
        'deny',
    ];

    /**
     * @var string[]
     */
    const ACTIONS = [
        'read',
        'create',
        'update',
        'delete',
        'publish',
        'unpublish',
        'archive',
        'unarchive',
    ];

    /**
     * @var string
     */
    private $effect;

    /**
     * @var string|string[]
     */
    private $actions;

    /**
     * @var ConstraintInterface|null
     */
    private $constraint;

    /**
     * Policy constructor.
     *
     * @param string                   $effect     Either "allow" or "deny"
     * @param string|string[]          $actions    Either "all" or an array
     * @param ConstraintInterface|null $constraint
     */
    public function __construct(string $effect, $actions = [], ConstraintInterface $constraint = \null)
    {
        $this->setEffect($effect);
        $this->setActions($actions);
        $this->constraint = $constraint;
    }

    /**
     * @return string Either "allow" or "deny"
     */
    public function getEffect(): string
    {
        return $this->effect;
    }

    /**
     * @param string $effect Either "allow" or "deny"
     *
     * @return static
     */
    public function setEffect(string $effect)
    {
        if (!\in_array($effect, self::EFFECTS, \true)) {
            throw new \InvalidArgumentException(\sprintf(
                'Parameter "$effect" in "Policy::setEffect()" must have either the value "allow" or "deny", "%s" given.',
                $effect
            ));
        }

        $this->effect = $effect;

        return $this;
    }

    /**
     * @return string|string[] Either the string "all", or an array with available actions
     */
    public function getActions()
    {
        return $this->actions;
    }

    /**
     * @param string|string[] $actions Either the string "all", or an array with available actions
     *
     * @return static
     */
    public function setActions($actions)
    {
        if (
            (!\is_string($actions) && !\is_array($actions)) ||
            (\is_string($actions) && 'all' !== $actions) ||
            (\is_array($actions) && \array_diff($actions, self::ACTIONS))
        ) {
            throw new \InvalidArgumentException(\sprintf(
                'Argument "$actions" in "Policy::setActions()" must be either a string "all", or an array containing a subset of these values: %s.',
                \implode(', ', self::ACTIONS)
            ));
        }

        $this->actions = $actions;

        return $this;
    }

    /**
     * @param string $action
     *
     * @return static
     */
    public function addAction(string $action)
    {
        if (\is_string($this->actions)) {
            throw new \LogicException(
                'Trying to add an action to a set, but the current value is a string. Use "Policy::setActions()" to initialize to an array.'
            );
        }

        if (!\in_array($action, self::ACTIONS, \true)) {
            throw new \InvalidArgumentException(\sprintf(
                'Argument "$action" in "Policy::addAction()" must be one of these values: %s.',
                \implode(', ', self::ACTIONS)
            ));
        }

        $this->actions[] = $action;
        $this->actions = \array_unique($this->actions);

        return $this;
    }

    /**
     * @return ConstraintInterface|null
     */
    public function getConstraint()
    {
        return $this->constraint;
    }

    /**
     * @param ConstraintInterface|null $constraint
     *
     * @return static
     */
    public function setConstraint(ConstraintInterface $constraint = \null)
    {
        $this->constraint = $constraint;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize(): array
    {
        $policy = [
            'effect' => $this->effect,
            'actions' => $this->actions,
        ];

        if ($this->constraint) {
            $policy['constraint'] = $this->constraint;
        }

        return $policy;
    }
}
