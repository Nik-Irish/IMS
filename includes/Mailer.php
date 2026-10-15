<?php
/**
 * Send OTP email using native PHP mail() function
 * 
 * @param string $email Recipient email address
 * @param string|int $otp OTP code to send
 * @return bool True on success, false on failure
 */
function sendOtpMail($email, $otp)
{
    // Admin sender configuration
    $fromEmail = 'nikrishdulal01@gmail.com';
    $fromName = 'IMS Nepal';
    $subject = 'IMS Nepal - Password Reset OTP';

    // Sanitize OTP for HTML output
    $safeOtp = htmlspecialchars(
        (string)$otp,
        ENT_QUOTES,
        'UTF-8'
    );

    // HTML email body - kept exactly as original
    $htmlBody = '
            <div style="
                font-family: Arial, sans-serif;
                max-width: 500px;
                margin: 0 auto;
                padding: 30px;
                background: #f8fafc;
                border-radius: 10px;
            ">

                <h2 style="
                    margin-bottom: 10px;
                    color: #0f172a;
                ">
                    IMS Nepal
                </h2>

                <p>
                    You requested a password reset.
                </p>

                <p>
                    Your OTP code is:
                </p>

                <div style="
                    font-size: 32px;
                    font-weight: bold;
                    letter-spacing: 8px;
                    padding: 20px;
                    text-align: center;
                    background: white;
                    border-radius: 8px;
                    margin: 20px 0;
                ">
                    ' . $safeOtp . '
                </div>

                <p>
                    This OTP is valid for
                    <strong>2 minutes</strong>.
                </p>

                <p style="
                    color: #64748b;
                    font-size: 13px;
                ">
                    If you did not request a password reset,
                    you can safely ignore this email.
                </p>

            </div>
        ';

    // Plain text alternative body - kept exactly as original
    $textBody = "IMS Nepal\n\n" .
                "Your password reset OTP is: " .
                $otp .
                "\n\n" .
                "This OTP is valid for 2 minutes.";

    // Build headers for multipart email (HTML + plain text)
    $boundary = '----=_Part_' . md5(uniqid(rand(), true)) . '_' . time();
    
    $headers = [];
    $headers[] = 'From: ' . $fromName . ' <' . $fromEmail . '>';
    $headers[] = 'Reply-To: ' . $fromEmail;
    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-Type: multipart/alternative; boundary="' . $boundary . '"';
    $headers[] = 'X-Mailer: PHP/' . phpversion();

    // Build multipart message body
    $message = '--' . $boundary . "\r\n";
    $message .= 'Content-Type: text/plain; charset=UTF-8' . "\r\n";
    $message .= 'Content-Transfer-Encoding: 8bit' . "\r\n\r\n";
    $message .= $textBody . "\r\n\r\n";
    
    $message .= '--' . $boundary . "\r\n";
    $message .= 'Content-Type: text/html; charset=UTF-8' . "\r\n";
    $message .= 'Content-Transfer-Encoding: 8bit' . "\r\n\r\n";
    $message .= $htmlBody . "\r\n\r\n";
    
    $message .= '--' . $boundary . '--';

    // Send email using native mail() function
    // The -f flag sets the envelope sender (return-path)
    $additionalParams = '-f' . $fromEmail;
    
    try {
        $result = mail($email, $subject, $message, implode("\r\n", $headers), $additionalParams);
        
        if (!$result) {
            error_log('PHP mail() Error: Failed to send OTP email to ' . $email);
            return false;
        }
        
        return true;
    } catch (Exception $e) {
        error_log('PHP mail() Exception: ' . $e->getMessage());
        return false;
    }
}
?>