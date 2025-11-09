<?php

function add_admin_to_path(string $path): string {
    // On remplace uniquement la première occurrence de "uploads/"
    return preg_replace('~(^(\.\./)*)(uploads/)~', '$1admin/$3', $path, 1);
}

?>