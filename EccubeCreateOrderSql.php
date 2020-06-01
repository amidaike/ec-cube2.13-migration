<?php

/**
 * 受注に関連するテーブルのinsert文を作成
 */
class EccubeCreateOrderSql
{
    public $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    /**
     * dtb_orderのinsert文を作成
     *
     * @return bool
     */
    public function writeOrderSql()
    {
        $sql = 'SELECT * FROM dtb_order ORDER BY order_id;';

        $rows = Common::fetch($this->conn, $sql);

        if (!$rows) {
            return false;
        }

        $maxImport = 5000;
        $turn = ceil(count($rows) / $maxImport);
        $loop = 0;
        $file = null;

        for ($count = 1; $count <= $turn; $count++) {

            $orderValues = array();

            $file = new SplFileObject('import-order'.$count.'.sql', 'a+');

            $file->fwrite('set foreign_key_checks = 0;');
            $file->fwrite(PHP_EOL);

            for ($i = $loop; $i < $maxImport * $count; $i++) {

                $item = $rows[$i];

                $id = $item['order_id'];
                $create_date = "'".$item['create_date']."'";
                $update_date = "'".$item['update_date']."'";
                $del_flg = $item['del_flg'];

                $customer_id = $item['customer_id'] != '0' ? $item['customer_id'] : 'NULL';
                $order_country_id = 'NULL';

                $order_pref = $item['order_pref'] != '' ? $item['order_pref'] : 'NULL';
                $order_sex = $item['order_sex'] != '' ? $item['order_sex'] : 'NULL';
                $order_job = $item['order_job'] != '' ? $item['order_job'] : 'NULL';
                $payment_id = $item['payment_id'] != '' ? $item['payment_id'] : 'NULL';
                $device_type_id = $item['device_type_id'] != '' ? $item['device_type_id'] : 'NULL';
                $pre_order_id = 'NULL';
                $message = $item['message'] != '' ? "'".str_replace("'", "\'", $item['message'])."'" : 'NULL';

                $order_name01 = $item['order_name01'] != '' ? "'".str_replace("'", "\'", $item['order_name01'])."'" : 'NULL';
                $order_name02 = $item['order_name02'] != '' ? "'".str_replace("'", "\'", $item['order_name02'])."'" : 'NULL';
                $order_kana01 = $item['order_kana01'] != '' ? "'".str_replace("'", "\'", $item['order_kana01'])."'" : 'NULL';
                $order_kana02 = $item['order_kana02'] != '' ? "'".str_replace("'", "\'", $item['order_kana02'])."'" : 'NULL';

                $order_company_name = $item['order_company_name'] != '' ? "'".str_replace("'", "\'", $item['order_company_name'])."'" : 'NULL';

                $order_email = "'".$item['order_email']."'";

                $order_tel01 = $item['order_tel01'] != '' ? "'".$item['order_tel01']."'" : 'NULL';
                $order_tel02 = $item['order_tel02'] != '' ? "'".$item['order_tel02']."'" : 'NULL';
                $order_tel03 = $item['order_tel03'] != '' ? "'".$item['order_tel03']."'" : 'NULL';
                $order_fax01 = $item['order_fax01'] != '' ? "'".$item['order_fax01']."'" : 'NULL';
                $order_fax02 = $item['order_fax02'] != '' ? "'".$item['order_fax02']."'" : 'NULL';
                $order_fax03 = $item['order_fax03'] != '' ? "'".$item['order_fax03']."'" : 'NULL';

                $order_zip01 = $item['order_zip01'] != '' ? "'".$item['order_zip01']."'" : 'NULL';
                $order_zip02 = $item['order_zip02'] != '' ? "'".$item['order_zip02']."'" : 'NULL';
                if ($order_zip01 != 'NULL') {
                    $order_zipcode = $order_zip01.$order_zip02;
                } else {
                    $order_zipcode = 'NULL';
                }

                $order_addr01 = $item['order_addr01'] != '' ? "'".str_replace("'", "\'", $item['order_addr01'])."'" : 'NULL';
                $order_addr02 = $item['order_addr02'] != '' ? "'".str_replace("'", "\'", $item['order_addr02'])."'" : 'NULL';

                $order_birth = $item['order_birth'] != '' ? "'".$item['order_birth']."'" : 'NULL';

                $subtotal = $item['subtotal'] != '' ? "'".$item['subtotal']."'" : 'NULL';
                $discount = $item['discount'] != '' ? "'".$item['discount']."'" : 0;

                $delivery_fee_total = $item['deliv_fee'] != '' ? "'".$item['deliv_fee']."'" : 'NULL';
                $charge = $item['charge'] != '' ? "'".$item['charge']."'" : 'NULL';

                $tax = $item['tax'] != '' ? "'".$item['tax']."'" : 'NULL';
                $total = $item['total'] != '' ? "'".$item['total']."'" : 'NULL';
                $payment_total = $item['payment_total'] != '' ? "'".$item['payment_total']."'" : 'NULL';
                $payment_method = $item['payment_method'] != '' ? "'".$item['payment_method']."'" : 'NULL';

                $note = $item['note'] != '' ? "'".str_replace("'", "\'", $item['note'])."'" : 'NULL';

                $order_date = $create_date;

                $commit_date = $item['commit_date'] != '' ? "'".$item['commit_date']."'" : 'NULL';
                $payment_date = $item['payment_date'] != '' ? "'".$item['payment_date']."'" : 'NULL';
                $status = $item['status'] != '' ? "'".$item['status']."'" : 'NULL';

                $orderValues[] = "($id, $customer_id, $order_country_id, $order_pref, $order_sex, $order_job, $payment_id, $device_type_id, $pre_order_id, $message, $order_name01, $order_name02, $order_kana01, $order_kana02, $order_company_name, $order_email, "
                    ."$order_tel01, $order_tel02, $order_tel03, $order_fax01, $order_fax02, $order_fax03, $order_zip01, $order_zip02, $order_zipcode, $order_addr01, $order_addr02, $order_birth, $subtotal, $discount, "
                    ."$delivery_fee_total, $charge, $tax, $total, $payment_total, $payment_method, $note, $create_date, $update_date, $order_date, $commit_date, $payment_date, $del_flg, $status)";

                if ($i == count($rows) - 1) {

                    break;
                }
            }

            // insert文
            if (!empty($orderValues)) {
                $importOrderSql = 'INSERT INTO dtb_order(order_id, customer_id, order_country_id, order_pref, '
                    .'order_sex, order_job, payment_id, device_type_id, pre_order_id, message, '
                    .'order_name01, order_name02, order_kana01, order_kana02, order_company_name, '
                    .'order_email, order_tel01, order_tel02, order_tel03, order_fax01, order_fax02, '
                    .'order_fax03, order_zip01, order_zip02, order_zipcode, order_addr01, order_addr02, '
                    .'order_birth, subtotal, discount, delivery_fee_total, charge, tax, total, payment_total, '
                    .'payment_method, note, create_date, update_date, order_date, commit_date, payment_date, del_flg, status) VALUES '.PHP_EOL;
                $importOrderSql .= implode(','.PHP_EOL, $orderValues).";";

                $file->fwrite(PHP_EOL);
                $file->fwrite($importOrderSql);
            }

            $loop = $i;

            $file->fwrite(PHP_EOL);
            $file->fwrite('set foreign_key_checks = 1;');

            $file = null;
        }

        return true;
    }

    /**
     * dtb_order_detailのinsert文を作成
     *
     * @return bool
     */
    public function writeOrderDetailSql()
    {

        $sql = 'SELECT distinct od.*, c1.name as name1, c2.name as name2 FROM dtb_order_detail od '.
            'left join dtb_classcategory cc1 on od.classcategory_name1 = cc1.name '.
            'left join dtb_class c1 on cc1.class_id = c1.class_id '.
            'left join dtb_classcategory cc2 on od.classcategory_name2 = cc2.name '.
            'left join dtb_class c2 on cc2.class_id = c2.class_id '.
            'ORDER BY od.order_detail_id, od.order_id';

        $rows = Common::fetch($this->conn, $sql);

        if (!$rows) {
            return false;
        }


        $maxImport = 5000;
        $turn = ceil(count($rows) / $maxImport);
        $loop = 0;
        $file = null;

        for ($count = 1; $count <= $turn; $count++) {

            $orderDetailValues = array();

            $file = new SplFileObject('import-order-detail'.$count.'.sql', 'a+');

            $file->fwrite('set foreign_key_checks = 0;');
            $file->fwrite(PHP_EOL);

            for ($i = $loop; $i < $maxImport * $count; $i++) {

                $item = $rows[$i];

                $id = $item['order_detail_id'];
                $order_id = $item['order_id'];
                $product_id = $item['product_id'] != '' ? $item['product_id'] : 'NULL';
                $product_class_id = $item['product_class_id'] != '' ? $item['product_class_id'] : 'NULL';

                $product_name = $item['product_name'] != '' ? "'".str_replace("'", "\'", $item['product_name'])."'" : 'NULL';
                $product_code = $item['product_code'] != '' ? "'".str_replace("'", "\'", $item['product_code'])."'" : 'NULL';

                $class_name1 = $item['name1'] != '' ? "'".str_replace("'", "\'", $item['name1'])."'" : 'NULL';
                $class_name2 = $item['name2'] != '' ? "'".str_replace("'", "\'", $item['name2'])."'" : 'NULL';
                $class_category_name1 = $item['classcategory_name1'] != '' ? "'".str_replace("'", "\'", $item['classcategory_name1'])."'" : 'NULL';
                $class_category_name2 = $item['classcategory_name2'] != '' ? "'".str_replace("'", "\'", $item['classcategory_name2'])."'" : 'NULL';

                $price = $item['price'] != '' ? $item['price'] : 'NULL';
                $quantity = $item['quantity'] != '' ? $item['quantity'] : 'NULL';

                $tax_rate = $item['tax_rate'] != '' ? $item['tax_rate'] : 'NULL';
                $tax_rule = $item['tax_rule'] != '' ? $item['tax_rule'] : 'NULL';

                $orderDetailValues[] = "($id, $order_id, $product_id, $product_class_id, $product_name, "
                    ."$product_code, $class_name1, $class_name2, $class_category_name1, $class_category_name2, "
                    ."$price, $quantity, $tax_rate, $tax_rule)";

                if ($i == count($rows) - 1) {

                    break;
                }

            }


            // insert文
            if (!empty($orderDetailValues)) {
                $importOrderDetailSql = 'INSERT INTO dtb_order_detail(order_detail_id,order_id,product_id,product_class_id,'
                    .'product_name,product_code,class_name1,class_name2,class_category_name1,class_category_name2,'
                    .'price,quantity,tax_rate,tax_rule) VALUES '.PHP_EOL;
                $importOrderDetailSql .= implode(','.PHP_EOL, $orderDetailValues).";";

                $file->fwrite(PHP_EOL);
                $file->fwrite($importOrderDetailSql);
            }

            $loop = $i;

            $file->fwrite(PHP_EOL);
            $file->fwrite('set foreign_key_checks = 1;');

            $file = null;
        }

        return true;
    }


    /**
     * dtb_shipping, dtb_shipment_itemのinsert文を作成
     *
     * @return bool
     */
    public function writeShippingSql()
    {
        $sql = 'select distinct s.*, si.*, o.deliv_id, o.deliv_fee, d.name as delivery_name, p.product_id, c1.name as name1, c2.name as name2  from dtb_shipment_item si '.
            'left join dtb_shipping s on s.shipping_id = si.shipping_id and s.order_id = si.order_id '.
            'inner join dtb_order o on o.order_id = s.order_id '.
            'left join dtb_deliv d on d.deliv_id = o.deliv_id '.
            'left join dtb_delivfee df on df.deliv_id = o.deliv_id '.
            'left join dtb_products_class pc on pc.product_class_id = si.product_class_id '.
            'left join dtb_products p on p.product_id = pc.product_id '.
            'left join dtb_classcategory cc1 on si.classcategory_name1 = cc1.name '.
            'left join dtb_class c1 on cc1.class_id = c1.class_id '.
            'left join dtb_classcategory cc2 on si.classcategory_name2 = cc2.name '.
            'left join dtb_class c2 on cc2.class_id = c2.class_id '.
            'order by s.order_id';

        $rows = Common::fetch($this->conn, $sql);

        if (!$rows) {
            return false;
        }

        $maxImport = 5000;
        $turn = ceil(count($rows) / $maxImport);
        $loop = 0;
        $file = null;

        $shipping_id = 1;
        $tmp_order_id = 0;

        for ($count = 1; $count <= $turn; $count++) {

            $shippingValues = array();
            $shipmentItemValues = array();

            $file = new SplFileObject('import-shipping'.$count.'.sql', 'a+');

            $file->fwrite('set foreign_key_checks = 0;');
            $file->fwrite(PHP_EOL);

            for ($i = $loop; $i < $maxImport * $count; $i++) {

                $item = $rows[$i];

                $order_id = $item['order_id'];
                if ($order_id != $tmp_order_id) {
                    $id = $shipping_id;
                }
                $create_date = "'".$item['create_date']."'";
                $update_date = "'".$item['update_date']."'";
                $del_flg = $item['del_flg'];

                $shipping_country_id = 'NULL';
                $shipping_pref = $item['shipping_pref'] != '' ? $item['shipping_pref'] : 'NULL';
                $delivery_id = $item['deliv_id'] != '' ? $item['deliv_id'] : 'NULL';
                $time_id = $item['time_id'] != '' ? $item['time_id'] : 'NULL';
                $fee_id = $shipping_pref;

                $shipping_name01 = $item['shipping_name01'] != '' ? "'".str_replace("'", "\'", $item['shipping_name01'])."'" : 'NULL';
                $shipping_name02 = $item['shipping_name02'] != '' ? "'".str_replace("'", "\'", $item['shipping_name02'])."'" : 'NULL';
                $shipping_kana01 = $item['shipping_kana01'] != '' ? "'".str_replace("'", "\'", $item['shipping_kana01'])."'" : 'NULL';
                $shipping_kana02 = $item['shipping_kana02'] != '' ? "'".str_replace("'", "\'", $item['shipping_kana02'])."'" : 'NULL';

                $shipping_company_name = $item['shipping_company_name'] != '' ? "'".str_replace("'", "\'", $item['shipping_company_name'])."'" : 'NULL';

                $shipping_tel01 = $item['shipping_tel01'] != '' ? "'".$item['shipping_tel01']."'" : 'NULL';
                $shipping_tel02 = $item['shipping_tel02'] != '' ? "'".$item['shipping_tel02']."'" : 'NULL';
                $shipping_tel03 = $item['shipping_tel03'] != '' ? "'".$item['shipping_tel03']."'" : 'NULL';
                $shipping_fax01 = $item['shipping_fax01'] != '' ? "'".$item['shipping_fax01']."'" : 'NULL';
                $shipping_fax02 = $item['shipping_fax02'] != '' ? "'".$item['shipping_fax02']."'" : 'NULL';
                $shipping_fax03 = $item['shipping_fax03'] != '' ? "'".$item['shipping_fax03']."'" : 'NULL';

                $shipping_zip01 = $item['shipping_zip01'] != '' ? "'".$item['shipping_zip01']."'" : 'NULL';
                $shipping_zip02 = $item['shipping_zip02'] != '' ? "'".$item['shipping_zip02']."'" : 'NULL';

                if ($shipping_zip01 != 'NULL') {
                    $shipping_zipcode = $shipping_zip01.$shipping_zip02;
                } else {
                    $shipping_zipcode = 'NULL';
                }

                $shipping_addr01 = $item['shipping_addr01'] != '' ? "'".str_replace("'", "\'", $item['shipping_addr01'])."'" : 'NULL';
                $shipping_addr02 = $item['shipping_addr02'] != '' ? "'".str_replace("'", "\'", $item['shipping_addr02'])."'" : 'NULL';


                $shipping_delivery_name = $item['delivery_name'] != '' ? "'".str_replace("'", "\'", $item['delivery_name'])."'" : 'NULL';
                $shipping_delivery_time = $item['shipping_time'] != '' ? "'".str_replace("'", "\'", $item['shipping_time'])."'" : 'NULL';
                $shipping_delivery_date = $item['shipping_date'] != '' ? "'".$item['shipping_date']."'" : 'NULL';

                $shipping_delivery_fee = $item['deliv_fee'] != '' ? "'".$item['deliv_fee']."'" : 'NULL';
                $shipping_commit_date = $item['shipping_commit_date'] != '' ? "'".$item['shipping_commit_date']."'" : 'NULL';
                $rank = $item['rank'] != '' ? "'".$item['rank']."'" : 'NULL';

                if ($order_id != $tmp_order_id) {
                    // order_idが異なれば新しいdtb_shippingとして登録
                    $shippingValues[] = "($id, $shipping_country_id, $shipping_pref, $order_id, $delivery_id, $time_id, "
                        ."$fee_id, $shipping_name01, $shipping_name02, $shipping_kana01, $shipping_kana02, $shipping_company_name,"
                        ."$shipping_tel01, $shipping_tel02, $shipping_tel03, $shipping_fax01, $shipping_fax02, $shipping_fax03, $shipping_zip01, $shipping_zip02,"
                        ."$shipping_zipcode, $shipping_addr01, $shipping_addr02, $shipping_delivery_name, $shipping_delivery_time, $shipping_delivery_date, $shipping_delivery_fee, $shipping_commit_date,"
                        ."$rank, $create_date, $update_date, $del_flg)";
                }

                $product_id = $item['product_id'] != '' ? $item['product_id'] : 'NULL';
                $product_class_id = $item['product_class_id'] != '' ? $item['product_class_id'] : 'NULL';

                $product_name = $item['product_name'] != '' ? "'".str_replace("'", "\'", $item['product_name'])."'" : 'NULL';
                $product_code = $item['product_code'] != '' ? "'".str_replace("'", "\'", $item['product_code'])."'" : 'NULL';

                $class_name1 = $item['name1'] != '' ? "'".str_replace("'", "\'", $item['name1'])."'" : 'NULL';
                $class_name2 = $item['name2'] != '' ? "'".str_replace("'", "\'", $item['name2'])."'" : 'NULL';
                $class_category_name1 = $item['classcategory_name1'] != '' ? "'".str_replace("'", "\'", $item['classcategory_name1'])."'" : 'NULL';
                $class_category_name2 = $item['classcategory_name2'] != '' ? "'".str_replace("'", "\'", $item['classcategory_name2'])."'" : 'NULL';

                $price = $item['price'] != '' ? $item['price'] : 'NULL';
                $quantity = $item['quantity'] != '' ? $item['quantity'] : 'NULL';

                // dtb_shipment_item
                $shipmentItemValues[] = "($order_id, $product_id, $product_class_id, $id,"
                    ."$product_name, $product_code, $class_name1, $class_name2, $class_category_name1, $class_category_name2, $price, $quantity)";

                if ($i == count($rows) - 1) {

                    break;
                }

                if ($order_id != $tmp_order_id) {
                    $shipping_id++;
                }

                $tmp_order_id = $order_id;

            }

            // insert文
            if (!empty($shippingValues)) {
                $importShippingSql = 'INSERT INTO dtb_shipping(shipping_id, shipping_country_id, shipping_pref, order_id, delivery_id, time_id, '
                    .'fee_id, shipping_name01, shipping_name02, shipping_kana01, shipping_kana02, shipping_company_name,'
                    .'shipping_tel01, shipping_tel02, shipping_tel03, shipping_fax01, shipping_fax02, shipping_fax03, shipping_zip01, shipping_zip02,'
                    .'shipping_zipcode, shipping_addr01, shipping_addr02, shipping_delivery_name, shipping_delivery_time, shipping_delivery_date, shipping_delivery_fee, shipping_commit_date,'
                    .'`rank`, create_date, update_date, del_flg) VALUES '.PHP_EOL;
                $importShippingSql .= implode(','.PHP_EOL, $shippingValues).";";

                $file->fwrite(PHP_EOL);
                $file->fwrite($importShippingSql);
            }

            $file->fwrite(PHP_EOL);

            if (!empty($shipmentItemValues)) {
                $importShipmentItemSql = 'INSERT INTO dtb_shipment_item(order_id,product_id,product_class_id,'
                    .'shipping_id,product_name,product_code,class_name1,class_name2,'
                    .'class_category_name1,class_category_name2,price,quantity) VALUES '.PHP_EOL;
                $importShipmentItemSql .= implode(','.PHP_EOL, $shipmentItemValues).";";

                $file->fwrite(PHP_EOL);
                $file->fwrite($importShipmentItemSql);
            }

            $loop = $i;

            $file->fwrite(PHP_EOL);
            $file->fwrite('set foreign_key_checks = 1;');

            $file = null;
        }

        return true;
    }


    /**
     * dtb_mail_historyのinsert文を作成
     *
     * @return bool
     */
    public function writeMailHistorySql()
    {
        $sql = "SELECT distinct h.* FROM dtb_mail_history h LEFT JOIN dtb_order o ON h.order_id = o.order_id WHERE o.order_id IS NOT NULL ORDER BY h.send_id ASC;";

        $rows = Common::fetch($this->conn, $sql);

        if (!$rows) {
            return false;
        }

        $maxImport = 500;
        $turn = ceil(count($rows) / $maxImport);
        $loop = 0;
        $file = null;

        for ($count = 1; $count <= $turn; $count++) {

            $mailHistoryValues = array();

            $file = new SplFileObject('import-mail-history'.$count.'.sql', 'a+');

            $file->fwrite('set foreign_key_checks = 0;');
            $file->fwrite(PHP_EOL);

            for ($i = $loop; $i < $maxImport * $count; $i++) {

                $item = $rows[$i];

                $id = $item['send_id'];
                $order_id = $item['order_id'];

                $template_id = "'".$item['template_id']."'";
                $creator_id = "'".$item['creator_id']."'";

                $send_date = $item['send_date'] != '' ? "'".$item['send_date']."'" : 'NULL';
                $subject = $item['subject'] != '' ? "'".str_replace("'", "\'", $item['subject'])."'" : 'NULL';
                $mail_body = $item['mail_body'] != '' ? "'".str_replace("'", "\'", $item['mail_body'])."'" : 'NULL';

                $mailHistoryValues[] = "($id, $order_id, $template_id, $creator_id, $send_date, $subject, $mail_body)";

                if ($i == count($rows) - 1) {

                    break;
                }

            }

            // insert文
            if (!empty($mailHistoryValues)) {
                $importMailHistorySql = 'INSERT INTO dtb_mail_history(send_id, order_id, template_id,  creator_id, send_date, subject, mail_body) VALUES'.PHP_EOL;
                $importMailHistorySql .= implode(','.PHP_EOL, $mailHistoryValues).";";

                $file->fwrite(PHP_EOL);
                $file->fwrite($importMailHistorySql);
            }


            $loop = $i;

            $file->fwrite(PHP_EOL);
            $file->fwrite('set foreign_key_checks = 1;');

            $file = null;

        }

        return true;
    }

}