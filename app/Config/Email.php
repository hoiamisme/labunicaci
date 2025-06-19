<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Email extends BaseConfig
{
    public string $fromEmail = 'faliefwaluyo@gmail.com';
    public string $fromName = 'Labunica System';
    public string $recipients = '';

    /**
     * Protocol untuk mengirim email
     */
    public string $protocol = 'smtp';

    /**
     * SMTP Configuration untuk Gmail
     */
    public string $SMTPHost = 'smtp.gmail.com';
    public string $SMTPUser = 'faliefwaluyo@gmail.com';
    public string $SMTPPass = 'uosl xcfb hfio dsjx';
    public int $SMTPPort = 587;
    public string $SMTPCrypto = 'tls';
    public bool $SMTPAuth = true;
    public int $SMTPTimeout = 60;
    
    /**
     * Email format
     */
    public string $mailType = 'html';
    public string $charset = 'UTF-8';
    public bool $validate = true;
    public int $priority = 3;
    public string $CRLF = "\r\n";
    public string $newline = "\r\n";
    public bool $BCCBatchMode = false;
    public int $BCCBatchSize = 200;
    public bool $DSN = false;
}
