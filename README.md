# LIBRA - Hệ thống quản lý thư viện

Ứng dụng PHP và MySQL dành cho ba vai trò: độc giả, thủ thư và quản trị viên.

## Yêu cầu

- Apache có PHP và phần mở rộng PDO MySQL.
- MySQL 8 trở lên.
- Trình duyệt hiện đại.

Mặc định ứng dụng kết nối đến MySQL bằng các giá trị sau:

```text
DB_HOST=127.0.0.1
DB_PORT=3306
DB_NAME=thuVienLibra
DB_USER=root
DB_PASS=
```

Nếu MySQL của bạn khác các giá trị trên, tạo hoặc sửa biến môi trường tương ứng rồi khởi động lại Apache. Để tạo mật khẩu cho quản trị viên đầu tiên, cần thêm biến `APP_SETUP_TOKEN`.

## Cài đặt

1. Chép thư mục dự án vào thư mục của Laragon.
2. Khởi động Laragon.
3. Chạy file `cauTrucCSDL.sql`.
4. Mở `http://localhost:8080/login.php`. Dùng nút đăng nhập nhanh.

Optional: Nếu chưa có mật khẩu quản trị viên, mở `khoiTaoAdmin.php`, nhập `APP_SETUP_TOKEN`, mã đăng nhập và mật khẩu mới.

File `cauTrucCSDL.sql` là bản sao cơ sở dữ liệu hiện tại, đã có cấu trúc và dữ liệu thử nghiệm. Không chạy thêm `duLieuMau.sql` trên cùng cơ sở dữ liệu này vì có thể tạo dữ liệu trùng hoặc không còn khớp cấu trúc mới.


## Đăng nhập và tài khoản

- Độc giả tự đăng ký tại `dangKy.php`. Mã số được dùng làm tên đăng nhập.
- Quản trị viên tạo tài khoản thủ thư, khóa hoặc mở khóa tài khoản và cấp lại mật khẩu.
- `dangNhapNhanh.php` chỉ hỗ trợ thử giao diện trên máy cục bộ. Có thể xóa file này khi không cần nữa.
- Mật khẩu được lưu bằng `password_hash` và kiểm tra bằng `password_verify`.

## Chức năng theo vai trò

### Độc giả

- Tìm kiếm và xem chi tiết tài liệu.
- Gửi yêu cầu mượn, hủy yêu cầu đang chờ duyệt và gửi yêu cầu gia hạn.
- Theo dõi tài liệu đang mượn, lịch sử hoạt động mượn, yêu cầu bị từ chối và tình trạng tài liệu khi trả.
- Cập nhật hồ sơ cá nhân.

### Thủ thư

- Quản lý tài liệu, nhà xuất bản, ảnh bìa và tóm tắt.
- Lập phiếu mượn trực tiếp hoặc duyệt yêu cầu từ độc giả.
- Tiếp nhận trả sách, ghi nhận tình trạng và tạo phiếu phạt quá hạn hoặc hư hỏng.
- Quản lý hồ sơ độc giả, yêu cầu gia hạn và thu phạt.

### Quản trị viên

- Quản lý tài khoản, khoa và chính sách mượn.
- Thiết lập số sách tối đa, hạn mượn, gia hạn, phạt quá hạn và phạt hư hỏng.
- Theo dõi tổng quan và nhật ký hoạt động hệ thống.

## API chính

Các API yêu cầu phiên đăng nhập và tự kiểm tra đúng vai trò trước khi thực hiện thao tác.

| Nhóm                 | File API                                            | Mục đích 
| -------------------- | -----------------------------------------------     | --- 
| Đăng nhập và đăng ký | `api/dangNhap.php`, `api/dangKy.php`                | Xác thực và tạo tài khoản độc giả 
| Độc giả              | `api/docGia.php`                                    | Tài liệu, hồ sơ, mượn, gia hạn và lịch sử 
| Thủ thư              | `api/thuThu.php`, `api/thuThuSach.php`              | Lưu thông, phạt, tài liệu và nhà xuất bản 
| Quản trị             | `api/quanTriTaiKhoan.php`, `api/quanTriHeThong.php` | Tài khoản, khoa và chính sách 
| Khởi tạo             | `api/khoiTaoAdmin.php`                              | Thiết lập quản trị viên đầu tiên 

## Lưu ý dữ liệu

- Ảnh bìa tài liệu được lưu trong `assets/images/sach/`; cơ sở dữ liệu chỉ lưu đường dẫn tương đối.
- Tồn kho hiện được lưu trực tiếp trên bảng `taiLieu` qua `soLuongTong` và `soLuongCon`.
- Mức phạt lưu là số nguyên đồng Việt Nam. Giao diện chỉ thêm dấu chấm để dễ đọc, ví dụ `100.000`; giá trị lưu trong MySQL là `100000`.
- Chính sách được lưu theo phiên bản. Phiếu mượn giữ mã chính sách đã áp dụng để tính đúng hạn trả và tiền phạt.
