<?php

namespace App\Helpers;

class Sanitizer
{
    public static function clean(mixed $data): mixed
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = self::clean($value);
            }
            return $data;
        }

        if (is_string($data)) {
            return trim(strip_tags($data));
        }

        return $data;
    }

    public static function escape(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    public static function isValidEmail(string $email): bool
    {
        return (bool) filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public static function validateUpload(array $file, array $allowedMimes = ['image/jpeg', 'image/png', 'image/webp'], int $maxSizeBytes = 2097152): ?string
    {
        if (!isset($file['error']) || is_array($file['error'])) {
            return 'Parameter berkas tidak valid.';
        }

        if ($file['error'] === UPLOAD_ERR_NO_FILE) {
            return null; // No file uploaded, handled by caller if required
        }

        if ($file['error'] !== UPLOAD_ERR_OK) {
            return 'Gagal mengunggah berkas. Kode kesalahan: ' . $file['error'];
        }

        if ($file['size'] > $maxSizeBytes) {
            $mb = round($maxSizeBytes / 1048576, 1);
            return "Ukuran berkas melebihi batas maksimum ({$mb} MB).";
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mime, $allowedMimes, true)) {
            return 'Format berkas tidak didukung. Harap unggah gambar JPG, PNG, atau WEBP.';
        }

        return null;
    }

    public static function storeUpload(array $file, string $destinationDir, string $prefix = 'avatar'): ?string
    {
        if (!is_dir($destinationDir)) {
            mkdir($destinationDir, 0755, true);
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        $extensions = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
        ];

        $ext = $extensions[$mime] ?? 'jpg';
        $randomName = $prefix . '_' . bin2hex(random_bytes(16)) . '.' . $ext;
        $targetPath = rtrim($destinationDir, '/') . '/' . $randomName;

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            return $randomName;
        }

        return null;
    }
}
