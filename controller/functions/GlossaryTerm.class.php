<?php

/**
 * Simple value object populated by PDO::FETCH_CLASS.
 */
class GlossaryTerm
{
    public int $id = 0;

    public string $term = '';

    public string $definition = '';
}
