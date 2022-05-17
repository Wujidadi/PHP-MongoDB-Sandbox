<?php

/**
 * Volume mount 檔案清單
 *
 * 鍵（key）為目標路徑  
 * 值（value）為源路徑，`(d)` 代表為資料夾，  
 * 其他非空字串者在本專案（Docker 建構專案）內有原始參照檔，初始化（執行 `init` 腳本）時會複製這些檔案到目標路徑
 *
 * @var string[]
 */
return [

    # 資料庫
    trDirSep(PROJECT_BASE . '/Volumes/Database') => '(d)',

    # 日誌
    trDirSep(PROJECT_BASE . '/Logs')                   => '(d)',
    trDirSep(PROJECT_BASE . '/Logs/syslog')            => '',
    trDirSep(PROJECT_BASE . '/Logs/apache.access.log') => '',
    trDirSep(PROJECT_BASE . '/Logs/apache.error.log')  => ''

];
