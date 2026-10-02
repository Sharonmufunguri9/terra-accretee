<?php
return [
    'recipient_email' => getenv('RECIPIENT_EMAIL') ?: 'hello@terra-accrete.com',
    'from_email' => getenv('FROM_EMAIL') ?: 'website@terra-accrete.com',
    'site_name' => getenv('SITE_NAME') ?: 'Terra-accrete Global',
    'smtp_host' => getenv('SMTP_HOST') ?: 'smtp.gmail.com',
    'smtp_port' => getenv('SMTP_PORT') ?: 587,
    'smtp_username' => getenv('SMTP_USERNAME') ?: '',
    'smtp_password' => getenv('SMTP_PASSWORD') ?: '',
    'smtp_encryption' => getenv('SMTP_ENCRYPTION') ?: 'tls',
];
?>