<?php

namespace App\Controllers;

use App\Models\Log;
use App\Models\User;

class UserController
{
    protected User $userModel;
    protected Log $logger;

    public function __construct()
    {
        $this->userModel = new User();
        $this->logger = new Log();
    }

    public function createUser($username, $password): void
    {
        $this->userModel->createUser($username, $password);
        $this->logger->createLog('User created', date('Y-m-d H:i:s'));
    }

    public function getUser($userID): bool|array|null
    {
        return $this->userModel->getUser($userID);
    }

    public function getUserID() {
        return $this->userModel->getUserID();
    }

    public function getUserRole($userID)
    {
        return $this->userModel->getUserRole($userID);
    }

    public function updateUserRole($userID, $role): void
    {
        $this->userModel->updateUserRole($userID, $role);
        $this->logger->createLog('User role updated', date('Y-m-d H:i:s'));
    }

    public function deleteUser(): void
    {
        $this->userModel->deleteUser();
        $this->logger->createLog('User deleted', date('Y-m-d H:i:s'));
    }

    public function getUsers(): array
    {
        return $this->userModel->getUsers();
    }

    public function resetPassword($userID, $password): void
    {
        $this->userModel->resetPassword($userID, $password);
        $this->logger->createLog('Password reset', date('Y-m-d H:i:s'));
    }

    public function login($username, $password): bool
    {
        $this->logger->createLog('User logged in', date('Y-m-d H:i:s'));
        return $this->userModel->login($username, $password);

    }

    public function isLoggedIn(): bool
    {
        return $this->userModel->isLoggedIn();
    }

    public function logout(): void
    {
        $this->userModel->logout();
        $this->logger->createLog('User logged out', date('Y-m-d H:i:s'));
    }

    public function getUsernameByID($user_id): bool|array|null
    {
        return $this->userModel->getUsernameByID($user_id);
    }

}