<?php
require __DIR__ . '/khoiTao.php';
$duLieu = duLieuGuiLen();
$tenDangNhap = strtoupper(trim($duLieu['username'] ?? ''));
$matKhau = $duLieu['password'] ?? '';
if (!$tenDangNhap || !$matKhau) traVeJson(['loi' => 'Vui lòng nhập mã đăng nhập và mật khẩu.'], 422);
$lenh = ketNoiCSDL()->prepare('SELECT nd.maNguoiDung, nd.tenDangNhap, nd.matKhau, nd.hoTen, nd.thuDienTu, nd.soDienThoai, nd.ngaySinh, nd.trangThai, vt.ma, vt.ten FROM nguoiDung nd JOIN vaiTro vt ON vt.maVaiTro = nd.maVaiTro WHERE nd.tenDangNhap = ?');
$lenh->execute([$tenDangNhap]);
$nguoiDung = $lenh->fetch(PDO::FETCH_ASSOC);
if (!$nguoiDung || !$nguoiDung['matKhau'] || !password_verify($matKhau, $nguoiDung['matKhau'])) traVeJson(['loi' => 'Mã đăng nhập hoặc mật khẩu chưa đúng.'], 401);
if ($nguoiDung['trangThai'] === 'tamKhoa') traVeJson(['loi' => 'Tài khoản của bạn đang bị tạm khóa. Vui lòng liên hệ thủ thư để được hỗ trợ.'], 403);
if ($nguoiDung['trangThai'] !== 'hoatDong') traVeJson(['loi' => 'Tài khoản của bạn chưa được cấp quyền đăng nhập. Vui lòng liên hệ quản trị viên.'], 403);
session_regenerate_id(true);
$vaiTro = ['DOCGIA' => 'reader', 'THUTHU' => 'librarian', 'ADMIN' => 'admin'][$nguoiDung['ma']] ?? '';
$_SESSION['library_user'] = ['id' => (int)$nguoiDung['maNguoiDung'], 'username' => $nguoiDung['tenDangNhap'], 'name' => $nguoiDung['hoTen'], 'initials' => mb_strtoupper(mb_substr($nguoiDung['hoTen'], 0, 1)), 'role_name' => $nguoiDung['ten'], 'email' => $nguoiDung['thuDienTu'], 'phone' => $nguoiDung['soDienThoai'], 'birth_date' => $nguoiDung['ngaySinh']];
$_SESSION['role'] = $vaiTro;
traVeJson(['thanhCong' => true, 'chuyenTrang' => 'index.php']);
