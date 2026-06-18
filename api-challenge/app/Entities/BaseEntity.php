<?php

namespace App\Entities;

use DateTime;

class BaseEntity
{
    public ?string $createdBy = null;
    public ?DateTime $createdAt = null;
    public ?string $updatedBy = null;
    public ?DateTime $updatedAt = null;
    public ?string $deletedBy = null;
    public ?DateTime $deletedAt = null;
}