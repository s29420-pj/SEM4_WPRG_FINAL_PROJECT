<?php

namespace App\Controllers;

use App\Models\Log;
use App\Models\User;

class UserController
{
    protected $userModel;
    protected $logger;

    public function __construct()
    {
        $this->userModel = new User();
        $this->logger = new Log();
    }

    public function createUser($username, $password)
    {
        $this->userModel->createUser($username, $password);
        $this->logger->createLog('User created', date('Y-m-d H:i:s'));
    }

    public function getUser($userID)
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

    public function updateUserRole($userID, $role)
    {
        $this->userModel->updateUserRole($userID, $role);
        $this->logger->createLog('User role updated', date('Y-m-d H:i:s'));
    }

    public function deleteUser()
    {
        $this->userModel->deleteUser();
        $this->logger->createLog('User deleted', date('Y-m-d H:i:s'));
    }

    public function getUsers()
    {
        return $this->userModel->getUsers();
    }

    public function resetPassword($userID, $password)
    {
        $this->userModel->resetPassword($userID, $password);
        $this->logger->createLog('Password reset', date('Y-m-d H:i:s'));
    }

    public function login($username, $password): bool
    {
        $this->logger->createLog('User logged in', date('Y-m-d H:i:s'));
        return $this->userModel->login($username, $password);

    }

    public function isLoggedIn()
    {
        return $this->userModel->isLoggedIn();
    }

    public function logout()
    {
        $this->userModel->logout();
        $this->logger->createLog('User logged out', date('Y-m-d H:i:s'));
    }

}