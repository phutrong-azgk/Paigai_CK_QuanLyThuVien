<?php
require __DIR__ . '/khoiTao.php';
$csdl = ketNoiCSDL();
$daCoAdmin = $csdl->query("SELECT COUNT(*) FROM nguoiDung nd JOIN vaiTro vt ON vt.maVaiTro = nd.maVaiTro WHERE vt.ma = 'ADMIN' AND nd.matKhau IS NOT NULL")->fetchColumn();
if ($daCoAdmin) traVeJson(['loi' => 'Đã có tài khoản Admin hoạt động.'], 403);
$duLieu = duLieuGuiLen();
$token = getenv('APP_SETUP_TOKEN');
if (!$token) traVeJson(['loi' => 'Chưa cấu hình APP_SETUP_TOKEN.'], 500);
if (!hash_equals($token, $duLieu['token'] ?? '')) traVeJson(['loi' => 'Token khởi tạo không đúng.'], 403);
$tenDangNhap = strtoupper(trim($duLieu['tenDangNhap'] ?? ''));
$matKhau = $duLieu['matKhau'] ?? '';
if (!preg_match('/^[A-Z0-9]{5,30}$/', $tenDangNhap) || strlen($matKhau) < 12) traVeJson(['loi' => 'Mã Admin hoặc mật khẩu chưa hợp lệ.'], 422);
$vaiTro = $csdl->query("SELECT maVaiTro FROM vaiTro WHERE ma = 'ADMIN'")->fetchColumn();
$lenh = $csdl->prepare('UPDATE nguoiDung SET tenDangNhap = ?, matKhau = ?, trangThai = "hoatDong" WHERE maVaiTro = ? LIMIT 1');
$lenh->execute([$tenDangNhap, password_hash($matKhau, PASSWORD_DEFAULT), $vaiTro]);
traVeJson(['thanhCong' => true]);
