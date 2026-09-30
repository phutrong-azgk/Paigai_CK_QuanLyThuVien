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
        $lenh = $csdl->query('SELECT dm.maDanhMuc, dm.ma, dm.ten, COUNT(tl.maTaiLieu) AS soTaiLieu FROM danhMucTaiLieu dm LEFT JOIN taiLieu tl ON tl.maDanhMuc = dm.maDanhMuc GROUP BY dm.maDanhMuc ORDER BY dm.ten');
        traVeJson(['duLieu' => $lenh->fetchAll(PDO::FETCH_ASSOC)]);
    }
    if ($loai === 'chinhSach') {
        $lenh = $csdl->query('SELECT * FROM chinhSachMuon ORDER BY loaiDocGia, ngayApDung DESC, maChinhSach DESC');
        traVeJson(['duLieu' => $lenh->fetchAll(PDO::FETCH_ASSOC)]);
    }
    if ($loai === 'tongQuan') {
        $taiKhoan = $csdl->query('SELECT COUNT(*) FROM nguoiDung')->fetchColumn();
        $taiLieu = $csdl->query('SELECT COUNT(*) FROM taiLieu')->fetchColumn();
        $danhMuc = $csdl->query('SELECT COUNT(*) FROM danhMucTaiLieu')->fetchColumn();
        $canXuLy = $csdl->query("SELECT COUNT(*) FROM yeuCauMuon WHERE trangThai = 'choDuyet'")->fetchColumn();
        $lenh = $csdl->query('SELECT nk.hanhDong, nk.doiTuong, nk.ngayTao, nd.hoTen FROM nhatKyHeThong nk LEFT JOIN nguoiDung nd ON nd.maNguoiDung = nk.maNguoiDung ORDER BY nk.ngayTao DESC LIMIT 5');
        traVeJson(['duLieu' => compact('taiKhoan', 'taiLieu', 'danhMuc', 'canXuLy'), 'nhatKy' => $lenh->fetchAll(PDO::FETCH_ASSOC)]);
    }
    traVeJson(['loi' => 'Dữ liệu không hợp lệ.'], 404);
}

$duLieu = duLieuGuiLen();
$hanhDong = $duLieu['hanhDong'] ?? '';
if ($hanhDong === 'luuDanhMuc') {
    $maDanhMuc = (int)($duLieu['maDanhMuc'] ?? 0);
    $ma = strtoupper(trim($duLieu['ma'] ?? ''));
    $ten = trim($duLieu['ten'] ?? '');
    if (!preg_match('/^[A-Z0-9-]{2,30}$/', $ma) || strlen($ten) < 2) traVeJson(['loi' => 'Mã và tên danh mục chưa hợp lệ.'], 422);
    try {
        if ($maDanhMuc) { $lenh = $csdl->prepare('UPDATE danhMucTaiLieu SET ma = ?, ten = ? WHERE maDanhMuc = ?'); $lenh->execute([$ma, $ten, $maDanhMuc]); }
        else { $lenh = $csdl->prepare('INSERT INTO danhMucTaiLieu (ma, ten) VALUES (?, ?)'); $lenh->execute([$ma, $ten]); $maDanhMuc = $csdl->lastInsertId(); }
        ghiNhatKyQuanTri($csdl, $nguoiDung['id'], 'capNhatDanhMuc', 'danhMucTaiLieu', $maDanhMuc);
        traVeJson(['thanhCong' => true]);
    } catch (PDOException $loi) { traVeJson(['loi' => 'Mã danh mục đã tồn tại.'], 409); }
}
if ($hanhDong === 'xoaDanhMuc') {
    try { $lenh = $csdl->prepare('DELETE FROM danhMucTaiLieu WHERE maDanhMuc = ?'); $lenh->execute([(int)($duLieu['maDanhMuc'] ?? 0)]); if (!$lenh->rowCount()) traVeJson(['loi' => 'Không tìm thấy danh mục.'], 404); traVeJson(['thanhCong' => true]); }
    catch (PDOException $loi) { traVeJson(['loi' => 'Danh mục đang có tài liệu hoặc danh mục con nên không thể xóa.'], 409); }
}
if ($hanhDong === 'luuChinhSach') {
    $loaiDocGia = $duLieu['loaiDocGia'] ?? '';
    $soSach = (int)($duLieu['soSachToiDa'] ?? 0); $soNgay = (int)($duLieu['soNgayMuon'] ?? 0); $soLan = (int)($duLieu['soLanGiaHan'] ?? 0); $tienPhat = (float)($duLieu['tienPhatMoiNgay'] ?? 0);
    if (!in_array($loaiDocGia, ['sinhVien', 'giangVien'], true) || $soSach < 1 || $soNgay < 1 || $soLan < 0 || $tienPhat < 0) traVeJson(['loi' => 'Thông số chính sách chưa hợp lệ.'], 422);
    $csdl->beginTransaction();
    try {
        $lenh = $csdl->prepare("UPDATE chinhSachMuon SET trangThai = 'ngungApDung' WHERE loaiDocGia = ? AND trangThai = 'dangApDung'"); $lenh->execute([$loaiDocGia]);
        $lenh = $csdl->prepare("INSERT INTO chinhSachMuon (loaiDocGia, soSachToiDa, soNgayMuon, soLanGiaHan, tienPhatMoiNgay, ngayApDung, trangThai) VALUES (?, ?, ?, ?, ?, CURDATE(), 'dangApDung')");
        $lenh->execute([$loaiDocGia, $soSach, $soNgay, $soLan, $tienPhat]); $maChinhSach = $csdl->lastInsertId();
        ghiNhatKyQuanTri($csdl, $nguoiDung['id'], 'capNhatChinhSach', 'chinhSachMuon', $maChinhSach);
        $csdl->commit(); traVeJson(['thanhCong' => true]);
    } catch (PDOException $loi) { if ($csdl->inTransaction()) $csdl->rollBack(); traVeJson(['loi' => 'Không thể lưu chính sách.'], 500); }
}
traVeJson(['loi' => 'Thao tác không hợp lệ.'], 404);
