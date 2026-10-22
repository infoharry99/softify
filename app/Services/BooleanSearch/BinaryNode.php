<?php

namespace App\Services\BooleanSearch;

class BinaryNode extends AstNode
{
    public string $operator; // 'AND' or 'OR'
    public AstNode $left;
    public AstNode $right;

    public function __construct(string $operator, AstNode $left, AstNode $right)
    {
        $this->operator = strtoupper($operator);
        $this->left = $left;
        $this->right = $right;
    }

    public function applyToQuery($query): void
    {
        if ($this->operator === 'AND') {
            $query->where(function ($sub) {
                $this->left->applyToQuery($sub);
            })->where(function ($sub) {
                $this->right->applyToQuery($sub);
            });
        } elseif ($this->operator === 'OR') {
            $query->where(function ($sub) {
                $sub->where(function ($subLeft) {
                    $this->left->applyToQuery($subLeft);
                })->orWhere(function ($subRight) {
                    $this->right->applyToQuery($subRight);
                });
            });
        }
    }

    public function extractPositiveTerms(array &$terms): void
    {
        $this->left->extractPositiveTerms($terms);
        $this->right->extractPositiveTerms($terms);
    }

    public function toReadableString(): string
    {
        return '(' . $this->left->toReadableString() . ' ' . $this->operator . ' ' . $this->right->toReadableString() . ')';
    }
}
