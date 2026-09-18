<?php

class FileDataProvider extends DataProvider
{
    public function __construct(string $source)
    {
        parent::__construct($source);
    }

    private function get_data(): array
    {
        if (!is_file($this->source)) {
            return [];
        }

        $content = file_get_contents(
            $this->source
        );

        if (
            $content === false ||
            trim($content) === ''
        ) {
            return [];
        }

        try {
            $data = json_decode(
                $content,
                true,
                512,
                JSON_THROW_ON_ERROR
            );
        } catch (JsonException) {
            return [];
        }

        return is_array($data)
            ? $data
            : [];
    }

    private function set_data(
        array $data
    ): bool {
        try {
            $json = json_encode(
                $data,
                JSON_PRETTY_PRINT |
                JSON_UNESCAPED_UNICODE |
                JSON_UNESCAPED_SLASHES |
                JSON_THROW_ON_ERROR
            );
        } catch (JsonException) {
            return false;
        }

        $directory = dirname($this->source);

        $temporary_file = tempnam(
            $directory,
            '.glossary-'
        );

        if ($temporary_file === false) {
            return false;
        }

        try {
            $bytes = file_put_contents(
                $temporary_file,
                $json,
                LOCK_EX
            );

            if ($bytes === false) {
                return false;
            }

            @chmod(
                $temporary_file,
                0600
            );

            if (!rename(
                $temporary_file,
                $this->source
            )) {
                return false;
            }

            return true;
        } finally {
            if (is_file($temporary_file)) {
                @unlink($temporary_file);
            }
        }
    }

    private function make_term(
        array $data
    ): GlossaryTerm {
        $term = new GlossaryTerm();

        $term->id =
            (int) ($data['id'] ?? 0);

        $term->term =
            (string) ($data['term'] ?? '');

        $term->definition =
            (string) ($data['definition'] ?? '');

        return $term;
    }

    /**
     * @return GlossaryTerm[]
     */
    public function get_terms(): array
    {
        return array_map(
            fn(array $item) =>
                $this->make_term($item),
            $this->get_data()
        );
    }

    /**
     * @return GlossaryTerm|false
     */
    public function get_term(int $id)
    {
        foreach ($this->get_data() as $item) {
            if (
                (int) ($item['id'] ?? 0) === $id
            ) {
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
            $item_definition =
                (string) (
                    $item['definition'] ?? ''
                );

            if (
                mb_stripos(
                    $item_definition,
                    $definition,
                    0,
                    'UTF-8'
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
    public function search_terms(
        string $search
    ): array {
        $results = [];

        foreach ($this->get_data() as $item) {
            $term =
                (string) ($item['term'] ?? '');

            $definition =
                (string) (
                    $item['definition'] ?? ''
                );

            if (
                mb_stripos(
                    $term,
                    $search,
                    0,
                    'UTF-8'
                ) !== false ||
                mb_stripos(
                    $definition,
                    $search,
                    0,
                    'UTF-8'
                ) !== false
            ) {
                $results[] =
                    $this->make_term($item);
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
                    array_column(
                        $data,
                        'id'
                    )
                )
            ) + 1;

        $data[] = [
            'id' => $new_id,
            'term' => $term,
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
            if (
                (int) ($item['id'] ?? 0) === $id
            ) {
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

    public function delete_term(
        int $id
    ): bool {
        $data = $this->get_data();

        $filtered = array_values(
            array_filter(
                $data,
                fn(array $item) =>
                    (int) (
                        $item['id'] ?? 0
                    ) !== $id
            )
        );

        if (
            count($filtered) ===
            count($data)
        ) {
            return false;
        }

        return $this->set_data($filtered);
    }
}
