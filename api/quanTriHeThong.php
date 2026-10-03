<?php
require __DIR__ . '/khoiTao.php';
$nguoiDung = nguoiDungHienTai('admin');
$csdl = ketNoiCSDL();

function ghiNhatKyQuanTri($csdl, $maNguoiDung, $hanhDong, $doiTuong, $maDoiTuong) {
    $lenh = $csdl->prepare('INSERT INTO nhatKyHeThong (maNguoiDung, hanhDong, doiTuong, maDoiTuong, ngayTao) VALUES (?, ?, ?, ?, NOW())');
    $lenh->execute([$maNguoiDung, $hanhDong, $doiTuong, $maDoiTuong]);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $loai = $_GET['loai'] ?? '';
    if ($loai === 'danhMuc') {
        $lenh = $csdl->query('SELECT k.maKhoa AS maDanhMuc, k.ma, k.ten, COUNT(tl.maTaiLieu) AS soTaiLieu FROM khoa k LEFT JOIN taiLieu tl ON tl.maKhoa = k.maKhoa GROUP BY k.maKhoa ORDER BY k.ten');
        traVeJson(['duLieu' => $lenh->fetchAll(PDO::FETCH_ASSOC)]);
    }
    if ($loai === 'chinhSach') {
        $lenh = $csdl->query('SELECT * FROM chinhSachMuon ORDER BY loaiDocGia, ngayApDung DESC, maChinhSach DESC');
        traVeJson(['duLieu' => $lenh->fetchAll(PDO::FETCH_ASSOC)]);
    }
    if ($loai === 'tongQuan') {
        $taiKhoan = $csdl->query('SELECT COUNT(*) FROM nguoiDung')->fetchColumn();
        $taiKhoanHoatDong = $csdl->query("SELECT COUNT(*) FROM nguoiDung WHERE trangThai = 'hoatDong'")->fetchColumn();
        $taiLieu = $csdl->query('SELECT COUNT(*) FROM taiLieu')->fetchColumn();
        $danhMuc = $csdl->query('SELECT COUNT(*) FROM khoa')->fetchColumn();
        $yeuCauChoDuyet = $csdl->query("SELECT COUNT(*) FROM yeuCauMuon WHERE trangThai = 'choDuyet'")->fetchColumn();
        $taiKhoanTamKhoa = $csdl->query("SELECT COUNT(*) FROM nguoiDung WHERE trangThai = 'tamKhoa'")->fetchColumn();
        $taiLieuHet = $csdl->query('SELECT COUNT(*) FROM taiLieu WHERE soLuongCon <= 0')->fetchColumn();
        $chinhSachDangApDung = $csdl->query("SELECT loaiDocGia FROM chinhSachMuon WHERE trangThai = 'dangApDung'")->fetchAll(PDO::FETCH_COLUMN);
        $canhBao = [];
        if ($yeuCauChoDuyet) $canhBao[] = ['soLuong' => $yeuCauChoDuyet, 'noiDung' => 'yêu cầu mượn đang chờ thủ thư duyệt.'];
        if ($taiKhoanTamKhoa) $canhBao[] = ['soLuong' => $taiKhoanTamKhoa, 'noiDung' => 'tài khoản đang tạm khóa và cần kiểm tra.'];
        if ($taiLieuHet) $canhBao[] = ['soLuong' => $taiLieuHet, 'noiDung' => 'đầu tài liệu hiện đã hết bản có thể mượn.'];
        foreach (['sinhVien' => 'sinh viên', 'giangVien' => 'giảng viên'] as $maLoai => $tenLoai) if (!in_array($maLoai, $chinhSachDangApDung, true)) $canhBao[] = ['soLuong' => '!', 'noiDung' => "chưa có chính sách mượn đang áp dụng cho $tenLoai."];
        $canXuLy = $taiKhoanTamKhoa;
        $lenh = $csdl->query('SELECT nk.hanhDong, nk.doiTuong, nk.ngayTao, nd.hoTen FROM nhatKyHeThong nk LEFT JOIN nguoiDung nd ON nd.maNguoiDung = nk.maNguoiDung ORDER BY nk.ngayTao DESC LIMIT 5');
        traVeJson(['duLieu' => compact('taiKhoan', 'taiKhoanHoatDong', 'taiLieu', 'danhMuc', 'yeuCauChoDuyet', 'taiKhoanTamKhoa', 'canXuLy'), 'nhatKy' => $lenh->fetchAll(PDO::FETCH_ASSOC), 'canhBao' => $canhBao]);
    }
    traVeJson(['loi' => 'Dữ liệu không hợp lệ.'], 404);
}

$duLieu = duLieuGuiLen();
$hanhDong = $duLieu['hanhDong'] ?? '';
if ($hanhDong === 'luuDanhMuc') {
    $maDanhMuc = (int)($duLieu['maDanhMuc'] ?? 0);
    $ma = strtoupper(trim($duLieu['ma'] ?? ''));
    $ten = trim($duLieu['ten'] ?? '');
    if (!preg_match('/^[A-Z0-9-]{2,30}$/', $ma) || strlen($ten) < 2) traVeJson(['loi' => 'Mã và tên khoa chưa hợp lệ.'], 422);
    try {
        if ($maDanhMuc) { $lenh = $csdl->prepare('UPDATE khoa SET ma = ?, ten = ? WHERE maKhoa = ?'); $lenh->execute([$ma, $ten, $maDanhMuc]); }
        else { $lenh = $csdl->prepare('INSERT INTO khoa (ma, ten) VALUES (?, ?)'); $lenh->execute([$ma, $ten]); $maDanhMuc = $csdl->lastInsertId(); }
        ghiNhatKyQuanTri($csdl, $nguoiDung['id'], 'capNhatKhoa', 'khoa', $maDanhMuc);
        traVeJson(['thanhCong' => true]);
    } catch (PDOException $loi) { traVeJson(['loi' => 'Mã khoa đã tồn tại.'], 409); }
}
if ($hanhDong === 'xoaDanhMuc') {
    try { $lenh = $csdl->prepare('DELETE FROM khoa WHERE maKhoa = ?'); $lenh->execute([(int)($duLieu['maDanhMuc'] ?? 0)]); if (!$lenh->rowCount()) traVeJson(['loi' => 'Không tìm thấy khoa.'], 404); traVeJson(['thanhCong' => true]); }
    catch (PDOException $loi) { traVeJson(['loi' => 'Khoa đang có tài liệu hoặc hồ sơ độc giả nên không thể xóa.'], 409); }
}
if ($hanhDong === 'luuChinhSach') {
    $loaiDocGia = $duLieu['loaiDocGia'] ?? '';
    $docTien = function ($giaTri) { $chuoi = trim((string)$giaTri); return preg_match('/^\d+\.\d{1,2}$/', $chuoi) ? (int)round((float)$chuoi) : (int)str_replace('.', '', $chuoi); };
    $soSach = (int)($duLieu['soSachToiDa'] ?? 0); $soNgay = (int)($duLieu['soNgayMuon'] ?? 0); $soLan = (int)($duLieu['soLanGiaHan'] ?? 0); $soNgayGiaHan = (int)($duLieu['soNgayGiaHan'] ?? 0); $tienPhat = $docTien($duLieu['tienPhatMoiNgay'] ?? 0); $phatHuHongNhe = $docTien($duLieu['tienPhatHuHongNhe'] ?? 0); $phatHuHongNang = $docTien($duLieu['tienPhatHuHongNang'] ?? 0);
    if (!in_array($loaiDocGia, ['sinhVien', 'giangVien'], true) || $soSach < 1 || $soNgay < 1 || $soLan < 0 || $soNgayGiaHan < 1 || $tienPhat < 0 || $phatHuHongNhe < 0 || $phatHuHongNang < 0) traVeJson(['loi' => 'Thông số chính sách chưa hợp lệ.'], 422);
    $csdl->beginTransaction();
    try {
        $lenh = $csdl->prepare("UPDATE chinhSachMuon SET trangThai = 'ngungApDung' WHERE loaiDocGia = ? AND trangThai = 'dangApDung'"); $lenh->execute([$loaiDocGia]);
        $lenh = $csdl->prepare("INSERT INTO chinhSachMuon (loaiDocGia, soSachToiDa, soNgayMuon, soLanGiaHan, soNgayGiaHan, tienPhatMoiNgay, tienPhatHuHongNhe, tienPhatHuHongNang, ngayApDung, trangThai) VALUES (?, ?, ?, ?, ?, ?, ?, ?, CURDATE(), 'dangApDung')");
        $lenh->execute([$loaiDocGia, $soSach, $soNgay, $soLan, $soNgayGiaHan, $tienPhat, $phatHuHongNhe, $phatHuHongNang]); $maChinhSach = $csdl->lastInsertId();
        ghiNhatKyQuanTri($csdl, $nguoiDung['id'], 'capNhatChinhSach', 'chinhSachMuon', $maChinhSach);
        $csdl->commit(); traVeJson(['thanhCong' => true]);
    } catch (PDOException $loi) { if ($csdl->inTransaction()) $csdl->rollBack(); traVeJson(['loi' => 'Không thể lưu chính sách.'], 500); }
}
traVeJson(['loi' => 'Thao tác không hợp lệ.'], 404);
