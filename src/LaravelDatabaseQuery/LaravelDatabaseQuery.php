<?php

namespace OskarZborowski\LaravelDatabaseMigrator\LaravelDatabaseQuery;

use Exception;
use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class LaravelDatabaseQuery
{
    private array $allowedClasses = [DB::class, Model::class];

    public function __construct(
        private DB|Connection|QueryBuilder|EloquentBuilder|Collection|array|string|null $query = null,
        private ?string $rawQuery = null,
        private ?array $bindings = null,
        private ?string $connectionName = null,
    ) {
        $this->checkIfQueryIsAllowedInstance();
    }

    public function setQuery(DB|Connection|QueryBuilder|EloquentBuilder|Collection|array|string $query = null): void
    {
        $this->checkIfQueryIsAllowedInstance();

        $this->query = $query;
    }

    public function getQuery(): DB|Connection|QueryBuilder|EloquentBuilder|Collection|array|string|null
    {
        return $this->query;
    }

    public function setRawQuery(string $rawQuery = null): void
    {
        $this->rawQuery = $rawQuery;
    }

    public function getRawQuery(): ?string
    {
        return $this->rawQuery;
    }

    public function setBindings(array $bindings = null): void
    {
        $this->bindings = $bindings;
    }

    public function getBindings(): ?array
    {
        return $this->bindings;
    }

    public function setConnectionName(string $connectionName = null): void
    {
        $this->connectionName = $connectionName;
    }

    public function getConnectionName(): ?string
    {
        return $this->connectionName;
    }

    private function checkIfQueryIsAllowedInstance(): bool
    {
        if ('string' !== gettype($this->query)) {
            return true;
        }

        foreach ($this->allowedClasses as $allowedClass) {
            if (new $this->query instanceof $allowedClass) {
                return true;
            }
        }

        throw new Exception('Do uzupełnienia');
    }
}
