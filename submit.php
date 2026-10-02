<?php
$config = require __DIR__ . '/config.php';

if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require __DIR__ . '/vendor/autoload.php';
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function clean($v): string {
    return trim(str_replace(["\r", "\n"], ' ', (string)$v));
}
function field($key): string {
    return isset($_POST[$key]) ? trim((string)$_POST[$key]) : '';
}
function sendMessage(array $config, string $to, string $subject, string $body, string $fromEmail, string $siteName, string $replyTo = ''): bool {
    $smtpHost = getenv('SMTP_HOST') ?: ($config['smtp_host'] ?? '');
    $smtpPort = getenv('SMTP_PORT') ?: ($config['smtp_port'] ?? 587);
    $smtpUsername = getenv('SMTP_USERNAME') ?: ($config['smtp_username'] ?? '');
    $smtpPassword = getenv('SMTP_PASSWORD') ?: ($config['smtp_password'] ?? '');
    $smtpEncryption = getenv('SMTP_ENCRYPTION') ?: ($config['smtp_encryption'] ?? 'tls');

    if (!empty($smtpHost) && !empty($smtpUsername) && !empty($smtpPassword)) {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = $smtpHost;
            $mail->SMTPAuth = true;
            $mail->Username = $smtpUsername;
            $mail->Password = $smtpPassword;
            $mail->SMTPSecure = $smtpEncryption;
            $mail->Port = (int) $smtpPort;
            $mail->CharSet = 'UTF-8';
            $mail->setFrom($fromEmail, $siteName);
            $mail->addAddress($to);
            if ($replyTo !== '') {
                $mail->addReplyTo($replyTo);
            }
            $mail->Subject = $subject;
            $mail->Body = $body;
            $mail->AltBody = strip_tags(str_replace(["\r\n", "\r", "\n"], "\n", $body));
            return $mail->send();
        } catch (Exception $e) {
            error_log('PHPMailer error: ' . $e->getMessage());
            return false;
        }
    }

    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    $headers .= "From: " . $siteName . " <" . $fromEmail . ">\r\n";
    if ($replyTo !== '') {
        $headers .= "Reply-To: " . $replyTo . "\r\n";
    }

    return mail($to, $subject, $body, $headers);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method not allowed.');
}

$type = clean(field('form_type'));
$email = clean(field('email'));
$name = clean(field('name'));

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(422);
    exit('Please provide a valid email address.');
}
if (!in_array($type, ['Investor Access', 'Project Submission'], true)) {
    http_response_code(422);
    exit('Invalid form type.');
}

$recipient = $config['recipient_email'];
$fromEmail = $config['from_email'];
$siteName = $config['site_name'];

if ($type === 'Investor Access') {
    $subject = 'Terra-accrete — Investor Access Request';
    $body = "New investor access request\n\n"
          . "Name: " . clean($name) . "\n"
          . "Institution: " . clean(field('institution')) . "\n"
          . "Email: " . $email . "\n"
          . "Investment focus: " . clean(field('investment_focus')) . "\n";
} else {
    $subject = 'Terra-accrete — New Mining Project Submission';
    $body = "New mining project submission\n\n"
          . "Project: " . clean(field('project_name')) . "\n"
          . "Commodity: " . clean(field('commodity')) . "\n"
          . "Jurisdiction: " . clean(field('jurisdiction')) . "\n"
          . "Capital required: " . clean(field('capital_required')) . "\n"
          . "Contact: " . clean($name) . "\n"
          . "Email: " . $email . "\n\n"
          . "Overview:\n" . trim(field('overview')) . "\n";
}

if (!sendMessage($config, $recipient, $subject, $body, $fromEmail, $siteName, $email)) {
    http_response_code(500);
    exit('The server could not send the email. Configure the hosting mail transport or SMTP.');
}

$confirmationSubject = 'We received your Terra-accrete enquiry';
$confirmationBody = "Dear " . clean($name) . ",\n\n"
    . "Thank you for contacting " . $siteName . ".\n\n"
    . "We have received your " . strtolower($type) . " and will follow up using this email address.\n\n"
    . "Regards,\nTerra-accrete Global";

sendMessage($config, $email, $confirmationSubject, $confirmationBody, $fromEmail, $siteName, '');

header('Location: thank-you.html');
exit;
?>