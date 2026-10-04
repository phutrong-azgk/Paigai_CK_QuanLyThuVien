<?php
require __DIR__ . '/khoiTao.php';
nguoiDungHienTai('admin');
$csdl = ketNoiCSDL();
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $lenh = $csdl->query("SELECT nd.maNguoiDung, nd.tenDangNhap, nd.hoTen, nd.thuDienTu, nd.trangThai, vt.ma AS maVaiTro, vt.ten AS vaiTro FROM nguoiDung nd JOIN vaiTro vt ON vt.maVaiTro = nd.maVaiTro WHERE vt.ma <> 'ADMIN' ORDER BY nd.maNguoiDung DESC");
    traVeJson(['duLieu' => $lenh->fetchAll(PDO::FETCH_ASSOC)]);
}
$duLieu = duLieuGuiLen();
$hanhDong = $duLieu['hanhDong'] ?? '';
if ($hanhDong === 'doiMatKhauCuaToi') {
    $matKhauHienTai = $duLieu['matKhauHienTai'] ?? '';
    $matKhauMoi = $duLieu['matKhauMoi'] ?? '';
    if (strlen($matKhauMoi) < 8) traVeJson(['loi' => 'Mật khẩu mới cần ít nhất 8 ký tự.'], 422);
    $nguoiDung = nguoiDungHienTai('admin');
    $lenh = $csdl->prepare('SELECT matKhau FROM nguoiDung WHERE maNguoiDung = ?');
    $lenh->execute([$nguoiDung['id']]);
    $matKhauCu = $lenh->fetchColumn();
    if (!$matKhauCu || !password_verify($matKhauHienTai, $matKhauCu)) traVeJson(['loi' => 'Mật khẩu hiện tại chưa đúng.'], 422);
    $lenh = $csdl->prepare('UPDATE nguoiDung SET matKhau = ? WHERE maNguoiDung = ?');
    $lenh->execute([password_hash($matKhauMoi, PASSWORD_DEFAULT), $nguoiDung['id']]);
    traVeJson(['thanhCong' => true]);
}
if ($hanhDong === 'tao') {
    $maVaiTro = strtoupper($duLieu['maVaiTro'] ?? '');
    $tenDangNhap = strtoupper(trim($duLieu['tenDangNhap'] ?? ''));
    $hoTen = trim($duLieu['hoTen'] ?? '');
    $email = trim($duLieu['thuDienTu'] ?? '');
    $matKhau = $duLieu['matKhau'] ?? '';
    $loaiDocGia = $duLieu['loaiDocGia'] ?? 'sinhVien';
    if (!in_array($maVaiTro, ['THUTHU', 'DOCGIA'], true) || !in_array($loaiDocGia, ['sinhVien', 'giangVien'], true) || !preg_match('/^[A-Z0-9]{5,30}$/', $tenDangNhap) || strlen($hoTen) < 3 || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($matKhau) < 8) traVeJson(['loi' => 'Thông tin tài khoản chưa hợp lệ.'], 422);
    try {
        $csdl->beginTransaction();
        $vaiTro = $csdl->prepare('SELECT maVaiTro FROM vaiTro WHERE ma = ?');
        $vaiTro->execute([$maVaiTro]);
        $lenh = $csdl->prepare('INSERT INTO nguoiDung (maVaiTro, tenDangNhap, matKhau, hoTen, thuDienTu, trangThai, ngayTao) VALUES (?, ?, ?, ?, ?, "hoatDong", NOW())');
        $lenh->execute([$vaiTro->fetchColumn(), $tenDangNhap, password_hash($matKhau, PASSWORD_DEFAULT), $hoTen, $email]);
        if ($maVaiTro === 'DOCGIA') {
            $hoSo = $csdl->prepare('INSERT INTO hoSoDocGia (maNguoiDung, maSo, loaiDocGia, trangThaiThe) VALUES (?, ?, ?, "hoatDong")');
            $hoSo->execute([$csdl->lastInsertId(), $tenDangNhap, $loaiDocGia]);
        }
        $csdl->commit();
        traVeJson(['thanhCong' => true]);
    } catch (PDOException $loi) {
        if ($csdl->inTransaction()) $csdl->rollBack();
        traVeJson(['loi' => 'Mã đăng nhập hoặc email đã tồn tại.'], 409);
    }
}
if ($hanhDong === 'capLaiMatKhau') {
    $maNguoiDung = (int)($duLieu['maNguoiDung'] ?? 0);
    $matKhau = $duLieu['matKhau'] ?? '';
    if (!$maNguoiDung || strlen($matKhau) < 8) traVeJson(['loi' => 'Mật khẩu cần ít nhất 8 ký tự.'], 422);
    $lenh = $csdl->prepare('SELECT vt.ma FROM nguoiDung nd JOIN vaiTro vt ON vt.maVaiTro = nd.maVaiTro WHERE nd.maNguoiDung = ?');
    $lenh->execute([$maNguoiDung]);
    if ($lenh->fetchColumn() === 'ADMIN') traVeJson(['loi' => 'Quản trị viên phải tự đổi mật khẩu bằng mật khẩu hiện tại.'], 403);
    $lenh = $csdl->prepare('UPDATE nguoiDung SET matKhau = ? WHERE maNguoiDung = ?');
    $lenh->execute([password_hash($matKhau, PASSWORD_DEFAULT), $maNguoiDung]);
    traVeJson(['thanhCong' => $lenh->rowCount() > 0]);
}
if ($hanhDong === 'capNhatTrangThai') {
    $maNguoiDung = (int)($duLieu['maNguoiDung'] ?? 0);
    $trangThai = $duLieu['trangThai'] ?? '';
    if (!in_array($trangThai, ['hoatDong', 'tamKhoa'], true)) traVeJson(['loi' => 'Trạng thái không hợp lệ.'], 422);
    $lenh = $csdl->prepare('SELECT vt.ma FROM nguoiDung nd JOIN vaiTro vt ON vt.maVaiTro = nd.maVaiTro WHERE nd.maNguoiDung = ?');
    $lenh->execute([$maNguoiDung]);
    if ($lenh->fetchColumn() === 'ADMIN') traVeJson(['loi' => 'Không thể thay đổi trạng thái tài khoản Quản trị viên.'], 403);
    $lenh = $csdl->prepare('UPDATE nguoiDung SET trangThai = ? WHERE maNguoiDung = ?');
    $lenh->execute([$trangThai, $maNguoiDung]);
    traVeJson(['thanhCong' => $lenh->rowCount() > 0]);
}
if ($hanhDong === 'xoa') {
    $maNguoiDung = (int)($duLieu['maNguoiDung'] ?? 0);
    if (!$maNguoiDung) traVeJson(['loi' => 'Tài khoản không hợp lệ.'], 422);
    try {
        $csdl->beginTransaction();
        $lenh = $csdl->prepare('SELECT vt.ma FROM nguoiDung nd JOIN vaiTro vt ON vt.maVaiTro = nd.maVaiTro WHERE nd.maNguoiDung = ?');
        $lenh->execute([$maNguoiDung]);
        $maVaiTro = $lenh->fetchColumn();
        if (!$maVaiTro) {
            $csdl->rollBack();
            traVeJson(['loi' => 'Không tìm thấy tài khoản.'], 404);
        }
        if ($maVaiTro === 'ADMIN') {
            $csdl->rollBack();
            traVeJson(['loi' => 'Không thể xóa tài khoản Quản trị viên.'], 403);
        }
        if ($maVaiTro === 'DOCGIA') {
            $lenh = $csdl->prepare('DELETE FROM hoSoDocGia WHERE maNguoiDung = ?');
            $lenh->execute([$maNguoiDung]);
        }
        $lenh = $csdl->prepare('DELETE FROM nguoiDung WHERE maNguoiDung = ?');
        $lenh->execute([$maNguoiDung]);
        if (!$lenh->rowCount()) {
            $csdl->rollBack();
            traVeJson(['loi' => 'Không thể xóa tài khoản.'], 422);
        }
        $csdl->commit();
        traVeJson(['thanhCong' => true]);
    } catch (PDOException $loi) {
        if ($csdl->inTransaction()) $csdl->rollBack();
        traVeJson(['loi' => 'Không thể xóa tài khoản đã phát sinh dữ liệu nghiệp vụ. Hãy tạm khóa thay vì xóa.'], 409);
    }
}
traVeJson(['loi' => 'API không hợp lệ.'], 404);
