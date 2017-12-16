<?php

require_once('EccubeMigrate.php');

$migrate = new EccubeMigrate();

// 商品に関するデータを読み込み
$migrate->createProductData();

// 会員に関するデータを読み込み
$migrate->createCustomerData();

// 受注に関するデータを読み込み
$migrate->createOrderData1();
$migrate->createOrderData2();
$migrate->createOrderData3();
$migrate->createOrderData4();

$migrate->closeDatabase();
