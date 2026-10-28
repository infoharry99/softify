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
            $sub->whereRaw("COALESCE(skills, '') LIKE ?", ["%{$term}%"])
                ->orWhereRaw("COALESCE(job_title, '') LIKE ?", ["%{$term}%"])
                ->orWhereRaw("COALESCE(name, '') LIKE ?", ["%{$term}%"])
                ->orWhereRaw("COALESCE(company_name, '') LIKE ?", ["%{$term}%"])
                ->orWhereRaw("COALESCE(location, '') LIKE ?", ["%{$term}%"])
                ->orWhereRaw("COALESCE(note, '') LIKE ?", ["%{$term}%"]);
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
