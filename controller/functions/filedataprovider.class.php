<?php

class FileDataProvider extends DataProvider
{
    public function __construct($source)
    {
        parent::__construct($source);
    }

    private function get_data(): array
    {
        if (!file_exists($this->source)) {
            return [];
        }

        $content = file_get_contents($this->source);

        if ($content === false || trim($content) === '') {
            return [];
        }

        $data = json_decode($content, true);

        return is_array($data) ? $data : [];
    }

    private function set_data(array $data): bool
    {
        $result = file_put_contents(
            $this->source,
            json_encode(
                $data,
                JSON_PRETTY_PRINT |
                JSON_UNESCAPED_UNICODE |
                JSON_UNESCAPED_SLASHES
            ),
            LOCK_EX
        );

        return $result !== false;
    }

    private function make_term(array $data): GlossaryTerm
    {
        $term = new GlossaryTerm();

        $term->id = (int) ($data['id'] ?? 0);
        $term->term = (string) ($data['term'] ?? '');
        $term->definition = (string) ($data['definition'] ?? '');

        return $term;
    }

    /**
     * @return GlossaryTerm[]
     */
    public function get_terms(): array
    {
        return array_map(
            fn(array $item) => $this->make_term($item),
            $this->get_data()
        );
    }

    /**
     * @return GlossaryTerm|false
     */
    public function get_term(int $id)
    {
        foreach ($this->get_data() as $item) {

            if ((int) ($item['id'] ?? 0) === $id) {
                return $this->make_term($item);
            }
        }

        return false;
    }

    /**
     * @return GlossaryTerm|false
     */
    public function get_def(string $definition)
    {
        foreach ($this->get_data() as $item) {

            $itemDefinition =
                (string) ($item['definition'] ?? '');

            if (
                stripos(
                    $itemDefinition,
                    $definition
                ) !== false
            ) {
                return $this->make_term($item);
            }
        }

        return false;
    }

    /**
     * @return GlossaryTerm[]
     */
    public function search_terms(string $search): array
    {
        $results = [];

        foreach ($this->get_data() as $item) {

            $term = (string) ($item['term'] ?? '');
            $definition = (string) ($item['definition'] ?? '');

            if (
                stripos($term, $search) !== false ||
                stripos($definition, $search) !== false
            ) {
                $results[] = $this->make_term($item);
            }
        }

        return $results;
    }

    public function add_term(
        string $term,
        string $definition
    ): bool {
        $data = $this->get_data();

        $new_id = empty($data)
            ? 1
            : max(
                array_map(
                    'intval',
                    array_column($data, 'id')
                )
            ) + 1;

        $data[] = [
            'id'         => $new_id,
            'term'       => $term,
            'definition' => $definition,
        ];

        return $this->set_data($data);
    }

    public function update_term(
        int $id,
        string $term,
        string $definition
    ): bool {
        $data = $this->get_data();
        $found = false;

        foreach ($data as &$item) {

            if ((int) ($item['id'] ?? 0) === $id) {

                $item['term'] = $term;
                $item['definition'] = $definition;

                $found = true;
                break;
            }
        }

        unset($item);

        if (!$found) {
            return false;
        }

        return $this->set_data($data);
    }

    public function delete_term(int $id): bool
    {
        $data = $this->get_data();

        $filtered = array_values(
            array_filter(
                $data,
                fn(array $item) =>
                    (int) ($item['id'] ?? 0) !== $id
            )
        );

        if (count($filtered) === count($data)) {
            return false;
        }

        return $this->set_data($filtered);
    }
}
