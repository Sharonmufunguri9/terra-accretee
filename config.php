<?php
return [
    'recipient_email' => getenv('RECIPIENT_EMAIL') ?: 'hello@terra-accrete.com',
    'from_email' => getenv('FROM_EMAIL') ?: 'website@terra-accrete.com',
    'site_name' => getenv('SITE_NAME') ?: 'Terra-accrete Global',
];
?>