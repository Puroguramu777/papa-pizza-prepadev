<?php

namespace App\Repository;

use App\Model\User;

use Core\Repository\Repository;

class UserRepository extends Repository
{
    public function getTableName(): string
    {
        return 'user';
    }

    public function findUserByEmail(string $email): ?User
    {
        $query = sprintf(
            'SELECT * FROM %s WHERE email = :email',
            $this->getTableName()
        );

        $stmt = $this->pdo->prepare($query);
        if (!$stmt) return null;
        $stmt->execute(['email' => $email]);
        while ($result = $stmt->fetch()) {
            $user = new User($result);
        }
        return $user ?? null;
    }

    public function addUser(array $data): ?User
    {
        $data_more = [
            'is_admin' => 0,
            'is_active' => 1,
        ];
        $data = array_merge($data, $data_more);

        $query = sprintf(
            'INSERT INTO %s (email, password, lastname, firstname, phone, is_admin, is_active)
            VALUES (:email, :password, :lastname, :firstname, :phone, :is_admin, :is_active)',
            $this->getTableName()
        );

        $stmt = $this->pdo->prepare($query);
        if (!$stmt) return null;

        $stmt->execute($data);

        $id = $this->pdo->lastInsertId();
        return $this->readById(User::class, $id);
    }

    public function addTeam(array $data): ?User
    {
        $data_more = [
            'is_admin' => 1,
            'is_active' => 1,
        ];
        $data = array_merge($data, $data_more);

        $query = sprintf(
            'INSERT INTO %s (email, password, lastname, firstname, phone, is_admin, is_active)
            VALUES (:email, :password, :lastname, :firstname, :phone, :is_admin, :is_active)',
            $this->getTableName()
        );

        $stmt = $this->pdo->prepare($query);
        if (!$stmt) return null;

        $stmt->execute($data);

        $id = $this->pdo->lastInsertId();
        return $this->readById(User::class, $id);
    }

    public function findUserbyId(int $id)
    {
        return $this->readById(User::class, $id);
    }

    public function getAllClientActif(): array
    {

        $users = [];

        $query = sprintf(
            'SELECT * FROM %s WHERE is_admin=0 AND is_active=1',
            $this->getTableName()
        );

        $stmt = $this->pdo->query($query);

        if (!$stmt) return $users;

        while ($result = $stmt->fetch()) {
            $users[] = new User($result);
        }

        return $users;
    }

    public function deleteUser(int $id): bool
    {
        $query = sprintf(
            'UPDATE %s SET is_active = 0 WHERE id = :id',
            $this->getTableName()
        );

        $stmt = $this->pdo->prepare($query);

        if (!$stmt) return false;

        return $stmt->execute(['id' => $id]);
    }

    public function getAllAdminActif(): array
    {

        $users = [];

        $query = sprintf(
            'SELECT * FROM %s WHERE is_admin=1 AND is_active=1',
            $this->getTableName()
        );

        $stmt = $this->pdo->query($query);

        if (!$stmt) return $users;

        while ($result = $stmt->fetch()) {
            $users[] = new User($result);
        }

        return $users;
    }

    
}
