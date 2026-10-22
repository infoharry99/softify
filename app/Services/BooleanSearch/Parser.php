<?php

namespace App\Services\BooleanSearch;

use Exception;

class ParserException extends Exception
{
}

class Parser
{
    /** @var Token[] */
    protected array $tokens;
    protected int $current = 0;

    /**
     * Parse token stream into an AstNode tree.
     *
     * @param Token[] $tokens
     * @return AstNode
     * @throws ParserException
     */
    public function parse(array $tokens): AstNode
    {
        $this->tokens = $tokens;
        $this->current = 0;

        // Strip any leading EOF or empty check
        if ($this->isAtEnd() || $this->peek()->type === Token::T_EOF) {
            throw new ParserException('Please enter a search expression.');
        }

        $node = $this->parseOr();

        if (!$this->isAtEnd() && $this->peek()->type !== Token::T_EOF) {
            $token = $this->peek();
            if ($token->type === Token::T_RPAREN) {
                throw new ParserException("Unexpected closing parenthesis ')' without a matching opening '('.");
            }
            throw new ParserException("Unexpected token '{$token->value}' at position {$token->position}.");
        }

        return $node;
    }

    /**
     * Expression: handles OR (lowest precedence among binary operators)
     */
    protected function parseOr(): AstNode
    {
        $node = $this->parseAnd();

        while ($this->match(Token::T_OR)) {
            $opToken = $this->previous();
            if ($this->isAtEnd() || $this->peek()->type === Token::T_EOF || $this->peek()->type === Token::T_RPAREN) {
                throw new ParserException("Operator 'OR' requires a right-hand search term or expression.");
            }
            $right = $this->parseAnd();
            $node = new BinaryNode('OR', $node, $right);
        }

        return $node;
    }

    /**
     * Expression: handles AND
     */
    protected function parseAnd(): AstNode
    {
        $node = $this->parseNot();

        while (true) {
            if ($this->match(Token::T_AND)) {
                $opToken = $this->previous();
                if ($this->isAtEnd() || $this->peek()->type === Token::T_EOF || $this->peek()->type === Token::T_RPAREN) {
                    throw new ParserException("Operator 'AND' requires a right-hand search term or expression.");
                }
                $right = $this->parseNot();
                $node = new BinaryNode('AND', $node, $right);
            } elseif ($this->check(Token::T_NOT)) {
                // Syntactic convenience: "Java NOT Angular" => "Java AND (NOT Angular)"
                $right = $this->parseNot();
                $node = new BinaryNode('AND', $node, $right);
            } elseif ($this->canStartPrimary()) {
                // Implicit AND between adjacent terms/groups, e.g. 'Java Python' or '("AWS" Java)'
                $right = $this->parseNot();
                $node = new BinaryNode('AND', $node, $right);
            } else {
                break;
            }
        }

        return $node;
    }

    /**
     * Expression: handles NOT (highest precedence unary operator)
     */
    protected function parseNot(): AstNode
    {
        if ($this->match(Token::T_NOT)) {
            if ($this->isAtEnd() || $this->peek()->type === Token::T_EOF || $this->peek()->type === Token::T_RPAREN) {
                throw new ParserException("Operator 'NOT' requires a term or expression to negate.");
            }
            $child = $this->parseNot();
            return new NotNode($child);
        }

        return $this->parsePrimary();
    }

    /**
     * Primary: Term or ( Expression )
     */
    protected function parsePrimary(): AstNode
    {
        if ($this->match(Token::T_TERM)) {
            $token = $this->previous();
            return new TermNode($token->value, strpos($token->value, ' ') !== false);
        }

        if ($this->match(Token::T_LPAREN)) {
            $lparen = $this->previous();
            if ($this->isAtEnd() || $this->peek()->type === Token::T_EOF) {
                throw new ParserException("Missing closing parenthesis ')' for '(' opened at position {$lparen->position}.");
            }
            if ($this->check(Token::T_RPAREN)) {
                throw new ParserException("Empty parentheses '()' are not allowed.");
            }

            $node = $this->parseOr();

            if (!$this->match(Token::T_RPAREN)) {
                throw new ParserException("Missing closing parenthesis ')' matching '(' at position {$lparen->position}.");
            }

            return $node;
        }

        $token = $this->peek();
        if ($token->type === Token::T_AND || $token->type === Token::T_OR) {
            throw new ParserException("Misplaced operator '{$token->value}' at position {$token->position}. An operator must be preceded by a term.");
        }

        if ($token->type === Token::T_RPAREN) {
            throw new ParserException("Unexpected closing parenthesis ')' at position {$token->position}.");
        }

        throw new ParserException("Invalid or unexpected token '{$token->value}' at position {$token->position}.");
    }

    protected function canStartPrimary(): bool
    {
        if ($this->isAtEnd()) {
            return false;
        }
        $type = $this->peek()->type;
        return $type === Token::T_TERM || $type === Token::T_LPAREN;
    }

    protected function match(string ...$types): bool
    {
        foreach ($types as $type) {
            if ($this->check($type)) {
                $this->advance();
                return true;
            }
        }
        return false;
    }

    protected function check(string $type): bool
    {
        if ($this->isAtEnd()) {
            return false;
        }
        return $this->peek()->type === $type;
    }

    protected function advance(): Token
    {
        if (!$this->isAtEnd()) {
            $this->current++;
        }
        return $this->previous();
    }

    protected function isAtEnd(): bool
    {
        return $this->current >= count($this->tokens) || $this->tokens[$this->current]->type === Token::T_EOF;
    }

    protected function peek(): Token
    {
        return $this->tokens[$this->current] ?? new Token(Token::T_EOF, '', 0);
    }

    protected function previous(): Token
    {
        return $this->tokens[$this->current - 1];
    }
}
