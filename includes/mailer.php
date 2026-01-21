<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once '../PHPMailer/src/PHPMailer.php';
require_once '../PHPMailer/src/SMTP.php';
require_once '../PHPMailer/src/Exception.php';

/**
 * Email configuration function for password reset functionality
 * 
 * This function provides flexible email configuration options including:
 * - Standard PHP mail function
 * - SMTP configuration using PHPMailer
 * - Logging of email attempts
 * 
 * @param string $to Email recipient
 * @param string $subject Email subject
 * @param string $message Email body message
 * @param array $options Additional options (smtp settings, attachments, etc)
 * @return array Status of sending and any error messages
 */
function sendEmail($to, $subject, $message, $options = [])
{
    // Default options
    $default_options = [
        'from_email' => 'sample@gmail.com',
        'from_name' => 'A1 Store',
        'reply_to' => 'sample@gmail.com',
        'use_smtp' => true,
        'smtp_host' => 'your host smtp',
        'smtp_port' => 587,
        'smtp_secure' => 'tls',
        'smtp_auth' => true,
        'smtp_username' => 'sample@gmail.com',
        'smtp_password' => 'your smtp password',
        'log_emails' => true,
        'log_path' => '../logs/email_logs.txt',
        'is_html' => true
    ];

    // Merge user options with defaults
    $options = array_merge($default_options, $options);

    // Result array
    $result = [
        'success' => false,
        'error' => ''
    ];

    // Decide which mail method to use
    if ($options['use_smtp']) {
        // Using PHPMailer for SMTP
        // First check if PHPMailer is available
        if (!class_exists('PHPMailer\PHPMailer\PHPMailer')) {
            if (!file_exists('../vendor/autoload.php')) {
                $result['error'] = 'PHPMailer not available. Please install it using Composer.';
                logEmailAttempt($to, $subject, $result, $options);
                return $result;
            }
            require_once '../vendor/autoload.php';
        }

        try {
            $mail = new PHPMailer();

            // Server settings
            $mail->isSMTP();
            $mail->Host = $options['smtp_host'];
            $mail->SMTPAuth = $options['smtp_auth'];
            $mail->Username = $options['smtp_username'];
            $mail->Password = $options['smtp_password'];
            $mail->SMTPSecure = $options['smtp_secure'];
            $mail->Port = $options['smtp_port'];
            // $mail->SMTPDebug = 2;

            // Recipients
            $mail->setFrom($options['from_email'], $options['from_name']);
            $mail->addAddress($to);
            $mail->addReplyTo($options['reply_to']);

            // Content
            $mail->isHTML($options['is_html']);
            $mail->Subject = $subject;
            $mail->Body = $message;

            // If HTML is enabled, set plain text alternative
            if ($options['is_html']) {
                $mail->AltBody = strip_tags($message);
            }

            $mail->send();
            $result['success'] = true;
        } catch (Exception $e) {
            $result['error'] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        // Using standard PHP mail function
        $headers = "From: {$options['from_name']} <{$options['from_email']}>\r\n";
        $headers .= "Reply-To: {$options['reply_to']}\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";

        if ($options['is_html']) {
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        }

        if (mail($to, $subject, $message, $headers)) {
            $result['success'] = true;
        } else {
            $result['error'] = "Failed to send email using PHP mail() function.";
        }
    }

    // Log email attempt if enabled
    if ($options['log_emails']) {
        logEmailAttempt($to, $subject, $result, $options);
    }

    return $result;
}

/**
 * Log email sending attempts
 * 
 * @param string $to Recipient email
 * @param string $subject Email subject
 * @param array $result Result of sending attempt
 * @param array $options Email configuration options
 * @return void
 */
function logEmailAttempt($to, $subject, $result, $options)
{
    $log_dir = dirname($options['log_path']);

    // Create log directory if it doesn't exist
    if (!file_exists($log_dir)) {
        mkdir($log_dir, 0755, true);
    }

    $log_message = date('Y-m-d H:i:s') . " | ";
    $log_message .= "To: $to | ";
    $log_message .= "Subject: $subject | ";
    $log_message .= "Status: " . ($result['success'] ? 'SUCCESS' : 'FAILED');

    if (!$result['success']) {
        $log_message .= " | Error: " . $result['error'];
    }

    $log_message .= " | Method: " . ($options['use_smtp'] ? 'SMTP' : 'PHP mail()');
    $log_message .= "\n";

    file_put_contents($options['log_path'], $log_message, FILE_APPEND);
}
