<?php

// 移行元データベース情報(DB_TYPEにはmysqlまたはpostgresを設定)
define('DB_TYPE', 'mysql');
define('DB_USER', 'root');
define('DB_PASSWORD', 'root');
define('DB_SERVER', '127.0.0.1');
define('DB_NAME', 'gaastra213');
define('DB_PORT', '3306');

// 移行先のデータベースの種類(現状はmysqlしか対応していません)
define('DB_TYPE_TO', 'mysql');

// import用SQLファイル名
define('IMPORT_PRODUCT', 'import-product.sql');

define('IMPORT_CUSTOMER', 'import-customer.sql');

// 受注ファイルは別途定義
