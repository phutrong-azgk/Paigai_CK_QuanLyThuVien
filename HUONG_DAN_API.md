# Ket noi MySQL va API

1. Tao co so du lieu bang `cauTrucCSDL.sql`.
2. Chay `duLieuMau.sql`.
3. Mở `cauHinh.local.php` trong thư mục dự án và sửa thông tin MySQL nếu cần:

```php
return [
  'dbHost' => '127.0.0.1',
  'dbPort' => '3306',
  'dbName' => 'thuVienLibra',
  'dbUser' => 'root',
  'dbPassword' => '',
  'appSetupToken' => 'doi-token-nay',
];
```

API dang nhap va dang ky tu dong dung MySQL. Tai khoan trong `duLieuMau.sql` khong co mat khau de dam bao du lieu mau khong luu mat khau ro.

Sau khi nạp dữ liệu mẫu, mở `khoiTaoAdmin.php`, nhập đúng `appSetupToken` trong file cấu hình và đặt tài khoản Admin đầu tiên. Biểu mẫu và API khởi tạo sẽ tự vô hiệu sau khi Admin đã có mật khẩu. Đăng nhập bằng tài khoản đó để dùng mục **Quản lý tài khoản**, nơi có thể tạo tài khoản độc giả/thủ thư, cấp lại mật khẩu và tạm khóa/mở khóa tài khoản.

API doc gia:

- `api/dangKy.php`
- `api/dangNhap.php`
- `api/docGia.php?hanhDong=taiLieu`
- `api/docGia.php?hanhDong=phieuMuon`
- `api/docGia.php?hanhDong=phieuMuon&loai=lichSu`
- `api/docGia.php?hanhDong=yeuCauMuon`
- `api/quanTriTaiKhoan.php`
- `api/quanTriHeThong.php?loai=danhMuc`
- `api/quanTriHeThong.php?loai=chinhSach`
