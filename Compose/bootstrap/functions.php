<?php

/**
 * 統一轉換檔案路徑中的反斜線（目錄分隔符號）為正斜線
 *
 * @param  string  $path  檔案路徑
 * @return string
 */
function trDirSep(string $path): string
{
    return preg_replace("/\\\/", '/', $path);
}

/**
 * 格式化命令行輸出
 *
 * @param  string   $text       輸出字串
 * @param  string   $hexColor   `#RRGGBB` 格式色碼
 * @param  boolean  $breakLine  最後是否換行，預設 `false`
 * @param  boolean  $underline  字元是否帶底線，預設 `false`
 * @return string               ANSI 格式化輸出字元
 */
function colorText(string $text, string $hexColor = '', bool $breakLine = false, bool $underline = false): string
{
    $eot = $breakLine ? PHP_EOL : '';
    $udl = $underline ? ';4' : '';

    if ($hexColor === '' || is_null($hexColor)) {
        return "{$text}{$eot}";
    } else {
        list($r, $g, $b) = sscanf($hexColor, '#%02X%02X%02X');
        return "\033[38;2;{$r};{$g};{$b}{$udl}m{$text}\033[0m{$eot}";
    }
}

/**
 * 提示檔案初始化安全（成功）訊息
 *
 * @param  string  $file  檔案名稱
 * @return void
 */
function fileInitSafe(string $file): void
{
    echo colorText('建立檔案 ', CLI_COLOR_SAFE) .
        colorText($file, CLI_COLOR_SAFE_EM) .
        colorText(' 成功！', CLI_COLOR_SAFE, true);
}

/**
 * 提示檔案初始化警告訊息
 *
 * @param  string  $file  檔案名稱
 * @param  boolean  $force  是否強行重建檔案
 * @return void
 */
function fileInitWarn(string $file, bool $force = false)
{
    if ($force) {
        echo colorText('檔案 ', CLI_COLOR_WARN) .
            colorText($file, CLI_COLOR_WARN_EM) .
            colorText(' 已存在，強行覆蓋中……', CLI_COLOR_WARN, true);
    } else {
        echo colorText('檔案 ', CLI_COLOR_DANGER) .
            colorText($file, CLI_COLOR_DANGER_EM) .
            colorText(' 已存在！', CLI_COLOR_DANGER, true);
    }
}

/**
 * 提示檔案初始化危險（失敗）訊息
 *
 * @param  string  $file  檔案名稱
 * @return void
 */
function fileInitDanger(string $file)
{
    echo colorText('建立檔案 ', CLI_COLOR_DANGER) .
        colorText($file, CLI_COLOR_DANGER_EM) .
        colorText(' 失敗！', CLI_COLOR_DANGER, true);
}

/**
 * 提示資料夾初始化安全（成功）訊息
 *
 * @param  string  $dir  資料夾名稱
 * @return void
 */
function dirInitSafe(string $dir): void
{
    echo colorText('建立資料夾 ', CLI_COLOR_SAFE) .
        colorText($dir, CLI_COLOR_SAFE_EM) .
        colorText(' 成功！', CLI_COLOR_SAFE, true);
}

/**
 * 提示資料夾初始化危險（失敗）訊息
 *
 * @param  string  $dir  資料夾名稱
 * @return void
 */
function dirInitDanger(string $dir)
{
    echo colorText('建立資料夾 ', CLI_COLOR_DANGER) .
        colorText($dir, CLI_COLOR_DANGER_EM) .
        colorText(' 失敗！', CLI_COLOR_DANGER, true);
}

/**
 * 提示檔案初始化警告（存在同名資料夾）訊息
 *
 * @param  string   $file   檔案名稱
 * @param  boolean  $force  是否強行重建檔案
 * @param  boolean  $dir    是否資料夾（是則不顯示「同名」提示字眼，否則反之）
 * @return void
 */
function dirExistWarn(string $file, bool $force = false, bool $dir = false)
{
    $sameNameHint = $dir ? '' : '同名';
    if ($force) {
        echo colorText("{$sameNameHint}資料夾 ", CLI_COLOR_WARN) .
            colorText($file, CLI_COLOR_WARN_EM) .
            colorText(' 已存在，刪除並重新建立檔案中……', CLI_COLOR_WARN, true);
    } else {
        echo colorText("{$sameNameHint}資料夾 ", CLI_COLOR_DANGER) .
            colorText($file, CLI_COLOR_DANGER_EM) .
            colorText(' 已存在！', CLI_COLOR_DANGER, true);
    }
}

/**
 * 逐項檢查輸入的檔案清單，建立其中不存在的檔案或資料夾
 *
 * @param  string[]  $fileList  檔案及資料夾清單
 * @param  boolean   $force     是否強制覆蓋現有檔案/資料夾
 * @return void
 */
function createFileOrDirByList($fileList, $force = false)
{
    foreach ($fileList as $file => $source) {
        # Windows 下替換磁碟代號大小寫及檔案路徑分隔字元
        if (OS === 'Windows') {
            $file = preg_replace(
                '/\//',
                '\\',
                preg_replace_callback('/^\/(\w+)\//', function ($matches) {
                    return strtoupper($matches[1]) . ':\\';
                }, $file)
            );
            $source = preg_replace('/\//', '\\', $source);
        }

        if (is_file($file)) {
            # 帶命令行參數 -f 且有原始參照檔時，強行以原始參照檔覆蓋現有檔案
            if ($force && $source != '') {
                # 黃字提示資料夾已存在，強行覆蓋中
                fileInitWarn($file, true);

                # 覆蓋檔案
                $built = copy($source, $file);
                if ($built) {
                    # 綠字提示檔案建立成功
                    fileInitSafe($file);
                } else {
                    # 紅字提示檔案建立失敗
                    fileInitDanger($file);
                }
            } else {
                # 紅字提示檔案已存在
                fileInitWarn($file);
            }
        } else {
            # 同名資料夾已存在
            if (is_dir($file)) {
                if ($source !== '(d)') {
                    # 帶命令行參數 -f 時，強行刪除同名資料夾並重新建立檔案
                    if ($force) {
                        # 黃字提示資料夾已存在，強行重建中
                        dirExistWarn($file, true);

                        # 遞迴刪除同名資料夾
                        shell_exec("rm -rf '{$file}'");

                        # 複製或新建檔案
                        $built = ($source != '') ? copy($source, $file) : fclose(fopen($file, 'w+'));
                        if ($built) {
                            # 綠字提示檔案建立成功
                            fileInitSafe($file);
                        } else {
                            # 紅字提示檔案建立失敗
                            fileInitDanger($file);
                        }
                    } else {
                        # 命令行參數不含 -f，紅字提示資料夾已存在
                        dirExistWarn($file);
                    }
                } else {
                    # 本項本來就應該是資料夾
                    # 帶命令行參數 -f 時，強行刪除同名資料夾並重新建立檔案
                    if ($force) {
                        # 黃字提示資料夾已存在，強行重建中
                        dirExistWarn($file, true);

                        # 遞迴刪除同名資料夾
                        shell_exec("rm -rf '{$file}'");

                        # 新建資料夾
                        $built = mkdir($file);
                        if ($built) {
                            # 綠字提示資料夾建立成功
                            dirInitSafe($file);
                        } else {
                            # 紅字提示資料夾建立失敗
                            dirInitDanger($file);
                        }
                    } else {
                        # 紅字提示資料夾已存在
                        dirExistWarn(file: $file, dir: false);
                    }
                }
            } else {
                if ($source !== '(d)') {
                    # 檔案不存在，複製或新建檔案
                    $built = ($source != '') ? copy($source, $file) : fclose(fopen($file, 'w+'));
                    if ($built) {
                        # 綠字提示檔案建立成功
                        fileInitSafe($file);
                    } else {
                        # 紅字提示檔案建立失敗
                        fileInitDanger($file);
                    }
                } else {
                    # 本項應為資料夾，新建之
                    $built = mkdir($file);
                    if ($built) {
                        # 綠字提示資料夾建立成功
                        dirInitSafe($file);
                    } else {
                        # 紅字提示資料夾建立失敗
                        dirInitDanger($file);
                    }
                }
            }
        }
    }
}
