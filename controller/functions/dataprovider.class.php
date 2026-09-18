<?php

require_once __DIR__ . '/GlossaryTerm.class.php';

/**
 * Abstract base class for all data providers.
 */
abstract class DataProvider
{
    public $source;

    public function __construct($source)
    {
        $this->source = $source;
    }

    /**
     * @return GlossaryTerm[]
     */
    abstract public function get_terms(): array;

    /**
     * @return GlossaryTerm|false
     */
    abstract public function get_term(int $id);

    /**
     * @return GlossaryTerm|false
     */
    abstract public function get_def(string $definition);

    /**
     * @return GlossaryTerm[]
     */
    abstract public function search_terms(string $search): array;

    abstract public function add_term(
        string $term,
        string $definition
    ): bool;

    abstract public function update_term(
        int $id,
        string $term,
        string $definition
    ): bool;

    abstract public function delete_term(int $id): bool;
}
