<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2025 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\CodeGenerator;

use Contentful\Management\Resource\ContentType\Field\ArrayField;
use Contentful\Management\Resource\ContentType\Field\FieldInterface;
use PhpParser\Comment;
use PhpParser\Node;
use PhpParser\PrettyPrinter\Standard as StandardPrettyPrinter;

/**
 * BaseCodeGenerator class.
 */
abstract class BaseCodeGenerator
{
    /**
     * @var string
     */
    protected $defaultLocale;

    /**
     * BaseCodeGenerator constructor.
     */
    public function __construct(string $defaultLocale)
    {
        $this->defaultLocale = $defaultLocale;
    }

    abstract public function generate(array $params): string;

    /**
     * Returns a rendered node.
     */
    protected function render(Node $node): string
    {
        $prettyPrinter = new StandardPrettyPrinter([
            'shortArraySyntax' => true,
        ]);

        $code = $prettyPrinter->prettyPrintFile([$node]);

        // Removes spaces from blank lines
        $code = \preg_replace('/\n(\s+)\n/', "\n\n", $code)."\n";
        $code = \strtr($code, [
            // Removes space after parenthesis with return types
            ') : ' => '): ',
            // Add a space after `use` statements in closures
            ') use(' => ') use (',
        ]);

        return $code;
    }

    /**
     * Converts a string to StudlyCaps.
     */
    protected function convertToStudlyCaps(string $name): string
    {
        return \ucwords(\str_replace(['-', '_'], ' ', $name));
    }

    /**
     * @param array<int, string|null> $classes
     *
     * @return Node\Stmt\Use_[]
     */
    protected function generateUses(array $classes): array
    {
        $classes = \array_filter($classes);

        \usort($classes, function ($classA, $classB): int {
            // According to the doctype, this array never contains arrays and therefore the call to is_array should
            // always return false. Old implementations might not honor this, though, so we will keep this check.
            // @phpstan-ignore-next-line
            $classA = \is_array($classA) ? $classA['class'] : $classA;
            // @phpstan-ignore-next-line
            $classB = \is_array($classB) ? $classB['class'] : $classB;

            return $classA <=> $classB;
        });

        return \array_map(function ($class): Node\Stmt\Use_ {
            // Same as above.
            // @phpstan-ignore-next-line
            $alias = \is_array($class) ? $class['alias'] : null;
            // @phpstan-ignore-next-line
            $className = \is_array($class) ? $class['class'] : $class;

            return new Node\Stmt\Use_(
                [new Node\Stmt\UseUse(new Node\Name($className), $alias)]
            );
        }, $classes);
    }

    protected function getFieldType(FieldInterface $field): string
    {
        if ($field instanceof ArrayField) {
            if ('Link' === $field->getItemsType()) {
                return 'Link[]';
            }

            return 'string[]';
        }

        $fields = [
            'Location' => 'float[]',
            'Boolean' => 'bool',
            'Date' => 'DateTimeImmutable',
            'Integer' => 'int',
            'Link' => 'Link',
            'Number' => 'float',
            'Object' => 'mixed',
            'Symbol' => 'string',
            'Text' => 'string',
            'RichText' => 'string',
        ];

        return $fields[$field->getType()];
    }

    protected function generateCommentAttributes(string $comment): array
    {
        return [
            'comments' => [
                new Comment($comment),
            ],
        ];
    }
}
