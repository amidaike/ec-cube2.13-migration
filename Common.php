<?php

require_once('config.php');

class Common
{

    /**
     * 移行元データベースがMySQLかどうか
     *
     * @return bool
     */
    public static function isMySQL()
    {
        if (DB_TYPE == 'mysql') {
            return true;
        }

        return false;
    }

    /**
     * 移行元データベースがPostgreSQLかどうか
     *
     * @return bool
     */
    public static function isPostgres()
    {
        if (DB_TYPE == 'postgres') {
            return true;
        }

        return false;
    }

    /**
     * データベースによりqueryの取得方法を変更
     *
     * @param $conn
     * @param $sql
     * @return resource
     */
    public static function getQueryResult($conn, $sql)
    {
        $result = null;
        if (Common::isMySQL()) {
            $result = $conn->query($sql);
        } elseif (Common::isPostgres()) {
            $result = pg_query($conn, $sql);
        }

        return $result;
    }

    /**
     * データベースによりfetchした結果の取得方法を変更
     *
     * @param $result
     * @return array|null
     */
    public static function getFetch($result)
    {
        $rows = null;
        if (Common::isMySQL()) {
            $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
        } elseif (Common::isPostgres()) {
            $rows = pg_fetch_all($result);
        }

        return $rows;
    }

    /**
     * データベースによりfetch結果を変更して取得
     *
     * @param $conn
     * @param $sql
     * @return array|null
     */
    public static function fetch($conn, $sql)
    {
        $result = Common::getQueryResult($conn, $sql);

        if (!$result) {
            echo "An error occurred in sql: $sql \n";
            exit;
        }

        $rows = Common::getFetch($result);

        return $rows;
    }

}