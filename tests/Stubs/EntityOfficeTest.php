<?php

declare(strict_types=1);

namespace Shopie\Mongoer\Tests\Stubs;

use DateTime;
use MongoDB\BSON\ObjectId;
use Shopie\Mongoer\Attributes\DocumentIndexKey;
use Shopie\Mongoer\Attributes\DocumentKey;
use Shopie\Mongoer\Entity;

class EntityOfficeTest extends Entity
{
    #[DocumentIndexKey]
    private ObjectId $id;

    #[DocumentKey('api_id')]
    private int $apiId;

    #[DocumentKey('user_id')]
    private ObjectId $userId;

    #[DocumentKey]
    private string $name;

    #[DocumentKey]
    private string $email;

    #[DocumentKey]
    private array $phones;

    #[DocumentKey('is_active')]
    private bool $isActive;

    #[DocumentKey('created_at')]
    private DateTime $createdAt;
    
    public function __construct()
    {
    }

    //==============
    // GETTERS
    //==============

    public function id(): ObjectId
    {
        return $this->id;
    }

    public function apiId(): int
    {
        return $this->apiId;
    }

    public function userId(): ObjectId
    {
        return $this->userId;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function phones(): array
    {
        return $this->phones;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function createdAt(): DateTime
    {
        return $this->createdAt;
    }

    //==============
    // SETTERS
    //==============

    public function setId(ObjectId $id): void
    {
        $this->id = $id;
    }

    public function setApiId(int $apiId): void
    {
        $this->apiId = $apiId;
    }

    public function setUserId(ObjectId $userId): void
    {
        $this->userId = $userId;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setPhones(array $phones): void
    {
        $this->phones = $phones;
    }

    public function setIsActive(bool $isActive): void
    {
        $this->isActive = $isActive;
    }

    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}