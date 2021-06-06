<?php

namespace App\Classes;

abstract class MigrationData
{
    public abstract function getModel(): string;

    public abstract function getEntities(): array;
}
