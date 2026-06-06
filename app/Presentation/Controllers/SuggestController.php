<?php

declare(strict_types=1);

namespace App\Presentation\Controllers;

use App\Presentation\Controllers\BaseController;
use App\Application\Catalog\UseCases\GetFormatDataUseCase;

class SuggestController extends BaseController
{
    public function __construct(
        private GetFormatDataUseCase $formatUseCase
    ) {}

    /**
     * Display suggestion form
     */
    public function index(): void
    {
        $this->requireLogin();

        $pageTitle  = "Suggest a Media Item";
        $section    = "suggest";
        $hideSearch = true;

        $error_message = null;

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $result = $this->handleForm();

            if (!empty($result['error_message'])) {
                $error_message = $result['error_message'];
            }
        }

        $data = $this->formatUseCase->execute();

        $categories = $data['categories'] ?? [];
        $formats    = $data['formats'] ?? [];
        $genres     = $data['genres'] ?? [];

        $this->view('suggest', [
            'pageTitle' => $pageTitle,
            'section' => $section,
            'hideSearch' => $hideSearch,
            'error_message' => $error_message,
            'categories' => $categories,
            'formats' => $formats,
            'genres' => $genres,
            'user' => $this->user()
        ]);
    }

    /**
     * Handle form submission (same logic kept)
     */
    private function handleForm(): array
    {
        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'category' => trim($_POST['category'] ?? ''),
            'title' => trim($_POST['title'] ?? ''),
            'format' => trim($_POST['format'] ?? ''),
            'genre' => trim($_POST['genre'] ?? ''),
            'year' => trim($_POST['year'] ?? ''),
            'details' => trim($_POST['details'] ?? ''),
            'error_message' => null
        ];

        if (
            empty($data['name']) ||
            empty($data['email']) ||
            empty($data['category']) ||
            empty($data['title'])
        ) {
            $data['error_message'] = "Please fill in all required fields.";
            return $data;
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $data['error_message'] = "Invalid email address.";
            return $data;
        }

        if (!empty($_POST['address'])) {
            $data['error_message'] = "Spam detected.";
            return $data;
        }

        header("Location: " . BASE_URL . "/public/index.php?page=suggest&status=thanks");
        exit;
    }
}