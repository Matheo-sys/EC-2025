<?php
if (session_status() == PHP_SESSION_NONE) {
    @session_start();
}

function safe_attr($value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function sanitize_html_allowlist(string $html, array $allowedTags = []): string
{
    if (empty($allowedTags)) {
        return safe_attr($html);
    }
    $allowed = '';
    foreach ($allowedTags as $t) {
        $allowed .= "<" . $t . ">";
    }
    // strip_tags garde uniquement les balises autorisées
    return strip_tags($html, $allowed);
}

function send_csp_header(): string
{
    if (headers_sent())
        return '';

    $nonce = base64_encode(random_bytes(16));

    $csp = "default-src 'self'; ";
    $csp .= "script-src 'self' https://cdn.jsdelivr.net https://unpkg.com https://cdnjs.cloudflare.com 'nonce-$nonce'; ";
    $csp .= "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.jsdelivr.net https://unpkg.com https://cdnjs.cloudflare.com; ";
    $csp .= "font-src 'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com; ";
    $csp .= "img-src 'self' data: https:; ";
    $csp .= "connect-src 'self'; ";

    header("Content-Security-Policy: $csp");
    return $nonce;
}

$nonce = send_csp_header();
?>