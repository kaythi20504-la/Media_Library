<?php

declare(strict_types=1);

namespace App\Controllers\Api;

use App\Services\FormatService;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * API: Media Suggestion endpoint
 */
class ApiSuggestController
{
    private FormatService $formatService;

    public function __construct(FormatService $formatService)
    {
        $this->formatService = $formatService;
    }

    public function store(): void
    {
        header('Content-Type: application/json');

        try {

            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                http_response_code(405);
                echo json_encode([
                    'success' => false,
                    'message' => 'Method not allowed'
                ]);
                return;
            }

            $input = $this->getInput();
            $result = $this->process($input);

            if (!empty($result['error'])) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => $result['error']
                ]);
                return;
            }

            http_response_code(200);
            echo json_encode([
                'success' => true,
                'message' => 'Suggestion sent successfully'
            ]);

        } catch (Exception $e) {

            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    private function process(array $data): array
    {
        $data = [
            'name'     => trim($data['name'] ?? ''),
            'email'    => trim($data['email'] ?? ''),
            'category' => trim($data['category'] ?? ''),
            'title'    => trim($data['title'] ?? ''),
            'format'   => trim($data['format'] ?? ''),
            'genre'    => trim($data['genre'] ?? ''),
            'year'     => trim($data['year'] ?? ''),
            'details'  => trim($data['details'] ?? ''),
        ];

        if (empty($data['name']) ||
            empty($data['email']) ||
            empty($data['category']) ||
            empty($data['title'])) {
            return ['error' => 'Name, Email, Category and Title are required'];
        }

        if (!empty($data['address'] ?? null)) {
            return ['error' => 'Spam detected'];
        }

        if (!PHPMailer::validateAddress($data['email'])) {
            return ['error' => 'Invalid email address'];
        }

        $this->sendEmail($data);

        return ['success' => true];
    }

private function sendEmail(array $data): void
{
    $mail = new PHPMailer(true);

    // Helper function to find variables no matter where XAMPP puts them
    $getSetting = function($key) {
        return $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key) ?: null;
    };

    $mail->isSMTP();
    $mail->Host       = $getSetting('MAIL_HOST'); // smtp.gmail.com
    $mail->SMTPAuth   = true;
    $mail->Username   = $getSetting('MAIL_USERNAME'); // kaythi20504@gmail.com
    $mail->Password   = $getSetting('MAIL_PASSWORD'); // oavkhtvvsnwatuxq
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // SSL for Port 465
    $mail->Port       = (int)$getSetting('MAIL_PORT'); 

    $fromEmail = $getSetting('MAIL_FROM_EMAIL');
    $fromName  = $getSetting('MAIL_FROM_NAME');

    // Final check to see if variables actually loaded
    if (!$fromEmail || !$mail->Password) {
        throw new \Exception("Environment variables are missing. Please check that your .env file is located at: " . BASE_PATH . DIRECTORY_SEPARATOR . ".env");
    }

    $mail->setFrom($fromEmail, $fromName);
    $mail->addAddress($fromEmail); 
    $mail->addReplyTo($data['email'], $data['name']);

    $mail->Subject = 'Library Suggestion from: ' . $data['name'];
    $mail->isHTML(false);
    $mail->Body = "Name: {$data['name']}\n" .
                  "Email: {$data['email']}\n" .
                  "Title: {$data['title']}\n" .
                  "Details: {$data['details']}";

    $mail->send();
}

/**
     * Helper to grab JSON data from Postman or Form data from a browser
     */
    private function getInput(): array
    {
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';

        // If the request is JSON (like from Postman)
        if (strpos($contentType, 'application/json') !== false) {
            $json = file_get_contents("php://input");
            return json_decode($json, true) ?? [];
        }

        // Fallback for standard form submissions
        return $_POST;
    }
} // Final closing brace of the class


