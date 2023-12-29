<?php

namespace OskarZborowski\LaravelDatabaseMigrator\LaravelDatabaseStructure;

use Exception;
use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use OskarZborowski\LaravelDatabaseMigrator\LaravelDatabaseMapper\LaravelDatabaseMapper;

class LaravelDatabaseStructure
{
    private array $allowedClasses = [DB::class, Model::class];

    private ?Collection $defaultCollection;

    private ?array $defaultRecordsArray;

    public function __construct(
        private string $object,
        private array $mappers,
        private ?bool $isSavedOnce = null,
        private ?string $createMethod = null,
        private ?string $updateMethod = null,
        private ?string $deleteMethod = null,
        private ?bool $isRemovedIfNotExist = null,
        private ?string $removalColumn = null,
        private ?string $softDeleteColumn = null,
        private DB|Connection|QueryBuilder|EloquentBuilder|Collection|array|string|null $defaultQuery = null,
        private ?string $defaultRawQuery = null,
        private ?array $defaultBindings = null,
        private ?string $defaultConnectionName = null,
        private ?string $defaultRelatedId = null,
        private ?string $defaultForeignKey = null,
        private ?string $defaultLocalKey = null,
    ) {
        $this->checkIfObjectIsAllowedInstance();
        $this->checkIfMappersAreAllowedInstances();
        $this->replaceDefaultQueryArrayStringWithArray();
        $this->checkIfDefaultQueryIsAllowedInstance();
        $this->checkIfDefaultConnectionIsCorrect();
    }

    public function setObject(string $object): void
    {
        $this->object = $object;

        $this->checkIfObjectIsAllowedInstance();
    }

    public function getObject(): string
    {
        return $this->object;
    }

    public function setMappers(array $mappers): void
    {
        $this->mappers = $mappers;

        $this->checkIfMappersAreAllowedInstances();
    }

    public function addMappers(array $mappers): void
    {
        $this->setMappers(array_merge($this->getMappers(), $mappers));
        $this->checkIfMappersAreAllowedInstances();
    }

    public function getMappers(): array
    {
        return $this->mappers;
    }

    public function setIsSavedOnce(bool $isSavedOnce = null): void
    {
        $this->isSavedOnce = $isSavedOnce;
    }

    public function getIsSavedOnce(): ?bool
    {
        return $this->isSavedOnce;
    }

    public function clearIsSavedOnce(): void
    {
        $this->setIsSavedOnce();
    }

    public function setCreateMethod(string $createMethod = null): void
    {
        $this->createMethod = $createMethod;
    }

    public function getCreateMethod(): ?string
    {
        return $this->createMethod;
    }

    public function clearCreateMethod(): void
    {
        $this->setCreateMethod();
    }

    public function setUpdateMethod(string $updateMethod = null): void
    {
        $this->updateMethod = $updateMethod;
    }

    public function getUpdateMethod(): ?string
    {
        return $this->updateMethod;
    }

    public function clearUpdateMethod(): void
    {
        $this->setUpdateMethod();
    }

    public function setDeleteMethod(string $deleteMethod = null): void
    {
        $this->deleteMethod = $deleteMethod;
    }

    public function getDeleteMethod(): ?string
    {
        return $this->deleteMethod;
    }

    public function clearDeleteMethod(): void
    {
        $this->setDeleteMethod();
    }

    public function setIsRemovedIfNotExist(bool $isRemovedIfNotExist = null): void
    {
        $this->isRemovedIfNotExist = $isRemovedIfNotExist;
    }

    public function getIsRemovedIfNotExist(): ?bool
    {
        return $this->isRemovedIfNotExist;
    }

    public function clearIsRemovedIfNotExist(): void
    {
        $this->setIsRemovedIfNotExist();
    }

    public function setRemovalColumn(string $removalColumn = null): void
    {
        $this->removalColumn = $removalColumn;
    }

    public function getRemovalColumn(): ?string
    {
        return $this->removalColumn;
    }

    public function clearRemovalColumn(): void
    {
        $this->setRemovalColumn();
    }

    public function setSoftDeleteColumn(string $softDeleteColumn = null): void
    {
        $this->softDeleteColumn = $softDeleteColumn;
    }

    public function getSoftDeleteColumn(): ?string
    {
        return $this->softDeleteColumn;
    }

    public function clearSoftDeleteColumn(): void
    {
        $this->setSoftDeleteColumn();
    }

    public function setDefaultQuery(DB|Connection|QueryBuilder|EloquentBuilder|Collection|array|string $defaultQuery = null): void
    {
        $this->defaultQuery = $defaultQuery;

        $this->replaceDefaultQueryArrayStringWithArray();
        $this->checkIfDefaultQueryIsAllowedInstance();
    }

    public function getDefaultQuery(): DB|Connection|QueryBuilder|EloquentBuilder|Collection|array|string|null
    {
        return $this->defaultQuery;
    }

    public function clearDefaultQuery(): void
    {
        $this->setDefaultQuery();
    }

    public function setDefaultRawQuery(string $defaultRawQuery = null): void
    {
        $this->defaultRawQuery = $defaultRawQuery;
    }

    public function getDefaultRawQuery(): ?string
    {
        return $this->defaultRawQuery;
    }

    public function clearDefaultRawQuery(): void
    {
        $this->setDefaultRawQuery();
    }

    public function setDefaultBindings(array $defaultBindings = null): void
    {
        $this->defaultBindings = $defaultBindings;
    }

    public function addDefaultBindings(array $defaultBindings): void
    {
        $this->setDefaultBindings(array_merge($this->getDefaultBindings() ?? [], $defaultBindings));
    }

    public function getDefaultBindings(): ?array
    {
        return $this->defaultBindings;
    }

    public function clearDefaultBindings(): void
    {
        $this->setDefaultBindings();
    }

    public function setDefaultConnectionName(string $defaultConnectionName = null): void
    {
        $this->defaultConnectionName = $defaultConnectionName;

        $this->checkIfDefaultConnectionIsCorrect();
    }

    public function getDefaultConnectionName(): ?string
    {
        return $this->defaultConnectionName;
    }

    public function clearDefaultConnectionName(): void
    {
        $this->setDefaultConnectionName();
    }

    public function setDefaultRelatedId(string $defaultRelatedId = null): void
    {
        $this->defaultRelatedId = $defaultRelatedId;
    }

    public function getDefaultRelatedId(): ?string
    {
        return $this->defaultRelatedId;
    }

    public function clearDefaultRelatedId(): void
    {
        $this->setDefaultRelatedId();
    }

    public function setDefaultForeignKey(string $defaultForeignKey = null): void
    {
        $this->defaultForeignKey = $defaultForeignKey;
    }

    public function getDefaultForeignKey(): ?string
    {
        return $this->defaultForeignKey;
    }

    public function clearDefaultForeignKey(): void
    {
        $this->setDefaultForeignKey();
    }

    public function setDefaultLocalKey(string $defaultLocalKey = null): void
    {
        $this->defaultLocalKey = $defaultLocalKey;
    }

    public function getDefaultLocalKey(): ?string
    {
        return $this->defaultLocalKey;
    }

    public function clearDefaultLocalKey(): void
    {
        $this->setDefaultLocalKey();
    }

    public function setDefaultCollection(Collection $defaultCollection = null): void
    {
        $this->defaultCollection = $defaultCollection;
    }

    public function addDefaultCollection(Collection $defaultCollection): void
    {
        $existingDefaultCollection = $this->getDefaultCollection();

        $existingDefaultCollection
            ? $this->setDefaultCollection($existingDefaultCollection->merge($defaultCollection))
            : $this->setDefaultCollection($defaultCollection);
    }

    public function getDefaultCollection(): ?Collection
    {
        return $this->defaultCollection;
    }

    public function clearDefaultCollection(): void
    {
        $this->setDefaultCollection();
    }

    public function setDefaultRecordsArray(array $defaultRecordsArray = null): void
    {
        $this->defaultRecordsArray = $defaultRecordsArray;
    }

    public function adddefaultRecordsArray(array $defaultRecordsArray): void
    {
        $this->setDefaultRecordsArray(array_merge($this->getDefaultRecordsArray() ?? [], $defaultRecordsArray));
    }

    public function getDefaultRecordsArray(): ?array
    {
        return $this->defaultRecordsArray;
    }

    public function clearDefaultRecordsArray(): void
    {
        $this->setDefaultRecordsArray();
    }

    public function getAllowedClasses(): array
    {
        return $this->allowedClasses;
    }

    private function checkIfObjectIsAllowedInstance(): bool
    {
        $object = $this->getObject();

        foreach ($this->getAllowedClasses() as $allowedClass) {
            if (new $object instanceof $allowedClass) {
                return true;
            }
        }

        throw new Exception('Do uzupełnienia');
    }

    private function checkIfMappersAreAllowedInstances(): bool
    {
        foreach ($this->getMappers() as $mapper) {
            if (! $mapper instanceof LaravelDatabaseMapper) {
                throw new Exception('Do uzupełnienia');
            }
        }

        return true;
    }

    private function replaceDefaultQueryArrayStringWithArray(): void
    {
        if ($this->getDefaultQuery() === '[]') {
            $this->setDefaultQuery([]);
        }
    }

    private function checkIfDefaultQueryIsAllowedInstance(): bool
    {
        $query = $this->getDefaultQuery();

        if ('string' !== gettype($query)) {
            return true;
        }

        foreach ($this->getAllowedClasses() as $allowedClass) {
            if (new $query instanceof $allowedClass) {
                return true;
            }
        }

        throw new Exception('Do uzupełnienia');
    }

    private function checkIfDefaultConnectionIsCorrect(): bool
    {
        if (null === $this->getDefaultConnectionName() ||
            DB::connection($this->getDefaultConnectionName())->getPdo()
        ) {
            return true;
        }

        throw new Exception('Do uzupełnienia');
    }
}
