<?php

class MySqlDataProvider extends DataProvider
{
    public function __construct(string $source)
    {
        parent::__construct($source);
    }

    private function connect(): PDO
    {
        return new PDO(
            $this->source,
            CONFIG['db_user'],
            CONFIG['db_password'],
            [
                PDO::ATTR_ERRMODE =>
                    PDO::ERRMODE_EXCEPTION,

                PDO::ATTR_DEFAULT_FETCH_MODE =>
                    PDO::FETCH_CLASS,

                PDO::ATTR_EMULATE_PREPARES =>
                    false,
            ]
        );
    }

    /**
     * @return GlossaryTerm[]
     */
    public function get_terms(): array
    {
        $stmt = $this->connect()->query(
            'SELECT id, term, definition
             FROM terms
             ORDER BY term ASC'
        );

        return $stmt->fetchAll(
            PDO::FETCH_CLASS,
            GlossaryTerm::class
        );
    }

    /**
     * @return GlossaryTerm|false
     */
    public function get_term(int $id)
    {
        $stmt = $this->connect()->prepare(
            'SELECT id, term, definition
             FROM terms
             WHERE id = :id
             LIMIT 1'
        );

        $stmt->execute([
            'id' => $id
        ]);

        $term = $stmt->fetchObject(
            GlossaryTerm::class
        );

        return $term ?: false;
    }

    /**
     * @return GlossaryTerm|false
     */
    public function get_def(string $definition)
    {
        $stmt = $this->connect()->prepare(
            'SELECT id, term, definition
             FROM terms
             WHERE definition LIKE :definition
             LIMIT 1'
        );

        $stmt->execute([
            'definition' => '%' . $definition . '%'
        ]);

        $term = $stmt->fetchObject(
            GlossaryTerm::class
        );

        return $term ?: false;
    }

    /**
     * @return GlossaryTerm[]
     */
    public function search_terms(string $search): array
    {
        $stmt = $this->connect()->prepare(
            'SELECT id, term, definition
             FROM terms
             WHERE term LIKE :search
                OR definition LIKE :search
             ORDER BY term ASC'
        );

        $stmt->execute([
            'search' => '%' . $search . '%'
        ]);

        return $stmt->fetchAll(
            PDO::FETCH_CLASS,
            GlossaryTerm::class
        );
    }

    public function add_term(
        string $term,
        string $definition
    ): bool {
        $stmt = $this->connect()->prepare(
            'INSERT INTO terms
                (term, definition)
             VALUES
                (:term, :definition)'
        );

        return $stmt->execute([
            'term'       => $term,
            'definition' => $definition,
        ]);
    }

    public function update_term(
        int $id,
        string $term,
        string $definition
    ): bool {
        $stmt = $this->connect()->prepare(
            'UPDATE terms
             SET
                term = :term,
                definition = :definition
             WHERE id = :id'
        );

        return $stmt->execute([
            'id'         => $id,
            'term'       => $term,
            'definition' => $definition,
        ]);
    }

    public function delete_term(int $id): bool
    {
        $stmt = $this->connect()->prepare(
            'DELETE FROM terms
             WHERE id = :id'
        );

        return $stmt->execute([
            'id' => $id
        ]);
    }
}
