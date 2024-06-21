<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Log;

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
        $this->logger->createLog('User created', time());
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
        $this->logger->createLog('User role updated', time());
    }

    public function deleteUser()
    {
        $this->userModel->deleteUser();
        $this->logger->createLog('User deleted', time());
    }

    public function getUsers()
    {
        return $this->userModel->getUsers();
    }

    public function resetPassword($userID, $password)
    {
        $this->userModel->resetPassword($userID, $password);
        $this->logger->createLog('Password reset', time());
    }

    public function login($username, $password)
    {
        $this->userModel->login($username, $password);
        $this->logger->createLog('User logged in', time());
    }

    public function logout()
    {
        $this->userModel->logout();
        $this->logger->createLog('User logged out', time());
    }

}