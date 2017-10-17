<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Management\CodeGenerator;

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
     *
     * @param string $defaultLocale
     */
    public function __construct(string $defaultLocale)
    {
        $this->defaultLocale = $defaultLocale;
    }

    /**
     * @param array $params
     */
    abstract public function generate(array $params): string;

    /**
     * Returns a rendered node.
     *
     * @param Node $node
     *
     * @return string
     */
    protected function render(Node $node): string
    {
        $prettyPrinter = new StandardPrettyPrinter([
            'shortArraySyntax' => true,
        ]);

        $code = $prettyPrinter->prettyPrintFile([$node]);

        // Removes spaces from blank lines
        $code = \preg_replace('/\n(\s+)\n/', "\n\n", $code)."\n";
        // Removes space after parenthesis with return types
        $code = \strtr($code, [') : ' => '): ']);

        return $code;
    }

    /**
     * Converts a string to SutdlyCaps.
     *
     * @param string $name
     *
     * @return string
     */
    protected function convertToStudlyCaps(string $name): string
    {
        return \ucwords(\str_replace(['-', '_'], ' ', $name));
    }

    /**
     * @param (string|null)[] $classes
     *
     * @return Node\Stmt\Use_[]
     */
    protected function generateUses(array $classes): array
    {
        $classes = \array_filter($classes);

        \usort($classes, function (string $classA, string $classB): int {
            return $classA <=> $classB;
        });

        return \array_map(function (string $class): Node\Stmt\Use_ {
            return new Node\Stmt\Use_(
                [new Node\Stmt\UseUse(new Node\Name($class))]
            );
        }, $classes);
    }

    /**
     * @param FieldInterface $field
     *
     * @return string
     */
    protected function getFieldType(FieldInterface $field): string
    {
        if ($field->getType() == 'Array') {
            if ($field->getItemsType() == 'Link') {
                return 'Link[]';
            }

            return 'string[]';
        }

        $fields = [
            'Location' => 'float[]',
            'Boolean' => 'bool',
            'Date' => 'ApiDateTime',
            'Integer' => 'int',
            'Link' => 'Link',
            'Number' => 'float',
            'Object' => 'mixed',
            'Symbol' => 'string',
            'Text' => 'string',
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
