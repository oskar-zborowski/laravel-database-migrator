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

    private ?Collection $collection;

    private ?array $recordsArray;

    public function __construct(
        private ?string $id = null,
        private DB|Connection|QueryBuilder|EloquentBuilder|Collection|array|string|null $query = null,
        private ?string $rawQuery = null,
        private ?array $bindings = null,
        private ?string $connectionName = null,
        private ?string $relatedId = null,
        private ?string $foreignKey = null,
        private ?string $localKey = null,
    ) {
        $this->checkIfQueryIsAllowedInstance();
        $this->checkIfConnectionIsCorrect();
    }

    public function setId(string $id = null): void
    {
        $this->id = $id;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function clearId(): void
    {
        $this->setId();
    }

    public function setQuery(DB|Connection|QueryBuilder|EloquentBuilder|Collection|array|string $query = null): void
    {
        $this->query = $query;

        $this->checkIfQueryIsAllowedInstance();
    }

    public function getQuery(): DB|Connection|QueryBuilder|EloquentBuilder|Collection|array|string|null
    {
        return $this->query;
    }

    public function clearQuery(): void
    {
        $this->setQuery();
    }

    public function setRawQuery(string $rawQuery = null): void
    {
        $this->rawQuery = $rawQuery;
    }

    public function getRawQuery(): ?string
    {
        return $this->rawQuery;
    }

    public function clearRawQuery(): void
    {
        $this->setRawQuery();
    }

    public function setBindings(array $bindings = null): void
    {
        $this->bindings = $bindings;
    }

    public function addBindings(array $bindings): void
    {
        $this->setBindings(array_merge($this->getBindings() ?? [], $bindings));
    }

    public function getBindings(): ?array
    {
        return $this->bindings;
    }

    public function clearBindings(): void
    {
        $this->setBindings();
    }

    public function setConnectionName(string $connectionName = null): void
    {
        $this->connectionName = $connectionName;

        $this->checkIfConnectionIsCorrect();
    }

    public function getConnectionName(): ?string
    {
        return $this->connectionName;
    }

    public function clearConnectionName(): void
    {
        $this->setConnectionName();
    }

    public function setRelatedId(string $relatedId = null): void
    {
        $this->relatedId = $relatedId;
    }

    public function getRelatedId(): ?string
    {
        return $this->relatedId;
    }

    public function clearRelatedId(): void
    {
        $this->setRelatedId();
    }

    public function setForeignKey(string $foreignKey = null): void
    {
        $this->foreignKey = $foreignKey;
    }

    public function getForeignKey(): ?string
    {
        return $this->foreignKey;
    }

    public function clearForeignKey(): void
    {
        $this->setForeignKey();
    }

    public function setLocalKey(string $localKey = null): void
    {
        $this->localKey = $localKey;
    }

    public function getLocalKey(): ?string
    {
        return $this->localKey;
    }

    public function clearLocalKey(): void
    {
        $this->setLocalKey();
    }

    public function setCollection(Collection $collection = null): void
    {
        $this->collection = $collection;
    }

    public function addCollection(Collection $collection): void
    {
        $existingCollection = $this->getCollection();

        $existingCollection
            ? $this->setCollection($existingCollection->merge($collection))
            : $this->setCollection($collection);
    }

    public function getCollection(): ?Collection
    {
        return $this->collection;
    }

    public function clearCollection(): void
    {
        $this->setCollection();
    }

    public function setRecordsArray(array $recordsArray = null): void
    {
        $this->recordsArray = $recordsArray;
    }

    public function addRecordsArray(array $recordsArray): void
    {
        $this->setRecordsArray(array_merge($this->getRecordsArray() ?? [], $recordsArray));
    }

    public function getRecordsArray(): ?array
    {
        return $this->recordsArray;
    }

    public function clearRecordsArray(): void
    {
        $this->setRecordsArray();
    }

    public function getAllowedClasses(): array
    {
        return $this->allowedClasses;
    }

    private function checkIfQueryIsAllowedInstance(): bool
    {
        $query = $this->getQuery();

        if ('string' !== gettype($query) || $query === '[]') {
            return true;
        }

        foreach ($this->getAllowedClasses() as $allowedClass) {
            if (new $query instanceof $allowedClass) {
                return true;
            }
        }

        throw new Exception('Do uzupełnienia');
    }

    private function checkIfConnectionIsCorrect(): bool
    {
        if (null === $this->getConnectionName() ||
            DB::connection($this->getConnectionName())->getPdo()
        ) {
            return true;
        }

        throw new Exception('Do uzupełnienia');
    }
}
