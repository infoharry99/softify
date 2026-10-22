<?php

namespace App\Services\BooleanSearch;

class Token
{
    public const T_TERM = 'TERM';
    public const T_AND = 'AND';
    public const T_OR = 'OR';
    public const T_NOT = 'NOT';
    public const T_LPAREN = '(';
    public const T_RPAREN = ')';
    public const T_EOF = 'EOF';

    public string $type;
    public string $value;
    public int $position;

    public function __construct(string $type, string $value, int $position = 0)
    {
        $this->type = $type;
        $this->value = $value;
        $this->position = $position;
    }

    public function isOperator(): bool
    {
        return in_array($this->type, [self::T_AND, self::T_OR, self::T_NOT], true);
    }

    public function isTerm(): bool
    {
        return $this->type === self::T_TERM;
    }
}
