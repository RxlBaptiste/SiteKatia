<?php 
function public_url_from_db(string $path): string {
    $p = trim((string)$path);
    $p = str_replace('\\', '/', $p);

    // déjà absolu ? on ne touche pas
    if (preg_match('~^https?://~i', $p)) {
        return $p;
    }

    // nettoie les ../ et ./
    while (strpos($p, '../') === 0) {
        $p = substr($p, 3);
    }
    if (strpos($p, './') === 0) {
        $p = substr($p, 2);
    }

    // depuis la page publique il faut préfixer "admin/"
    if (!str_starts_with($p, 'admin/')) {
        $p = 'admin/' . ltrim($p, '/');
    }

    // IMPORTANT : pas de "/" initial ici
    return ltrim($p, '/');
}
?>