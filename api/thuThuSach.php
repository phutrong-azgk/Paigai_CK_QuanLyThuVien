<?php
require __DIR__ . '/khoiTao.php';
nguoiDungHienTai('librarian');
$csdl = ketNoiCSDL();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $hanhDong = $_GET['hanhDong'] ?? 'danhSach';
    $trang = max(1, (int)($_GET['trang'] ?? 1));
    $gioiHan = min(50, max(1, (int)($_GET['gioiHan'] ?? 8)));
    $layTatCa = ($_GET['layTatCa'] ?? '') === '1';
    $tuKhoa = trim($_GET['tuKhoa'] ?? '');
    if ($hanhDong === 'danhMuc') {
        $lenh = $csdl->query('SELECT maKhoa AS maDanhMuc, ma, ten FROM khoa ORDER BY ten');
        traVeJson(['duLieu' => $lenh->fetchAll(PDO::FETCH_ASSOC)]);
    }
    if ($hanhDong === 'nhaXuatBan') {
        $dieuKien = $tuKhoa ? ' WHERE nxb.ma LIKE ? OR nxb.ten LIKE ? OR nxb.diaChi LIKE ?' : '';
        $thamSo = $tuKhoa ? array_fill(0, 3, '%' . $tuKhoa . '%') : [];
        $lenh = $csdl->prepare('SELECT COUNT(*) FROM nhaXuatBan nxb' . $dieuKien); $lenh->execute($thamSo); $tong = (int)$lenh->fetchColumn();
        $gioiHanSql = $layTatCa ? '' : ' LIMIT ' . (($trang - 1) * $gioiHan) . ', ' . $gioiHan;
        $lenh = $csdl->prepare('SELECT nxb.maNhaXuatBan, nxb.ma, nxb.ten, nxb.diaChi, nxb.soDienThoai, COUNT(tl.maTaiLieu) AS soTaiLieu FROM nhaXuatBan nxb LEFT JOIN taiLieu tl ON tl.maNhaXuatBan = nxb.maNhaXuatBan' . $dieuKien . ' GROUP BY nxb.maNhaXuatBan ORDER BY nxb.ten' . $gioiHanSql);
        $lenh->execute($thamSo);
        traVeJson(['duLieu' => $lenh->fetchAll(PDO::FETCH_ASSOC), 'tong' => $tong, 'trang' => $trang, 'gioiHan' => $gioiHan]);
    }
    $maKhoa = (int)($_GET['maKhoa'] ?? 0);
    $dieuKien = []; $thamSo = [];
    if ($tuKhoa) { $dieuKien[] = '(tl.ma LIKE ? OR tl.tieuDe LIKE ? OR nxb.ten LIKE ?)'; $thamSo = array_merge($thamSo, array_fill(0, 3, '%' . $tuKhoa . '%')); }
    if ($maKhoa) { $dieuKien[] = 'tl.maKhoa = ?'; $thamSo[] = $maKhoa; }
    $where = $dieuKien ? ' WHERE ' . implode(' AND ', $dieuKien) : '';
    $tu = ' FROM taiLieu tl LEFT JOIN khoa k ON k.maKhoa = tl.maKhoa LEFT JOIN nhaXuatBan nxb ON nxb.maNhaXuatBan = tl.maNhaXuatBan';
    $lenh = $csdl->prepare('SELECT COUNT(*)' . $tu . $where); $lenh->execute($thamSo); $tong = (int)$lenh->fetchColumn();
    $gioiHanSql = $layTatCa ? '' : ' LIMIT ' . (($trang - 1) * $gioiHan) . ', ' . $gioiHan;
    $lenh = $csdl->prepare('SELECT tl.maTaiLieu, tl.ma, tl.tieuDe, tl.maIsbn, tl.namXuatBan, tl.anhBia, tl.tomTat, tl.soLuongTong AS tongBan, tl.soLuongCon AS soBanSanSang, k.ten AS khoa, nxb.maNhaXuatBan, nxb.ten AS nhaXuatBan' . $tu . $where . ' ORDER BY tl.tieuDe' . $gioiHanSql);
    $lenh->execute($thamSo);
    traVeJson(['duLieu' => $lenh->fetchAll(PDO::FETCH_ASSOC), 'tong' => $tong, 'trang' => $trang, 'gioiHan' => $gioiHan]);
}

$duLieu = duLieuGuiLen();
$hanhDong = $duLieu['hanhDong'] ?? '';
if ($hanhDong === 'luuTomTat') {
    $maTaiLieu = (int)($duLieu['maTaiLieu'] ?? 0);
    $tomTat = trim($duLieu['tomTat'] ?? '');
    if (!$maTaiLieu) traVeJson(['loi' => 'Hãy chọn tài liệu cần cập nhật.'], 422);
    if (strlen($tomTat) > 12000) traVeJson(['loi' => 'Tóm tắt không được vượt quá 3.000 ký tự.'], 422);
    $lenh = $csdl->prepare('UPDATE taiLieu SET tomTat = ? WHERE maTaiLieu = ?');
    $lenh->execute([$tomTat ?: null, $maTaiLieu]);
    if (!$lenh->rowCount()) { $kiemTra = $csdl->prepare('SELECT 1 FROM taiLieu WHERE maTaiLieu = ?'); $kiemTra->execute([$maTaiLieu]); if (!$kiemTra->fetchColumn()) traVeJson(['loi' => 'Không tìm thấy tài liệu.'], 404); }
    traVeJson(['thanhCong' => true]);
}
if (in_array($hanhDong, ['luuNhaXuatBan', 'xoaNhaXuatBan'], true)) {
    if ($hanhDong === 'xoaNhaXuatBan') {
        try {
            $lenh = $csdl->prepare('DELETE FROM nhaXuatBan WHERE maNhaXuatBan = ?');
            $lenh->execute([(int)($duLieu['maNhaXuatBan'] ?? 0)]);
            if (!$lenh->rowCount()) traVeJson(['loi' => 'Không tìm thấy nhà xuất bản.'], 404);
            traVeJson(['thanhCong' => true]);
        } catch (PDOException $loi) { traVeJson(['loi' => 'Không thể xóa nhà xuất bản đang có tài liệu.'], 409); }
    }
    $maNhaXuatBan = (int)($duLieu['maNhaXuatBan'] ?? 0);
    $ma = strtoupper(trim($duLieu['ma'] ?? ''));
    $ten = trim($duLieu['ten'] ?? '');
    if (!preg_match('/^[A-Z0-9-]{2,30}$/', $ma) || strlen($ten) < 2) traVeJson(['loi' => 'Mã hoặc tên nhà xuất bản chưa hợp lệ.'], 422);
    try {
        if ($maNhaXuatBan) { $lenh = $csdl->prepare('UPDATE nhaXuatBan SET ma = ?, ten = ?, diaChi = ?, soDienThoai = ? WHERE maNhaXuatBan = ?'); $lenh->execute([$ma, $ten, trim($duLieu['diaChi'] ?? '') ?: null, trim($duLieu['soDienThoai'] ?? '') ?: null, $maNhaXuatBan]); }
        else { $lenh = $csdl->prepare('INSERT INTO nhaXuatBan (ma, ten, diaChi, soDienThoai) VALUES (?, ?, ?, ?)'); $lenh->execute([$ma, $ten, trim($duLieu['diaChi'] ?? '') ?: null, trim($duLieu['soDienThoai'] ?? '') ?: null]); $maNhaXuatBan = $csdl->lastInsertId(); }
        traVeJson(['thanhCong' => true, 'maNhaXuatBan' => $maNhaXuatBan]);
    } catch (PDOException $loi) { traVeJson(['loi' => 'Mã nhà xuất bản đã tồn tại.'], 409); }
}
if ($hanhDong === 'luu') {
    $maTaiLieu = (int)($duLieu['maTaiLieu'] ?? 0);
    $ma = strtoupper(trim($duLieu['ma'] ?? ''));
    $tieuDe = trim($duLieu['tieuDe'] ?? '');
    $maKhoa = (int)($duLieu['maDanhMuc'] ?? 0);
    $maNhaXuatBan = (int)($duLieu['maNhaXuatBan'] ?? 0);
    $namXuatBan = (int)($duLieu['namXuatBan'] ?? 0);
    $maIsbn = trim($duLieu['maIsbn'] ?? '');
    $anhBia = trim($duLieu['anhBiaHienTai'] ?? '');
    $soLuongTong = (int)($duLieu['soLuongTong'] ?? -1);
    if (!preg_match('/^[A-Z0-9-]{3,30}$/', $ma) || strlen($tieuDe) < 2 || !$maKhoa || !$maNhaXuatBan || $soLuongTong < 0) traVeJson(['loi' => 'Thông tin tài liệu, khoa hoặc nhà xuất bản chưa hợp lệ.'], 422);
    $tepAnhMoi = null;
    if (isset($_FILES['anhBia']) && $_FILES['anhBia']['error'] !== UPLOAD_ERR_NO_FILE) {
        if ($_FILES['anhBia']['error'] !== UPLOAD_ERR_OK || $_FILES['anhBia']['size'] > 5 * 1024 * 1024) traVeJson(['loi' => 'Không thể tải ảnh hoặc ảnh vượt quá 5 MB.'], 422);
        $thongTinAnh = @getimagesize($_FILES['anhBia']['tmp_name']); $dinhDang = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
        if (!$thongTinAnh || !isset($dinhDang[$thongTinAnh['mime']])) traVeJson(['loi' => 'Chỉ chấp nhận ảnh JPG, PNG hoặc WEBP.'], 422);
        $thuMucAnh = dirname(__DIR__) . '/assets/images/sach'; if (!is_dir($thuMucAnh) && !mkdir($thuMucAnh, 0775, true)) traVeJson(['loi' => 'Không thể tạo thư mục lưu ảnh.'], 500);
        $tepAnhMoi = $thuMucAnh . '/' . bin2hex(random_bytes(16)) . '.' . $dinhDang[$thongTinAnh['mime']];
        if (!move_uploaded_file($_FILES['anhBia']['tmp_name'], $tepAnhMoi)) traVeJson(['loi' => 'Không thể lưu ảnh đã tải lên.'], 500);
        $anhBia = 'assets/images/sach/' . basename($tepAnhMoi);
    }
    if ($anhBia && (!str_starts_with($anhBia, 'assets/images/sach/') || str_contains($anhBia, '..'))) traVeJson(['loi' => 'Đường dẫn ảnh không hợp lệ.'], 422);
    $csdl->beginTransaction();
    try {
        if ($maTaiLieu) {
            $lenh = $csdl->prepare('SELECT soLuongTong, soLuongCon FROM taiLieu WHERE maTaiLieu = ? FOR UPDATE'); $lenh->execute([$maTaiLieu]); $cu = $lenh->fetch(PDO::FETCH_ASSOC);
            if (!$cu) throw new Exception('Không tìm thấy tài liệu.');
            $soDangMuon = (int)$cu['soLuongTong'] - (int)$cu['soLuongCon'];
            if ($soLuongTong < $soDangMuon) throw new Exception('Số lượng tổng không được nhỏ hơn số sách đang mượn.');
            $lenh = $csdl->prepare('UPDATE taiLieu SET ma = ?, maKhoa = ?, maNhaXuatBan = ?, tieuDe = ?, maIsbn = ?, namXuatBan = ?, anhBia = ?, soLuongTong = ?, soLuongCon = ? WHERE maTaiLieu = ?');
            $lenh->execute([$ma, $maKhoa, $maNhaXuatBan, $tieuDe, $maIsbn ?: null, $namXuatBan ?: null, $anhBia ?: null, $soLuongTong, $soLuongTong - $soDangMuon, $maTaiLieu]);
        } else {
            $lenh = $csdl->prepare('INSERT INTO taiLieu (ma, maKhoa, maNhaXuatBan, tieuDe, maIsbn, loaiTaiLieu, namXuatBan, anhBia, duocMuon, soLuongTong, soLuongCon) VALUES (?, ?, ?, ?, ?, "giaoTrinh", ?, ?, "co", ?, ?)');
            $lenh->execute([$ma, $maKhoa, $maNhaXuatBan, $tieuDe, $maIsbn ?: null, $namXuatBan ?: null, $anhBia ?: null, $soLuongTong, $soLuongTong]);
        }
        $csdl->commit(); traVeJson(['thanhCong' => true]);
    } catch (Exception $loi) { if ($csdl->inTransaction()) $csdl->rollBack(); if ($tepAnhMoi && is_file($tepAnhMoi)) unlink($tepAnhMoi); traVeJson(['loi' => $loi->getMessage() ?: 'Không thể lưu tài liệu.'], 409); }
}
if ($hanhDong === 'xoa') {
    try { $lenh = $csdl->prepare('DELETE FROM taiLieu WHERE maTaiLieu = ?'); $lenh->execute([(int)($duLieu['maTaiLieu'] ?? 0)]); if (!$lenh->rowCount()) throw new Exception(); traVeJson(['thanhCong' => true]); }
    catch (Exception $loi) { traVeJson(['loi' => 'Không thể xóa tài liệu đã có lịch sử mượn.'], 409); }
}
traVeJson(['loi' => 'Thao tác không hợp lệ.'], 404);
