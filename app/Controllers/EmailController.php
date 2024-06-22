<?php

namespace App\Controllers;

use App\Models\Email;
use App\Models\Log;

class EmailController
{
    protected Email $emailModel;
    protected Log $logger;

    public function __construct()
    {
        $this->emailModel = new Email();
        $this->logger = new Log();
    }

    public function sendEmail($email, $subject, $message): void
    {
        $this->emailModel->sendEmail($email, $subject, $message);
        $this->logger->createLog('Email sent', date('Y-m-d H:i:s'));
    }

    public function getEmails(): array
    {
        return $this->emailModel->getEmails();
    }

}
