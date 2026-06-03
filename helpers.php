<?php

function e($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function money($value)
{
    return number_format((float) $value, 2, ',', ' ') . ' GNF';
}

function setFlash($type, $message)
{
    $GLOBALS['flash_message'] = [
        'type' => $type,
        'message' => $message,
    ];
}

function getFlash()
{
    if (!empty($GLOBALS['flash_message'])) {
        return $GLOBALS['flash_message'];
    }

    if (!empty($_GET['flash_type']) && !empty($_GET['flash_message'])) {
        return [
            'type' => $_GET['flash_type'],
            'message' => $_GET['flash_message'],
        ];
    }

    return null;
}

function redirectTo($url)
{
    if (!empty($GLOBALS['flash_message'])) {
        $separator = strpos($url, '?') === false ? '?' : '&';
        $url .= $separator . http_build_query([
            'flash_type' => $GLOBALS['flash_message']['type'],
            'flash_message' => $GLOBALS['flash_message']['message'],
        ]);
    }

    header('Location: ' . $url);
    exit;
}

function logAppError($message)
{
    $logDir = __DIR__ . '/logs';
    if (!is_dir($logDir)) {
        mkdir($logDir, 0777, true);
    }

    $line = '[' . date('Y-m-d H:i:s') . '] ' . $message . PHP_EOL;
    file_put_contents($logDir . '/app.log', $line, FILE_APPEND);
}

function activeClass($currentPage, $page, $extraCondition = true)
{
    return $currentPage === $page && $extraCondition ? 'active' : '';
}

function statusBadge($statut)
{
    $classes = [
        'Payee' => 'success',
        'Partiellement payee' => 'warning',
        'Annulee' => 'danger',
        'En attente' => 'secondary',
    ];

    $label = [
        'Payee' => 'Payee',
        'Partiellement payee' => 'Partiellement payee',
        'Annulee' => 'Annulee',
        'En attente' => 'En attente',
    ];

    $class = $classes[$statut] ?? 'secondary';
    $text = $label[$statut] ?? $statut;

    return '<span class="badge text-bg-' . $class . '">' . e($text) . '</span>';
}

function old($name, $default = '')
{
    return isset($_POST[$name]) ? $_POST[$name] : $default;
}
