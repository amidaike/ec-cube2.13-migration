<?php

require_once('config.php');
require_once('Common.php');
require_once('EccubeCreateProductSql.php');
require_once('EccubeCreateCustomerSql.php');
require_once('EccubeCreateOrderSql.php');

class EccubeMigrate
{
    public $conn;

    public function __construct()
    {
        $this->initialize();
    }

    /**
     * 商品関連データの移行
     */
    public function createProductData()
    {
        $file = $this->readFile(IMPORT_PRODUCT);

        $data = new EccubeCreateProductSql($this->conn, $file);

        $file->fwrite('set foreign_key_checks = 0;');
        $file->fwrite(PHP_EOL);

        // dtb_category
        $data->writeCategorySql();
        // dtb_class_name
        $data->writeClassNameSql();
        // dtb_class_category
        $data->writeClassCategorySql();
        // dtb_maker
        // $data->writeMakerSql();
        // dtb_product
        $data->writeProductSql();
        // dtb_product_class
        $data->writeProductClassSql();
        // dtb_product_category
        $data->writeProductCategorySql();
        // dtb_product_tag
        $data->writeProductTagSql();

        $file->fwrite(PHP_EOL);
        $file->fwrite('set foreign_key_checks = 1;');

        $file = null;

    }

    public function createCustomerData()
    {

        $file = $this->readFile(IMPORT_CUSTOMER);

        $data = new EccubeCreateCustomerSql($this->conn, $file);

        $file->fwrite('set foreign_key_checks = 0;');
        $file->fwrite(PHP_EOL);

        $customer_address_id = $data->writeCustomerSql();

        $data->writeCustomerAddressSql($customer_address_id);

        $data->writeCustomerFavoriteProductSql();

        $file->fwrite(PHP_EOL);
        $file->fwrite('set foreign_key_checks = 1;');

        $file = null;

    }

    public function createOrderData1()
    {

        $data = new EccubeCreateOrderSql($this->conn);

        $data->writeOrderSql();

        $file = null;

    }

    public function createOrderData2()
    {

        $data = new EccubeCreateOrderSql($this->conn);

        $data->writeShippingSql();

        $file = null;

    }

    public function createOrderData3()
    {

        $data = new EccubeCreateOrderSql($this->conn);

        $data->writeOrderDetailSql();

        $file = null;

    }

    public function createOrderData4()
    {

        $data = new EccubeCreateOrderSql($this->conn);

        $data->writeMailHistorySql();

        $file = null;

    }

    public function createMasterData()
    {

    }

    public function createOtherData()
    {

    }

    public function initialize()
    {
        $this->connectDatabase();
    }

    public function connectDatabase()
    {
        if (Common::isMySQL()) {
            $this->conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT) or die('Could not connect');
        } elseif (Common::isPostgres()) {
            $conn_string = 'host='.DB_SERVER.' port='.DB_PORT.' dbname='.DB_NAME.' user='.DB_USER.' password='.DB_PASSWORD;
            $this->conn = pg_connect($conn_string) or die('Could not connect');
        }
    }

    public function closeDatabase()
    {
        if (Common::isMySQL()) {
            mysqli_close($this->conn);
        } elseif (Common::isPostgres()) {
            pg_close($this->conn);
        }
    }


    public function readFile($file_name)
    {
        $file = new SplFileObject($file_name, 'a+');

        return $file;
    }

}