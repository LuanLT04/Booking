<?php
// File: app/models/Flight.php
// require_once "Database.php"; // Đảm bảo chỉ require một lần duy nhất

class Flight extends Database
{
    protected $table = "Flights";
    protected $availableSeats;

    // Tạo chuyến bay mới
    public function createFlight($data)
    {
        // Chuẩn bị câu lệnh SQL INSERT
        $query = "INSERT INTO " . $this->table . " (Departure, Destination, DepartureDate, ArrivalDate, TotalSeats, AvailableSeats, PricePerSeat, Status) 
                  VALUES ('{$data['departure']}', '{$data['destination']}', '{$data['departure_date']}', '{$data['arrival_date']}', {$data['total_seats']}, {$data['available_seats']}, {$data['price_per_seat']}, '{$data['status']}')";

        // Thực thi câu lệnh
        return $this->execute($query);  // Dùng phương thức execute từ lớp Database
    }


    // Hàm lấy dữ liệu chuyến bay
    public function getFlights() {
        $query = "SELECT * FROM Flights";
        return $this->select($query);
    }

    // Hàm đếm số lượng chuyến bay
    public function count() {
        // Sử dụng SQL để đếm số lượng chuyến bay
        $query = "SELECT COUNT(*) AS total FROM " . $this->table;
        $result = $this->select($query);
        return $result[0]['total']; // Giả sử kết quả trả về dạng mảng
    }

    // Kiểm tra điều kiện nào đó
    public function someCondition() {
        // Ví dụ: kiểm tra xem chuyến bay có còn trống vé hay không
        return $this->availableSeats > 0; 
    }

      // Cập nhật trạng thái chuyến bay (Duyệt chuyến bay)
      public function updateStatus($flightId, $status)
      {
          // Cập nhật trạng thái chuyến bay
          $query = "UPDATE " . $this->table . " SET Status = '{$status}' WHERE FlightID = {$flightId}";
          return $this->execute($query);
      }
  
      // Xóa chuyến bay
      public function deleteFlight($flightId)
      {
          // Xóa chuyến bay khỏi cơ sở dữ liệu
          $query = "DELETE FROM " . $this->table . " WHERE FlightID = {$flightId}";
          return $this->execute($query);
      }
}
