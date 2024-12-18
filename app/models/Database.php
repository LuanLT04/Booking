<?php
// app/models/Database.php

class Database
{
    // Biến static giữ kết nối cơ sở dữ liệu
    public static $connection = NULL;

    // Hàm khởi tạo kết nối
    public static function connect()
    {
        if (self::$connection === NULL) {
            // Sử dụng các hằng số cấu hình của cơ sở dữ liệu
            self::$connection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
            if (self::$connection->connect_error) {
                die("Connection failed: " . self::$connection->connect_error);
            }
            // Thiết lập bộ mã hóa ký tự
            self::$connection->set_charset('utf8mb4');
        }
        return self::$connection;
    }

    public static function select($sql, $params = [])
    {
        $connection = self::connect(); // Kết nối cơ sở dữ liệu
        $stmt = $connection->prepare($sql);
        if ($params) {
            $stmt->bind_param(str_repeat('s', count($params)), ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : null;
    }


    // Hàm thực hiện câu lệnh INSERT, UPDATE, DELETE
    public static function execute($query, $params = [])
    {
        $connection = self::connect(); // Kết nối cơ sở dữ liệu
        $stmt = $connection->prepare($query);
        if ($params) {
            // Bind các tham số vào câu lệnh
            $stmt->bind_param(str_repeat('s', count($params)), ...$params);
        }
        return $stmt->execute();
    }
}
