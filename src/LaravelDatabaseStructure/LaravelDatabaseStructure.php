<?php

namespace OskarZborowski\LaravelDatabaseMigrator\LaravelDatabaseStructure;

use Exception;
use OskarZborowski\LaravelDatabaseMigrator\LaravelDatabaseMapper\LaravelDatabaseMapper;

class LaravelDatabaseStructure
{
    public function __construct(
        private string $object,
        private array $mappers,
    ) {
        $this->checkMappers();
    }

    public function setObject(string $object): void
    {
        $this->object = $object;
    }

    public function getObject(): string
    {
        return $this->object;
    }

    public function setMappers(array $mappers): void
    {
        $this->mappers = $mappers;

        $this->checkMappers();
    }

    public function addMappers(array $mappers): void
    {
        $this->setMappers(array_merge($this->getMappers(), $mappers));
        $this->checkMappers();
    }

    public function getMappers(): array
    {
        return $this->mappers;
    }

    private function checkMappers(): bool
    {
        foreach ($this->getMappers() as $mapper) {
            if (! $mapper instanceof LaravelDatabaseMapper) {
                throw new Exception('Do uzupełnienia');
            }
        }

        return true;
    }
}
