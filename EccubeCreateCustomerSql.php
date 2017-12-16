<?php

/**
 * 会員に関連するテーブルのinsert文を作成
 */
class EccubeCreateCustomerSql
{
    public $conn;
    public $file;

    public function __construct($conn, SplFileObject $file)
    {
        $this->conn = $conn;
        $this->file = $file;
    }

    /**
     * dtb_customer、dtb_customer_addressのinsert文を作成
     *
     * @return bool|int
     */
    public function writeCustomerSql()
    {
        $sql = 'SELECT * FROM dtb_customer ORDER BY customer_id;';

        $rows = Common::fetch($this->conn, $sql);

        if (!$rows) {
            return false;
        }

        $customerValues = array();
        $customerAddressValues = array();
        $mailmagaValues = array();
        $customer_address_id = 1;

        foreach ($rows as $item) {

            $id = $item['customer_id'];
            $create_date = "'".$item['create_date']."'";
            $update_date = "'".$item['update_date']."'";
            $del_flg = $item['del_flg'];

            $status = $item['status'];
            $sex = $item['sex'] != '' ? $item['sex'] : 'NULL';
            $job = $item['job'] != '' ? $item['job'] : 'NULL';
            $country_id = 'NULL';
            $pref = $item['pref'] != '' ? $item['pref'] : 'NULL';

            $name01 = $item['name01'] != '' ? "'".str_replace("'", "\'", $item['name01'])."'" : 'NULL';
            $name02 = $item['name02'] != '' ? "'".str_replace("'", "\'", $item['name02'])."'" : 'NULL';
            $kana01 = $item['kana01'] != '' ? "'".str_replace("'", "\'", $item['kana01'])."'" : 'NULL';
            $kana02 = $item['kana02'] != '' ? "'".str_replace("'", "\'", $item['kana02'])."'" : 'NULL';
            $company_name = $item['company_name'] != '' ? "'".str_replace("'", "\'", $item['company_name'])."'" : 'NULL';

            $zip01 = $item['zip01'] != '' ? "'".$item['zip01']."'" : 'NULL';
            $zip02 = $item['zip02'] != '' ? "'".$item['zip02']."'" : 'NULL';
            if ($zip01 != 'NULL') {
                $zipcode = $zip01.$zip02;
            } else {
                $zipcode = 'NULL';
            }

            $addr01 = $item['addr01'] != '' ? "'".str_replace("'", "\'", $item['addr01'])."'" : 'NULL';
            $addr02 = $item['addr02'] != '' ? "'".str_replace("'", "\'", $item['addr02'])."'" : 'NULL';

            $email = "'".$item['email']."'";
            $tel01 = $item['tel01'] != '' ? "'".$item['tel01']."'" : 'NULL';
            $tel02 = $item['tel02'] != '' ? "'".$item['tel02']."'" : 'NULL';
            $tel03 = $item['tel03'] != '' ? "'".$item['tel03']."'" : 'NULL';
            $fax01 = $item['fax01'] != '' ? "'".$item['fax01']."'" : 'NULL';
            $fax02 = $item['fax02'] != '' ? "'".$item['fax02']."'" : 'NULL';
            $fax03 = $item['fax03'] != '' ? "'".$item['fax03']."'" : 'NULL';
            $birth = $item['birth'] != '' ? "'".$item['birth']."'" : 'NULL';
            $password = $item['password'] != '' ? "'".$item['password']."'" : 'NULL';
            $salt = $item['salt'] != '' ? "'".$item['salt']."'" : 'NULL';
            $secret_key = "'".$item['secret_key']."'";
            $first_buy_date = $item['first_buy_date'] != '' ? "'".$item['first_buy_date']."'" : 'NULL';
            $last_buy_date = $item['last_buy_date'] != '' ? "'".$item['last_buy_date']."'" : 'NULL';
            $buy_times = $item['buy_times'] != '' ? "'".$item['buy_times']."'" : 'NULL';
            $buy_total = $item['buy_total'] != '' ? "'".$item['buy_total']."'" : 'NULL';
            $note = $item['note'] != '' ? "'".str_replace("'", "\'", $item['note'])."'" : 'NULL';
            $reset_key = 'NULL';
            $reset_expire = 'NULL';

            $customerValues[] = "($id, $status, $sex, $job, $country_id, $pref, $name01, $name02, $kana01, $kana02, $company_name, $zip01, $zip02, $zipcode, $addr01, $addr02, $email, "
                ."$tel01, $tel02, $tel03, $fax01, $fax02, $fax03, $birth, $password, $salt, $secret_key, $first_buy_date, $last_buy_date, $buy_times, $buy_total, $note, $reset_key, $reset_expire, $create_date, $update_date, $del_flg)";

            $customerAddressValues[] = "($customer_address_id, $id, $country_id, $pref, $name01, $name02, $kana01, $kana02, $company_name, $zip01, $zip02, $zipcode, $addr01, $addr02, $tel01, $tel02, $tel03, $fax01, $fax02, $fax03, $create_date, $update_date, $del_flg)";
            $customer_address_id++;

            $mailmaga_flg = $item['mailmaga_flg'] != '' ? "'".$item['mailmaga_flg']."'" : 'NULL';

            if ($mailmaga_flg == "'1'" || $mailmaga_flg == "'2'") {
                $mailmaga_flg = 1;
            } else {
                $mailmaga_flg = 0;
            }

            // plg_mailmaga_customer
            $mailmagaValues[] = "($id, $mailmaga_flg, $del_flg, $create_date, $update_date)";

        }

        // insert文
        if (!empty($customerValues)) {
            $importCustomerSql = 'INSERT INTO dtb_customer(customer_id, status, sex, job, country_id, pref, name01, name02, kana01, kana02, company_name, zip01, zip02, zipcode, addr01, addr02, email, tel01, tel02, tel03, fax01, fax02, fax03, birth, password, salt, secret_key, first_buy_date, last_buy_date, buy_times, buy_total, note, reset_key, reset_expire, create_date, update_date, del_flg) VALUES'.PHP_EOL;
            $importCustomerSql .= implode(','.PHP_EOL, $customerValues).";";
            $this->file->fwrite(PHP_EOL);
            $this->file->fwrite($importCustomerSql);
        }

        $this->file->fwrite(PHP_EOL);

        if (!empty($customerAddressValues)) {
            $importCustomerAddressSql = 'INSERT INTO dtb_customer_address(customer_address_id, customer_id, country_id, pref, name01, name02, kana01, kana02, company_name, zip01, zip02, zipcode, addr01, addr02, tel01, tel02, tel03, fax01, fax02, fax03, create_date, update_date, del_flg) VALUES'.PHP_EOL;
            $importCustomerAddressSql .= implode(','.PHP_EOL, $customerAddressValues).";";
            $this->file->fwrite(PHP_EOL);
            $this->file->fwrite($importCustomerAddressSql);
        }

        $this->file->fwrite(PHP_EOL);

        if (!empty($mailmagaValues)) {
            $importMailmagaSql = 'INSERT INTO plg_mailmaga_customer(customer_id, mailmaga_flg, del_flg, create_date, update_date) VALUES'.PHP_EOL;
            $importMailmagaSql .= implode(','.PHP_EOL, $mailmagaValues).";";
            $this->file->fwrite(PHP_EOL);
            $this->file->fwrite($importMailmagaSql);
        }

        $this->file->fwrite(PHP_EOL);
        $this->file->fwrite(PHP_EOL);

        return $customer_address_id;
    }


    /**
     * dtb_customer_addressのinsert文を作成
     *
     * @param $customer_address_id
     * @return bool
     */
    public function writeCustomerAddressSql($customer_address_id)
    {
        $sql = 'SELECT * FROM dtb_other_deliv ORDER BY other_deliv_id;';

        $rows = Common::fetch($this->conn, $sql);

        if (!$rows) {
            return false;
        }

        $lastItem = end($rows);

        // insert文
        $sql = 'INSERT INTO dtb_customer_address(customer_address_id, customer_id, country_id, pref, name01, name02, kana01, kana02, company_name, zip01, zip02, zipcode, addr01, addr02, tel01, tel02, tel03, fax01, fax02, fax03, create_date, update_date, del_flg) VALUES'.PHP_EOL;
        $this->file->fwrite($sql);

        foreach ($rows as $item) {

            $id = $customer_address_id;
            $date = date('Y-m-d H:i:s');
            $create_date = "'".$date."'";
            $update_date = "'".$date."'";
            $del_flg = 0;

            $customer_id = $item['customer_id'];
            $country_id = 'NULL';
            $pref = $item['pref'] != '' ? $item['pref'] : 'NULL';

            $name01 = $item['name01'] != '' ? "'".str_replace("'", "\'", $item['name01'])."'" : 'NULL';
            $name02 = $item['name02'] != '' ? "'".str_replace("'", "\'", $item['name02'])."'" : 'NULL';
            $kana01 = $item['kana01'] != '' ? "'".str_replace("'", "\'", $item['kana01'])."'" : 'NULL';
            $kana02 = $item['kana02'] != '' ? "'".str_replace("'", "\'", $item['kana02'])."'" : 'NULL';

            $company_name = 'NULL';

            $zip01 = $item['zip01'] != '' ? "'".$item['zip01']."'" : 'NULL';
            $zip02 = $item['zip02'] != '' ? "'".$item['zip02']."'" : 'NULL';
            if ($zip01 != 'NULL') {
                $zipcode = $zip01.$zip02;
            } else {
                $zipcode = 'NULL';
            }

            $addr01 = $item['addr01'] != '' ? "'".str_replace("'", "\'", $item['addr01'])."'" : 'NULL';
            $addr02 = $item['addr02'] != '' ? "'".str_replace("'", "\'", $item['addr02'])."'" : 'NULL';

            $tel01 = $item['tel01'] != '' ? "'".$item['tel01']."'" : 'NULL';
            $tel02 = $item['tel02'] != '' ? "'".$item['tel02']."'" : 'NULL';
            $tel03 = $item['tel03'] != '' ? "'".$item['tel03']."'" : 'NULL';
            $fax01 = 'NULL';
            $fax02 = 'NULL';
            $fax03 = 'NULL';


            $values = "($id, $customer_id, $country_id, $pref, $name01, $name02, $kana01, $kana02, $company_name, $zip01, $zip02, $zipcode, $addr01, $addr02, $tel01, $tel02, $tel03, $fax01, $fax02, $fax03, $create_date, $update_date, $del_flg)";

            if ($item == $lastItem) {
                $this->file->fwrite($values.';'.PHP_EOL);
            } else {
                $this->file->fwrite($values.','.PHP_EOL);
            }

            $customer_address_id++;
        }

        return true;
    }

    /**
     * dtb_customer_favorite_productのinsert文を作成
     *
     * @return bool
     */
    public function writeCustomerFavoriteProductSql()
    {
        $sql = 'SELECT * FROM dtb_customer_favorite_products fd INNER JOIN dtb_customer c ON c.customer_id = fd.customer_id INNER JOIN dtb_products p ON p.product_id = fd.product_id;';

        $rows = Common::fetch($this->conn, $sql);

        if (!$rows) {
            return false;
        }

        $lastItem = end($rows);

        // insert文
        $sql = 'INSERT INTO dtb_customer_favorite_product(favorite_id, customer_id, product_id, create_date, update_date, del_flg) VALUES'.PHP_EOL;
        $this->file->fwrite(PHP_EOL);
        $this->file->fwrite($sql);

        $favorite_id = 1;

        foreach ($rows as $item) {

            $id = $favorite_id;
            $create_date = "'".$item['create_date']."'";
            $update_date = "'".$item['update_date']."'";
            $del_flg = 0;

            $customer_id = $item['customer_id'];
            $product_id = $item['product_id'];

            $values = "($id, $customer_id, $product_id, $create_date, $update_date, $del_flg)";

            if ($item == $lastItem) {
                $this->file->fwrite($values.';'.PHP_EOL);
            } else {
                $this->file->fwrite($values.','.PHP_EOL);
            }

            $favorite_id++;
        }

        return true;
    }

}