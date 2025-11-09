<?php
declare(strict_types=1);
define('UPLOAD_DIR', realpath(__DIR__ . '/../../uploads/gallery'));
define('UPLOAD_URL', '../../uploads/gallery');    

/**
 * Sauvegarde un fichier image uploadé dans /uploads/gallery/{section}/{category_id}/
 * Retourne l’URL publique (relative).
 */
function save_image_upload(string $input, string $section, int $categoryId): string
{
    if (empty($_FILES[$input]) || !is_uploaded_file($_FILES[$input]['tmp_name'])) {
        throw new RuntimeException('Aucun fichier uploadé.');
    }

    $file = $_FILES[$input];

    if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Erreur upload (code '.$file['error'].')');
    }
    if ($file['size'] > 8 * 1024 * 1024) {
        throw new RuntimeException('Fichier trop volumineux (8 Mo max).');
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime  = (string) $finfo->file($file['tmp_name']);
    $ext   = match ($mime) {
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
        default      => throw new RuntimeException('Format non supporté (JPEG/PNG/WEBP).'),
    };

    $section = strtolower(preg_replace('~[^a-z0-9_-]+~i', '-', $section));
    $dirAbs  = rtrim(UPLOAD_DIR, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $section . DIRECTORY_SEPARATOR . $categoryId;

    if (!is_dir($dirAbs) && !mkdir($dirAbs, 0775, true)) {
        throw new RuntimeException('Impossible de créer le dossier : ' . $dirAbs);
    }

    $basename = date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
    $destAbs  = $dirAbs . DIRECTORY_SEPARATOR . $basename;

    if (!move_uploaded_file($file['tmp_name'], $destAbs)) {
        throw new RuntimeException('Échec move_uploaded_file.');
    }

    $destUrl = rtrim(UPLOAD_URL, '/') . '/' . $section . '/' . $categoryId . '/' . $basename;
    return $destUrl;
}