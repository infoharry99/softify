<?php

namespace App\Services\BooleanSearch;

class NotNode extends AstNode
{
    public AstNode $child;

    public function __construct(AstNode $child)
    {
        $this->child = $child;
    }

    public function applyToQuery($query): void
    {
        $query->whereNot(function ($sub) {
            $this->child->applyToQuery($sub);
        });
    }

    public function extractPositiveTerms(array &$terms): void
    {
        // Excluded NOT terms are deliberately not included in positive highlight terms
    }

    public function toReadableString(): string
    {
        return 'NOT ' . $this->child->toReadableString();
    }
}
