<?php

function get_extension($fileName) {
    return (false === $pos = strrpos($fileName, '.')) ? '' : substr($fileName, $pos);
}