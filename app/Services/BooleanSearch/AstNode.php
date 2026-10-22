<?php

namespace App\Services\BooleanSearch;

use Illuminate\Database\Eloquent\Builder;

abstract class AstNode
{
    /**
     * Apply this AST node condition to the given Eloquent query builder.
     *
     * @param Builder|\Illuminate\Database\Query\Builder $query
     * @return void
     */
    abstract public function applyToQuery($query): void;

    /**
     * Collect all positive matched keywords (terms not under a NOT operator)
     * for keyword highlighting and relevance calculation.
     *
     * @param array $terms
     * @return void
     */
    abstract public function extractPositiveTerms(array &$terms): void;

    /**
     * Format node as a clean string.
     *
     * @return string
     */
    abstract public function toReadableString(): string;
}
