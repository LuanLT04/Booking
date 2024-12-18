<?php
// File: app/models/Booking.php
// require_once "Database.php"; // Đảm bảo chỉ require một lần duy nhất

class Booking extends Database {
    protected $table = "Bookings";

     // Tạo đặt vé mới
     public function createBooking($data) {
        // Chuẩn bị câu lệnh SQL INSERT
        $query = "INSERT INTO " . $this->table . " (UserID, FlightID, SeatsBooked, TotalPrice, PaymentStatus) 
                  VALUES ({$data['user_id']}, {$data['flight_id']}, {$data['seats_booked']}, {$data['total_price']}, '{$data['payment_status']}')";

        // Thực thi câu lệnh
        return $this->execute($query);  // Dùng phương thức execute từ lớp Database
    }

    // Phương thức tính tổng doanh thu
    public function sum($sql) {
        // Thực thi câu lệnh SQL và lấy kết quả
        $result = self::$connection->query($sql);
        
        // Kiểm tra xem truy vấn có thành công không
        if ($result) {
            $row = $result->fetch_row();  // Lấy dữ liệu dạng mảng từ dòng kết quả
            return $row[0];  // Trả về giá trị tổng doanh thu
        }
        return 0;  // Nếu truy vấn không thành công, trả về 0
    }
}
