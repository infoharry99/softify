<?php

namespace App\Services\BooleanSearch;

class Tokenizer
{
    /**
     * Tokenize the input Boolean search string into a list of Token objects.
     *
     * @param string $input
     * @return Token[]
     */
    public function tokenize(string $input): array
    {
        $tokens = [];
        $length = strlen($input);
        $i = 0;

        while ($i < $length) {
            $char = $input[$i];

            // 1. Skip whitespace
            if (ctype_space($char)) {
                $i++;
                continue;
            }

            // 2. Parentheses
            if ($char === '(') {
                $tokens[] = new Token(Token::T_LPAREN, '(', $i);
                $i++;
                continue;
            }

            if ($char === ')') {
                $tokens[] = new Token(Token::T_RPAREN, ')', $i);
                $i++;
                continue;
            }

            // 3. Quoted string / phrase ("..." or '...')
            if ($char === '"' || $char === "'") {
                $quoteChar = $char;
                $startPos = $i;
                $i++;
                $phrase = '';

                while ($i < $length && $input[$i] !== $quoteChar) {
                    if ($input[$i] === '\\' && ($i + 1) < $length) {
                        $i++;
                        $phrase .= $input[$i];
                    } else {
                        $phrase .= $input[$i];
                    }
                    $i++;
                }

                if ($i < $length && $input[$i] === $quoteChar) {
                    $i++; // Consume closing quote
                }

                $cleanPhrase = trim($phrase);
                if ($cleanPhrase !== '') {
                    $tokens[] = new Token(Token::T_TERM, $cleanPhrase, $startPos);
                }
                continue;
            }

            // 4. Word or Operator
            $startPos = $i;
            $word = '';

            // Consume characters until whitespace, paren, or quote
            while ($i < $length) {
                $c = $input[$i];
                if (ctype_space($c) || $c === '(' || $c === ')' || $c === '"' || $c === "'") {
                    break;
                }
                $word .= $c;
                $i++;
            }

            if ($word === '') {
                $i++;
                continue;
            }

            $upperWord = strtoupper($word);

            // Operators: AND, OR, NOT, &&, ||, !
            if ($upperWord === 'AND' || $upperWord === '&&') {
                $tokens[] = new Token(Token::T_AND, 'AND', $startPos);
            } elseif ($upperWord === 'OR' || $upperWord === '||') {
                $tokens[] = new Token(Token::T_OR, 'OR', $startPos);
            } elseif ($upperWord === 'NOT' || $upperWord === '!' || ($word === '-' && $i < $length && !ctype_space($input[$i]))) {
                $tokens[] = new Token(Token::T_NOT, 'NOT', $startPos);
            } else {
                $tokens[] = new Token(Token::T_TERM, $word, $startPos);
            }
        }

        $tokens[] = new Token(Token::T_EOF, '', $length);

        return $tokens;
    }
}
