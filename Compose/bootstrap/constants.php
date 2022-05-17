<?php

switch (strtolower(PHP_OS))
{
    case 'win32':
    case 'winnt':
    case 'windows':
        $_os = 'Windows';
        break;

    case 'darwin':
        $_os = 'macOS';
        break;

    default:
        $_os = PHP_OS;
        break;
}
/**
 * 作業系統類型
 *
 * @var string
 */
define('OS', $_os);
unset($_os);

/**
 * 大駝峰形式的專案名稱，用於容器名稱前綴
 *
 * @var string
 */
define('PROJECT_NAME_UPPER', 'PHP-Mongo');

/**
 * Kebab 形式的專案名稱，用於 Docker Compose 專案名及容器內的 host 名稱
 *
 * @var string
 */
define('PROJECT_NAME_KEBAB', 'php-mongo');

/**
 * 專案根目錄
 *
 * @var string
 */
define('PROJECT_BASE', dirname(__DIR__, 2));

/**
 * 命令行輸出顏色：安全或正常
 *
 * @var string
 */
define('CLI_COLOR_SAFE', '#B6F5B6');

/**
 * 命令行輸出顏色：安全或正常（強調）
 *
 * @var string
 */
define('CLI_COLOR_SAFE_EM', '#5AE75A');

/**
 * 命令行輸出顏色：警告
 *
 * @var string
 */
define('CLI_COLOR_WARN', '#FFFFAD');

/**
 * 命令行輸出顏色：警告（強調）
 *
 * @var string
 */
define('CLI_COLOR_WARN_EM', '#FFD700');

/**
 * 命令行輸出顏色：危險或錯誤
 *
 * @var string
 */
define('CLI_COLOR_DANGER', '#FFD2D2');

/**
 * 命令行輸出顏色：危險或錯誤（強調）
 *
 * @var string
 */
define('CLI_COLOR_DANGER_EM', '#FF6D6D');
