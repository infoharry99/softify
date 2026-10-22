<?php

namespace App\Services\BooleanSearch;

use Exception;
use Illuminate\Database\Eloquent\Builder;

class BooleanSearchEngine
{
    protected Tokenizer $tokenizer;
    protected Parser $parser;

    public function __construct()
    {
        $this->tokenizer = new Tokenizer();
        $this->parser = new Parser();
    }

    /**
     * Validate a Boolean search expression string.
     *
     * @param string $expression
     * @return array{valid: bool, error: string|null, tokens_count: int, positive_terms: string[]}
     */
    public function validate(string $expression): array
    {
        $trimmed = trim($expression);
        if ($trimmed === '') {
            return [
                'valid' => false,
                'error' => 'Please enter a search expression.',
                'tokens_count' => 0,
                'positive_terms' => [],
            ];
        }

        try {
            $tokens = $this->tokenizer->tokenize($trimmed);
            $ast = $this->parser->parse($tokens);

            $terms = [];
            $ast->extractPositiveTerms($terms);

            return [
                'valid' => true,
                'error' => null,
                'tokens_count' => count($tokens) - 1, // minus EOF
                'positive_terms' => $terms,
                'readable_expression' => $ast->toReadableString(),
            ];
        } catch (ParserException $e) {
            return [
                'valid' => false,
                'error' => $e->getMessage(),
                'tokens_count' => 0,
                'positive_terms' => [],
            ];
        } catch (Exception $e) {
            return [
                'valid' => false,
                'error' => 'Invalid Boolean expression: ' . $e->getMessage(),
                'tokens_count' => 0,
                'positive_terms' => [],
            ];
        }
    }

    /**
     * Parse and apply a Boolean search expression to an Eloquent query.
     *
     * @param Builder|\Illuminate\Database\Query\Builder $query
     * @param string $expression
     * @return array{success: bool, error: string|null, positive_terms: string[]}
     */
    public function applyToQuery($query, string $expression): array
    {
        $validation = $this->validate($expression);
        if (!$validation['valid']) {
            return [
                'success' => false,
                'error' => $validation['error'],
                'positive_terms' => [],
            ];
        }

        try {
            $tokens = $this->tokenizer->tokenize(trim($expression));
            $ast = $this->parser->parse($tokens);

            // Apply AST condition inside an isolated nested group
            $query->where(function ($sub) use ($ast) {
                $ast->applyToQuery($sub);
            });

            return [
                'success' => true,
                'error' => null,
                'positive_terms' => $validation['positive_terms'],
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'positive_terms' => [],
            ];
        }
    }

    /**
     * Parse simple keyword search into a Boolean AST or query.
     * Splits comma/space terms with OR or AND.
     *
     * @param Builder $query
     * @param string $keyword
     * @param string $mode 'any' or 'all'
     * @return array{positive_terms: string[]}
     */
    public function applySimpleKeywords($query, string $keyword, string $mode = 'all'): array
    {
        $terms = array_filter(array_map('trim', preg_split('/[,;|]+/', $keyword)));
        if (empty($terms)) {
            return ['positive_terms' => []];
        }

        $query->where(function ($sub) use ($terms, $mode) {
            foreach ($terms as $index => $term) {
                if ($term === '') continue;

                $condition = function ($fieldSub) use ($term) {
                    $fieldSub->where('skills', 'like', "%{$term}%")
                        ->orWhere('job_title', 'like', "%{$term}%")
                        ->orWhere('name', 'like', "%{$term}%")
                        ->orWhere('company_name', 'like', "%{$term}%")
                        ->orWhere('location', 'like', "%{$term}%")
                        ->orWhere('note', 'like', "%{$term}%");
                };

                if ($mode === 'any') {
                    if ($index === 0) {
                        $sub->where($condition);
                    } else {
                        $sub->orWhere($condition);
                    }
                } else {
                    $sub->where($condition);
                }
            }
        });

        return ['positive_terms' => array_values($terms)];
    }

    /**
     * Highlight matching terms within a string safely.
     *
     * @param string|null $text
     * @param array $terms
     * @return string
     */
    public static function highlight(?string $text, array $terms): string
    {
        if (empty($text)) {
            return '';
        }
        if (empty($terms)) {
            return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
        }

        $cleanTerms = array_filter(array_map('trim', $terms));
        if (empty($cleanTerms)) {
            return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
        }

        $escaped = array_map(function ($t) {
            return preg_quote($t, '/');
        }, $cleanTerms);

        // Sort terms by length descending so longer phrases match first
        usort($escaped, function ($a, $b) {
            return strlen($b) - strlen($a);
        });

        $pattern = '/(' . implode('|', $escaped) . ')/i';
        $safeText = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');

        return preg_replace($pattern, '<mark class="resdex-highlight">$1</mark>', $safeText);
    }
}
