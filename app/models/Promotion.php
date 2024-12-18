<?php

class Promotion extends Database
{
    // Hàm lấy tất cả các mã giảm giá từ cơ sở dữ liệu
    public function getAllPromotions()
    {
        $sql = "SELECT * FROM Promotions WHERE Status = 'Active'"; // Truy vấn lấy mã giảm giá đang hoạt động
        return $this->select($sql); // Gọi phương thức select từ lớp cha
    }

    // Hàm lấy một mã giảm giá theo mã của nó
    public function getPromotionByCode($promoCode)
    {
        $sql = "SELECT * FROM Promotions WHERE PromoCode = ? AND Status = 'Active'";
        return $this->select($sql, [$promoCode]);
    }

    // Thêm phiếu giảm giá
    public function createPromotion($promoCode, $discount, $description, $expiryDate)
    {
        $startDate = date("Y-m-d H:i:s"); // Ngày bắt đầu là thời gian hiện tại
        $status = 'Active'; // Trạng thái mặc định

        $query = "INSERT INTO Promotions (PromoCode, Discount, StartDate, EndDate, Status, Description) 
                  VALUES (?, ?, ?, ?, ?, ?)";

        return Database::execute($query, [$promoCode, $discount, $startDate, $expiryDate, $status, $description]);
    }


    // Hàm cập nhật mã giảm giá
    public function updatePromotion($id, $promoCode, $discount, $description, $expiryDate)
    {
        $query = "UPDATE Promotions SET 
                    PromoCode = ?, 
                    Discount = ?, 
                    Description = ?, 
                    EndDate = ? 
                  WHERE PromoID = ?";

        return $this->execute($query, [$promoCode, $discount, $description, $expiryDate, $id]);
    }

    // Hàm xóa mã giảm giá
    public function deletePromotion($promoId)
    {
        $query = "DELETE FROM Promotions WHERE PromoID = ?";
        return $this->execute($query, [$promoId]);
    }

    public function getPromotionById($id)
    {
        $sql = "SELECT * FROM Promotions WHERE PromoID = ?";
        $result = $this->select($sql, [$id]);
        return $result ? $result[0] : null;  // Trả về kết quả hoặc null nếu không tìm thấy
    }


    // Thoát ký tự trong chuỗi để tránh SQL Injection
    private function escapeString($string)
    {
        return self::$connection->real_escape_string($string);
    }
}
