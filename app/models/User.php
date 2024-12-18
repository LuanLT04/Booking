<?php
// File: app/models/User.php

class User extends Database
{
    protected $table = "Users";

    // Lấy tất cả người dùng
    public function getAll()
    {
        $sql = "SELECT * FROM " . $this->table;
        return $this->select($sql);  // Sử dụng phương thức select từ lớp Database
    }

    public function getAccount($email)
    {
        // Sử dụng PDO chuẩn
        $sql = self::$connection->prepare("SELECT * FROM `users` WHERE `Email` = :email");
        $sql->bindParam(":email", $email, PDO::PARAM_STR);
        $sql->execute();
        $user = $sql->fetch(PDO::FETCH_ASSOC); // Lấy dữ liệu dưới dạng mảng liên kết
        return $user;
    }

      // Phương thức đếm số lượng người dùng
      public function count($query)
      {
          // Thực thi câu lệnh SQL
          $result = self::$connection->query($query);
  
          // Kiểm tra xem câu lệnh có trả về kết quả không
          if ($result) {
              // Lấy giá trị cột đầu tiên từ dòng kết quả (số lượng người dùng)
              $row = $result->fetch_row();
              return $row[0];  // Trả về giá trị cột đầu tiên (số lượng)
          }
          return 0;  // Nếu truy vấn thất bại, trả về 0
      }
}

