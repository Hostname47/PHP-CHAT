<?php

function sanitize_text($text) {
    $text = trim($text);
    $text = htmlspecialchars($text);

    return $text;
}