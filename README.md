# PHP-MongoDB Sandbox

PHP-MongoDB 測試沙盒。

## 注意事項
* 執行 Main APP 子專案的 `composer install` 時，因依賴的 MongoDB library 需要配合 PHP MongoDB driver (ext-mongodb ^1.13.0)，若宿主環境未安裝，則必須在容器內執行。
