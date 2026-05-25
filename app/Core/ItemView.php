<?php
namespace App\Core;

class ItemView
{
    public static function render(array $item): string
    {
        $id    = htmlspecialchars((string)$item['media_id'], ENT_QUOTES, 'UTF-8');
        $img   = htmlspecialchars($item['img'], ENT_QUOTES, 'UTF-8');
        $title = htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8');
        $url   = BASE_URL . "/index.php?page=details&id={$id}";

        return "
        <li>
            <a href='{$url}'>
                <img src='" . BASE_URL . "/{$img}' alt='{$title}' />
                <p>View Details</p>
            </a>
        </li>";
    }
}