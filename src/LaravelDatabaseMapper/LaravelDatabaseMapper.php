<?php

namespace OskarZborowski\LaravelDatabaseMigrator\LaravelDatabaseMapper;

use OskarZborowski\LaravelDatabaseMigrator\LaravelDatabaseQuery\LaravelDatabaseQuery;

class LaravelDatabaseMapper
{
    public function __construct(
        private string $targetColumn,
        private ?string $sourceColumn = null,
        private LaravelDatabaseQuery|string|null $query = null,
        // TODO Formatowanie pola
    ) {}

    public function setTargetColumn(string $targetColumn): void
    {
        $this->targetColumn = $targetColumn;
    }

    public function getTargetColumn(): string
    {
        return $this->targetColumn;
    }

    public function setSourceColumn(string $sourceColumn = null): void
    {
        $this->sourceColumn = $sourceColumn;
    }

    public function getSourceColumn(): ?string
    {
        return $this->sourceColumn;
    }

    public function clearSourceColumn(): void
    {
        $this->setSourceColumn();
    }

    public function setQuery(LaravelDatabaseQuery|string $query = null): void
    {
        $this->query = $query;
    }

    public function getQuery(): LaravelDatabaseQuery|string|null
    {
        return $this->query;
    }

    public function clearQuery(): void
    {
        $this->setQuery();
    }
}
