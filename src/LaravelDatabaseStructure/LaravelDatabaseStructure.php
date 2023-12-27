<?php

namespace OskarZborowski\LaravelDatabaseMigrator\LaravelDatabaseStructure;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use OskarZborowski\LaravelDatabaseMigrator\LaravelDatabaseMapper\LaravelDatabaseMapper;

class LaravelDatabaseStructure
{
    private array $allowedClasses = [DB::class, Model::class];

    public function __construct(
        private string $object,
        private array $mappers,
        // TODO Flaga z informacją czy funkcja zapisująca jest zapisywaniem masowym
        // private ?string $createMethod = null,
        // private ?string $updateMethod = null,
        // private ?string $deleteMethod = null,
        // TODO Flaga z określeniem czy brak elementów ma usunąć elementy
        // TODO Pole z nazwą kolumny po znalezieniu którego element zostanie usunięty
        // TODO Pole z nazwą kolumny do miękkiego usuwania
        private ?string $defaultId = null,
        // private DB|Connection|QueryBuilder|EloquentBuilder|Collection|array|string|null $defaultQuery = null,
        private ?array $defaultBindings = null,
        private ?string $defaultConnectionName = null,
        private ?string $defaultLocalKey = null,
    ) {
        $this->checkIfObjectIsAllowedInstance();
        $this->checkIfMappersAreAllowedInstances();
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

    public function setDefaultId(string $defaultId = null): void
    {
        $this->defaultId = $defaultId;
    }

    public function getDefaultId(): ?string
    {
        return $this->defaultId;
    }

    public function clearDefaultId(): void
    {
        $this->setDefaultId();
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
