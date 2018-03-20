<?php

/**
 * 商品に関連するテーブルのinsert文を作成
 */
class EccubeCreateProductSql
{
    public $conn;
    public $file;

    public function __construct($conn, SplFileObject $file)
    {
        $this->conn = $conn;
        $this->file = $file;
    }

    /**
     * dtb_categoryのinsert文を作成
     *
     * @return bool
     */
    public function writeCategorySql()
    {
        $sql = 'SELECT * FROM dtb_category ORDER BY level, category_id, parent_category_id, rank;';

        $rows = Common::fetch($this->conn, $sql);

        if (!$rows) {
            return false;
        }

        $lastItem = end($rows);

        // insert文
        $sql = 'INSERT INTO dtb_category (category_id, category_name, parent_category_id, level, rank, creator_id, create_date, update_date, del_flg) VALUES'.PHP_EOL;
        $this->file->fwrite($sql);

        foreach ($rows as $item) {

            $id = $item['category_id'];
            $creator_id = $item['creator_id'];
            $create_date = "'".$item['create_date']."'";
            $update_date = "'".$item['update_date']."'";
            $del_flg = $item['del_flg'];

            $parent_category_id = $item['parent_category_id'] == 0 ? 'NULL' : $item['parent_category_id'];
            $category_name = $item['category_name'] != '' ? "'".str_replace("'", "\'", $item['category_name'])."'" : 'NULL';
            $level = $item['level'];
            $rank = $item['rank'];

            $values = "($id, $category_name, $parent_category_id, $level, $rank, $creator_id, $create_date, $update_date, $del_flg)";
            if ($item == $lastItem) {
                $this->file->fwrite($values.';'.PHP_EOL);
            } else {
                $this->file->fwrite($values.','.PHP_EOL);
            }
        }

        return true;
    }


    /**
     * dtb_categoryのinsert文を作成
     *
     * @return bool
     */
    public function writeProductCategorySql()
    {
        $sql = 'SELECT * FROM dtb_product_categories pc INNER JOIN dtb_products p ON pc.product_id = p.product_id INNER JOIN dtb_category c ON pc.category_id = c.category_id ORDER BY pc.product_id, pc.rank;';

        $rows = Common::fetch($this->conn, $sql);

        if (!$rows) {
            return false;
        }

        $lastItem = end($rows);

        // insert文
        $sql = 'INSERT INTO dtb_product_category (product_id, category_id, rank) VALUES'.PHP_EOL;
        $this->file->fwrite($sql);

        foreach ($rows as $item) {

            $product_id = $item['product_id'];
            $category_id = $item['category_id'];
            $rank = $item['rank'];

            $values = "($product_id, $category_id, $rank)";
            if ($item == $lastItem) {
                $this->file->fwrite($values.';'.PHP_EOL);
            } else {
                $this->file->fwrite($values.','.PHP_EOL);
            }
        }

        return true;
    }

    /**
     * dtb_class_nameのinsert文を作成
     *
     * @return bool
     */
    public function writeClassNameSql()
    {
        $sql = 'SELECT * FROM dtb_class ORDER BY class_id ASC;';

        $rows = Common::fetch($this->conn, $sql);

        if (!$rows) {
            return false;
        }

        $lastItem = end($rows);

        // insert文
        $sql = 'INSERT INTO dtb_class_name (class_name_id, name, rank, creator_id, create_date, update_date, del_flg) VALUES'.PHP_EOL;
        $this->file->fwrite(PHP_EOL);
        $this->file->fwrite($sql);

        foreach ($rows as $item) {

            $id = $item['class_id'];
            $creator_id = $item['creator_id'];
            $create_date = "'".$item['create_date']."'";
            $update_date = "'".$item['update_date']."'";
            $del_flg = $item['del_flg'];

            $class_name = $item['name'] != '' ? "'".str_replace("'", "\'", $item['name'])."'" : 'NULL';
            $rank = $item['rank'];

            $values = "($id, $class_name, $rank, $creator_id, $create_date, $update_date, $del_flg)";
            if ($item == $lastItem) {
                $this->file->fwrite($values.';'.PHP_EOL);
            } else {
                $this->file->fwrite($values.','.PHP_EOL);
            }
        }

        return true;
    }

    /**
     * dtb_class_categoryのinsert文を作成
     *
     * @return bool
     */
    public function writeClassCategorySql()
    {
        $sql = 'SELECT * FROM dtb_classcategory WHERE classcategory_id > 0 ORDER BY classcategory_id ASC;';

        $rows = Common::fetch($this->conn, $sql);

        if (!$rows) {
            return false;
        }

        $lastItem = end($rows);

        // insert文
        $sql = 'INSERT INTO dtb_class_category (class_category_id, name, class_name_id, rank, creator_id, create_date, update_date, del_flg) VALUES'.PHP_EOL;
        $this->file->fwrite(PHP_EOL);
        $this->file->fwrite($sql);

        foreach ($rows as $item) {

            $id = $item['classcategory_id'];
            $creator_id = $item['creator_id'];
            $create_date = "'".$item['create_date']."'";
            $update_date = "'".$item['update_date']."'";
            $del_flg = $item['del_flg'];

            $name = $item['name'] != '' ? "'".str_replace("'", "\'", $item['name'])."'" : 'NULL';
            $class_name_id = $item['class_id'];
            $rank = $item['rank'];

            $values = "($id, $name, $class_name_id, $rank, $creator_id, $create_date, $update_date, $del_flg)";
            if ($item == $lastItem) {
                $this->file->fwrite($values.';'.PHP_EOL);
            } else {
                $this->file->fwrite($values.','.PHP_EOL);
            }
        }

        return true;
    }

    /**
     * dtb_makerのinsert文を作成
     *
     * @return bool
     */
    public function writeMakerSql()
    {
        $sql = 'SELECT * FROM dtb_maker ORDER BY maker_id ASC;';

        $rows = Common::fetch($this->conn, $sql);

        if (!$rows) {
            return false;
        }

        $lastItem = end($rows);

        // insert文
        $sql = 'INSERT INTO dtb_maker (maker_id, name,rank, creator_id, create_date, update_date, del_flg) VALUES'.PHP_EOL;
        $this->file->fwrite($sql);

        foreach ($rows as $item) {

            $id = $item['maker_id'];
            $creator_id = $item['creator_id'];
            $create_date = "'".$item['create_date']."'";
            $update_date = "'".$item['update_date']."'";
            $del_flg = $item['del_flg'];

            $name = $item['name'] != '' ? "'".str_replace("'", "\'", $item['name'])."'" : 'NULL';

            $rank = $item['rank'];

            $values = "($id, $name, $rank, $creator_id, $create_date, $update_date, $del_flg)";
            if ($item == $lastItem) {
                $this->file->fwrite($values.';'.PHP_EOL);
            } else {
                $this->file->fwrite($values.','.PHP_EOL);
            }
        }

        return true;
    }

    /**
     * dtb_productのinsert文を作成
     *
     * @return bool
     */
    public function writeProductSql()
    {
        $sql = 'SELECT * FROM dtb_products ORDER BY product_id ASC;';

        $rows = Common::fetch($this->conn, $sql);

        if (!$rows) {
            return false;
        }

        $product_image_id = 1;

        $imageValues = array();
        $productValues = array();

        foreach ($rows as $item) {

            // dtb_products
            $id = $item['product_id'];
            $creator_id = $item['creator_id'];
            $create_date = "'".$item['create_date']."'";
            $update_date = "'".$item['update_date']."'";
            $del_flg = $item['del_flg'];

            $name = $item['name'] != '' ? "'".str_replace("'", "\'", $item['name'])."'" : 'NULL';
            $status = $item['status'];
            $note = $item['note'] != '' ? "'".str_replace("'", "\'", $item['note'])."'" : 'NULL';

            $description_list = $item['main_list_comment'] != '' ? "'".str_replace("'", "\'", $item['main_list_comment'])."'" : 'NULL';
            $description_detail = $item['main_comment'] != '' ? "'".str_replace("'", "\'", $item['main_comment'])."'" : 'NULL';

            $search_word = $item['comment3'] != '' ? "'".str_replace("'", "\'", $item['comment3'])."'" : 'NULL';

            // 必要であれば随時サイトに合わせた内容に置換すること
            $free_area = $item['sub_title1'] != '' ? "'".str_replace("'", "\'", $item['sub_title1'])."'" : 'NULL';
            $free_area .= $item['sub_comment1'] != '' ? "'".str_replace("'", "\'", $item['sub_comment1'])."'" : 'NULL';
            $free_area .= $item['sub_large_image1'] != '' ? "'".str_replace("'", "\'", $item['sub_large_image1'])."'" : 'NULL';
            $free_area .= $item['sub_title2'] != '' ? "'".str_replace("'", "\'", $item['sub_title2'])."'" : 'NULL';
            $free_area .= $item['sub_comment2'] != '' ? "'".str_replace("'", "\'", $item['sub_comment2'])."'" : 'NULL';
            $free_area .= $item['sub_large_image2'] != '' ? "'".str_replace("'", "\'", $item['sub_large_image2'])."'" : 'NULL';
            $free_area .= $item['sub_title3'] != '' ? "'".str_replace("'", "\'", $item['sub_title3'])."'" : 'NULL';
            $free_area .= $item['sub_comment3'] != '' ? "'".str_replace("'", "\'", $item['sub_comment3'])."'" : 'NULL';
            $free_area .= $item['sub_large_image3'] != '' ? "'".str_replace("'", "\'", $item['sub_large_image3'])."'" : 'NULL';
            $free_area .= $item['sub_title4'] != '' ? "'".str_replace("'", "\'", $item['sub_title4'])."'" : 'NULL';
            $free_area .= $item['sub_comment4'] != '' ? "'".str_replace("'", "\'", $item['sub_comment4'])."'" : 'NULL';
            $free_area .= $item['sub_large_image4'] != '' ? "'".str_replace("'", "\'", $item['sub_large_image4'])."'" : 'NULL';
            $free_area .= $item['sub_title5'] != '' ? "'".str_replace("'", "\'", $item['sub_title5'])."'" : 'NULL';
            $free_area .= $item['sub_comment5'] != '' ? "'".str_replace("'", "\'", $item['sub_comment5'])."'" : 'NULL';
            $free_area .= $item['sub_large_image5'] != '' ? "'".str_replace("'", "\'", $item['sub_large_image5'])."'" : 'NULL';
            $free_area .= $item['sub_title6'] != '' ? "'".str_replace("'", "\'", $item['sub_title6'])."'" : 'NULL';
            $free_area .= $item['sub_comment6'] != '' ? "'".str_replace("'", "\'", $item['sub_comment6'])."'" : 'NULL';
            $free_area .= $item['sub_large_image6'] != '' ? "'".str_replace("'", "\'", $item['sub_large_image6'])."'" : 'NULL';

            if ($item['sub_title1'] != '') {
                $free_area .= "'";
            } else {
                $free_area = 'NULL';
            }

            // $maker_id = $item['maker_id'] != '' ? $item['maker_id'] : 'NULL';

            $rank = 1;
            if ($item["main_large_image"] != '') {
                $file_name = "'".$item["main_large_image"]."'";
                $imageValues[] = "($product_image_id, $id, $creator_id, $file_name, $rank, $create_date)";
                $product_image_id++;
                $rank++;
            }

            // Import sub image.
            for ($i = 1; $i <= 6; $i++) {
                if ($item["sub_large_image$i"] != '') {
                    $file_name = "'".$item["sub_large_image$i"]."'";
                    // Write data dtb_product_image.
                    $imageValues[] = "($product_image_id, $id, $creator_id, $file_name, $rank, $create_date)";
                    $product_image_id++;
                    $rank++;
                }
            }

            $productValues[] = "($id, $status, $name, $note, $description_list, $description_detail, $search_word, $free_area, $creator_id, $create_date, $update_date, $del_flg)";

        }

        // insert文
        if (!empty($productValues)) {
            $importProductSql = 'INSERT INTO dtb_product (product_id, status, name, note, description_list, description_detail, search_word, free_area, creator_id, create_date, update_date, del_flg) VALUES'.PHP_EOL;
            $importProductSql .= implode(','.PHP_EOL, $productValues).";";
            $this->file->fwrite(PHP_EOL);
            $this->file->fwrite($importProductSql);
        }


        if (!empty($imageValues)) {
            $importImageSql = 'INSERT INTO dtb_product_image (product_image_id, product_id, creator_id, file_name, rank, create_date) VALUES'.PHP_EOL;
            $importImageSql .= implode(','.PHP_EOL, $imageValues).";";
            $this->file->fwrite(PHP_EOL);
            $this->file->fwrite($importImageSql);
        }

        return true;
    }


    /**
     * dtb_product_classのinsert文を作成
     *
     * @return bool
     */
    public function writeProductClassSql()
    {
        $sql = 'SELECT pc.*,p.deliv_date_id FROM dtb_products_class pc INNER JOIN dtb_products p ON pc.product_id = p.product_id ORDER BY pc.product_class_id ASC;';

        $rows = Common::fetch($this->conn, $sql);

        if (!$rows) {
            return false;
        }

        $productClassValues = array();
        $productStockValues = array();
        $product_stock_id = 1;
        $updateProductValues = array();

        foreach ($rows as $item) {

            // dtb_product_class
            $id = $item['product_class_id'];
            $creator_id = $item['creator_id'];
            $create_date = "'".$item['create_date']."'";
            $update_date = "'".$item['update_date']."'";
            $del_flg = $item['del_flg'];

            $product_id = $item['product_id'];
            $product_type_id = $item['product_type_id'];
            $class_category_id1 = $item['classcategory_id1'] == 0 ? 'NULL' : $item['classcategory_id1'];
            $class_category_id2 = $item['classcategory_id2'] == 0 ? 'NULL' : $item['classcategory_id2'];
            $delivery_date_id = (is_numeric($item['deliv_date_id']) && $item['deliv_date_id'] > 0) ? $item['deliv_date_id'] : 'NULL';
            $product_code = $item['product_code'] != '' ? "'".str_replace("'", "\'", $item['product_code'])."'" : 'NULL';
            $stock = $item['stock'] != '' ? $item['stock'] : 'NULL';
            $stock_unlimited = $item['stock_unlimited'] != '' ? $item['stock_unlimited'] : 0;
            $sale_limit = $item['sale_limit'] != '' ? $item['sale_limit'] : 'NULL';
            $price01 = $item['price01'] != '' ? $item['price01'] : 'NULL';
            $price02 = $item['price02'] != '' ? $item['price02'] : 'NULL';
            $delivery_fee = 'NULL';

            $productStockValues[] = "($product_stock_id, $id, $stock, $creator_id, $create_date, $update_date)";

            $productClassValues[] = "($id, $product_id, $product_type_id, $class_category_id1, $class_category_id2, $delivery_date_id,"
                ." $product_code, $stock, $stock_unlimited, $sale_limit, $price01, $price02, $delivery_fee, $creator_id, $create_date, $update_date, $del_flg)";
            $product_stock_id++;

        }

        // insert文
        if (!empty($productClassValues)) {
            $importProductClassSql = 'INSERT INTO dtb_product_class (product_class_id, product_id, product_type_id, class_category_id1, class_category_id2, delivery_date_id, product_code, stock, stock_unlimited, sale_limit, price01, price02, delivery_fee, creator_id, create_date, update_date, del_flg) VALUES'.PHP_EOL;
            $importProductClassSql .= implode(','.PHP_EOL, $productClassValues).";";
            $this->file->fwrite(PHP_EOL);
            $this->file->fwrite($importProductClassSql);
        }

        if (!empty($productStockValues)) {
            $importStockSql = 'INSERT INTO dtb_product_stock (product_stock_id, product_class_id, stock,  creator_id, create_date, update_date) VALUES'.PHP_EOL;
            $importStockSql .= implode(','.PHP_EOL, $productStockValues).";";
            $this->file->fwrite(PHP_EOL);
            $this->file->fwrite($importStockSql);
        }


        $this->file->fwrite(PHP_EOL);
        if (!empty($updateProductValues)) {
            foreach ($updateProductValues as $updateProductValue) {
                $this->file->fwrite($updateProductValue);
                $this->file->fwrite(PHP_EOL);
            }
        }

        return true;
    }


    /**
     * dtb_tagのinsert文を作成
     *
     * @return bool
     */
    public function writeProductTagSql()
    {
        $sql = 'SELECT * FROM dtb_product_status ps INNER JOIN dtb_products p ON ps.product_id = p.product_id ORDER BY ps.product_status_id ASC;';

        $rows = Common::fetch($this->conn, $sql);

        if (!$rows) {
            return false;
        }

        $lastItem = end($rows);

        // insert文
        $sql = 'INSERT INTO dtb_product_tag (product_tag_id, product_id, tag, creator_id, create_date) VALUES'.PHP_EOL;
        $this->file->fwrite($sql);

        $product_tag_id = 1;

        foreach ($rows as $item) {

            $id = $product_tag_id;
            // $creator_id = $item['creator_id'];
            $creator_id = 2;
            $create_date = "'".$item['create_date']."'";

            $tag = $item['product_status_id'];
            $product_id = $item['product_id'];

            $values = "($id, $product_id, $tag, $creator_id, $create_date)";
            if ($item == $lastItem) {
                $this->file->fwrite($values.';'.PHP_EOL);
            } else {
                $this->file->fwrite($values.','.PHP_EOL);
            }

            $product_tag_id++;
        }

        return true;
    }

}
