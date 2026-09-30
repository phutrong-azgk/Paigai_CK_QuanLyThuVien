<?php
require __DIR__ . '/khoiTao.php';
$duLieu = duLieuGuiLen();
$maSo = strtoupper(trim($duLieu['code'] ?? ''));
$hoTen = trim($duLieu['name'] ?? '');
$email = trim($duLieu['email'] ?? '');
$dienThoai = trim($duLieu['phone'] ?? '');
$ngaySinh = $duLieu['birth_date'] ?? '';
$matKhau = $duLieu['password'] ?? '';
if (!preg_match('/^[A-Z0-9]{5,16}$/', $maSo) || strlen($hoTen) < 3 || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($matKhau) < 8) traVeJson(['loi' => 'Thông tin đăng ký chưa hợp lệ; mật khẩu cần ít nhất 8 ký tự.'], 422);
$csdl = ketNoiCSDL();
try {
    $csdl->beginTransaction();
    $vaiTro = $csdl->query("SELECT maVaiTro FROM vaiTro WHERE ma = 'DOCGIA'")->fetchColumn();
    $lenh = $csdl->prepare('INSERT INTO nguoiDung (maVaiTro, tenDangNhap, matKhau, hoTen, thuDienTu, soDienThoai, ngaySinh, trangThai, ngayTao) VALUES (?, ?, ?, ?, ?, ?, ?, "hoatDong", NOW())');
    $lenh->execute([$vaiTro, $maSo, password_hash($matKhau, PASSWORD_DEFAULT), $hoTen, $email, $dienThoai, $ngaySinh]);
    $maNguoiDung = $csdl->lastInsertId();
    $lenh = $csdl->prepare('INSERT INTO hoSoDocGia (maNguoiDung, maSo, loaiDocGia, trangThaiThe) VALUES (?, ?, "sinhVien", "hoatDong")');
    $lenh->execute([$maNguoiDung, $maSo]);
    $csdl->commit();
    traVeJson(['thanhCong' => true, 'maSo' => $maSo]);
} catch (PDOException $loi) {
    if ($csdl->inTransaction()) $csdl->rollBack();
    traVeJson(['loi' => 'Mã số hoặc email đã tồn tại.'], 409);
}
