<?php

namespace App\Services\BooleanSearch;

class TermNode extends AstNode
{
    public string $term;
    public bool $isPhrase;

    public function __construct(string $term, bool $isPhrase = false)
    {
        $this->term = trim($term);
        $this->isPhrase = $isPhrase;
    }

    public function applyToQuery($query): void
    {
        $term = $this->term;
        if ($term === '') {
            return;
        }

        $query->where(function ($sub) use ($term) {
            $sub->where('skills', 'like', "%{$term}%")
                ->orWhere('job_title', 'like', "%{$term}%")
                ->orWhere('name', 'like', "%{$term}%")
                ->orWhere('company_name', 'like', "%{$term}%")
                ->orWhere('location', 'like', "%{$term}%")
                ->orWhere('note', 'like', "%{$term}%");
        });
    }

    public function extractPositiveTerms(array &$terms): void
    {
        if ($this->term !== '' && !in_array($this->term, $terms, true)) {
            $terms[] = $this->term;
        }
    }

    public function toReadableString(): string
    {
        return strpos($this->term, ' ') !== false ? "\"{$this->term}\"" : $this->term;
    }
}
