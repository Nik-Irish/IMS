<?php
function sendOtpMail($email, $otp)
{
    // Admin sender configuration
    $fromEmail = 'itsnikrish@gmail.com';
    $fromName = 'IMS Nepal';
    $subject = 'IMS Nepal - Password Reset OTP';

    $to = trim((string)$email);
    if (!filter_var($to, FILTER_VALIDATE_EMAIL)) {
        error_log('Email sending failed: invalid recipient address.');
        return false;
    }

    $safeOtp = htmlspecialchars((string)$otp, ENT_QUOTES, 'UTF-8');

    // HTML email body
    $htmlBody = '<div style="font-family: Arial, sans-serif; max-width: 500px; margin: 0 auto; padding: 30px; background: #f8fafc; border-radius: 10px;">
        <h2 style="color: #0f172a;">IMS Nepal</h2>
        <p>Your OTP code is:</p>
        <div style="font-size: 32px; font-weight: bold; background: white; padding: 20px; text-align: center; border-radius: 8px;">' . $safeOtp . '</div>
        <p>This OTP is valid for <strong>2 minutes</strong>.</p>
    </div>';

    // Plain text body
    $textBody = "IMS Nepal\n\nYour OTP is: $otp\n\nValid for 2 minutes.";

    $boundary = '----=_Part_' . bin2hex(random_bytes(16));
    $headers = [
        "From: $fromName <$fromEmail>",
        "Reply-To: $fromEmail",
        'X-Mailer: PHP/' . PHP_VERSION,
        'MIME-Version: 1.0',
        'Content-Type: multipart/alternative; boundary="' . $boundary . '"',
    ];

    // Include plain text and HTML alternatives for wider email-client support.
    $message = "--$boundary\r\n";
    $message .= "Content-Type: text/plain; charset=UTF-8\r\n";
    $message .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
    $message .= $textBody . "\r\n\r\n";
    $message .= "--$boundary\r\n";
    $message .= "Content-Type: text/html; charset=UTF-8\r\n";
    $message .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
    $message .= $htmlBody . "\r\n\r\n";
    $message .= "--$boundary--\r\n";

    // Use the configured XAMPP sendmail relay for authenticated TLS delivery.
    $sendmailCommand = trim((string)ini_get('sendmail_path'));
    if (!preg_match('/^"([^"]+\.exe)"(?:\s|$)|^([^\s"]+\.exe)(?:\s|$)/i', $sendmailCommand, $matches)) {
        error_log('Email sending failed: sendmail_path does not specify a sendmail executable.');
        return false;
    }

    $sendmailPath = !empty($matches[1]) ? $matches[1] : $matches[2];
    if (!is_file($sendmailPath)) {
        error_log('Email sending failed: configured sendmail executable was not found.');
        return false;
    }

    $mailData = "To: $to\r\n";
    $mailData .= "Subject: $subject\r\n";
    $mailData .= implode("\r\n", $headers) . "\r\n\r\n";
    $mailData .= $message;

    $command = '"' . $sendmailPath . '" -t';
    $descriptors = [
        0 => ['pipe', 'r'],
        1 => ['pipe', 'w'],
        2 => ['pipe', 'w'],
    ];
    $processWarning = null;
    set_error_handler(
        static function ($severity, $warning) use (&$processWarning) {
            if ($severity === E_WARNING && strpos($warning, 'proc_open():') !== false) {
                $processWarning = $warning;
                return true;
            }

            return false;
        }
    );

    try {
        $process = proc_open(
            $command,
            $descriptors,
            $pipes,
            dirname($sendmailPath),
            null,
            ['bypass_shell' => true]
        );
    } finally {
        restore_error_handler();
    }

    if (!is_resource($process)) {
        error_log('Email sending failed: ' . ($processWarning ?? 'could not start the configured mail relay.'));
        return false;
    }

    $bytesWritten = 0;
    $mailDataLength = strlen($mailData);
    while ($bytesWritten < $mailDataLength) {
        $written = fwrite($pipes[0], substr($mailData, $bytesWritten));
        if ($written === false || $written === 0) {
            break;
        }
        $bytesWritten += $written;
    }
    fclose($pipes[0]);

    $output = stream_get_contents($pipes[1]);
    fclose($pipes[1]);
    $errorOutput = stream_get_contents($pipes[2]);
    fclose($pipes[2]);

    $exitCode = proc_close($process);
    if ($bytesWritten !== $mailDataLength || $exitCode !== 0) {
        error_log(
            'Email sending failed: mail relay exited with code ' . $exitCode .
            ($errorOutput !== '' ? '; ' . trim($errorOutput) : '') .
            ($output !== '' ? '; ' . trim($output) : '')
        );
        return false;
    }

    return true;
}
