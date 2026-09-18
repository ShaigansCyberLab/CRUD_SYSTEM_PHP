<?php

/**
 * Static facade over the active DataProvider.
 */
class Data
{
    public static DataProvider $db;

    public static function initialize(DataProvider $provider): void
    {
        self::$db = $provider;
    }

    /**
     * @return GlossaryTerm[]
     */
    public static function get_terms(): array
    {
        return self::$db->get_terms();
    }

    /**
     * @return GlossaryTerm|false
     */
    public static function get_term(int $id)
    {
        return self::$db->get_term($id);
    }

    /**
     * @return GlossaryTerm|false
     */
    public static function get_def(string $definition)
    {
        return self::$db->get_def($definition);
    }

    /**
     * @return GlossaryTerm[]
     */
    public static function search_terms(string $search): array
    {
        return self::$db->search_terms($search);
    }

    public static function add_term(
        string $term,
        string $definition
    ): bool {
        return self::$db->add_term(
            $term,
            $definition
        );
    }

    public static function update_term(
        int $id,
        string $term,
        string $definition
    ): bool {
        return self::$db->update_term(
            $id,
            $term,
            $definition
        );
    }

    public static function delete_term(int $id): bool
    {
        return self::$db->delete_term($id);
    }
}
