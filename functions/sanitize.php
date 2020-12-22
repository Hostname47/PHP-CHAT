<?php

function escape($string) {
    // Iprefer to use htmlspecialchars over htmlentities
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}