<?php

namespace OskarZborowski\LaravelDatabaseMigrator;

use Exception;
use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use OskarZborowski\LaravelDatabaseMigrator\LaravelDatabaseStructure\LaravelDatabaseStructure;

class LaravelDatabaseMigrator
{
    private array $allowedClasses = [DB::class, Model::class];

    private ?Collection $defaultCollection;

    private ?array $defaultRecordsArray;

    public function __construct(
        private array $structures,
        private ?string $defaultObject = null,
        private ?array $defaultMappers = null,
        private ?bool $defaultIsSavedOnce = null,
        private ?string $defaultCreateMethod = null,
        private ?string $defaultUpdateMethod = null,
        private ?string $defaultDeleteMethod = null,
        private ?bool $defaultIsRemovedIfNotExist = null,
        private ?string $defaultRemovalColumn = null,
        private ?string $defaultSoftDeleteColumn = null,
        private DB|Connection|QueryBuilder|EloquentBuilder|Collection|array|string|null $defaultQuery = null,
        private ?string $defaultRawQuery = null,
        private ?array $defaultBindings = null,
        private ?string $defaultConnectionName = null,
        private ?string $defaultRelatedId = null,
        private ?string $defaultForeignKey = null,
        private ?string $defaultLocalKey = null,
    ) {
        $this->checkIfStructuresAreAllowedInstances();
    }

    public function setStructures(array $structures): void
    {
        $this->structures = $structures;

        $this->checkIfStructuresAreAllowedInstances();
    }

    public function addStructures(array $structures): void
    {
        $this->setStructures(array_merge($this->getStructures(), $structures));
        $this->checkIfStructuresAreAllowedInstances();
    }

    public function getStructures(): array
    {
        return $this->structures;
    }

    private function checkIfStructuresAreAllowedInstances(): bool
    {
        foreach ($this->getStructures() as $structure) {
            if (! $structure instanceof LaravelDatabaseStructure) {
                throw new Exception('Do uzupełnienia');
            }
        }

        return true;
    }
}
