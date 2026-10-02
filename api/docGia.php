<?php
require __DIR__ . '/khoiTao.php';
$nguoiDung = nguoiDungHienTai('reader');
$csdl = ketNoiCSDL();
$hanhDong = $_GET['hanhDong'] ?? '';
$lenhDocGia = $csdl->prepare('SELECT maDocGia FROM hoSoDocGia WHERE maNguoiDung = ?');
$lenhDocGia->execute([$nguoiDung['id']]);
$maDocGia = $lenhDocGia->fetchColumn();
if (!$maDocGia) traVeJson(['loi' => 'Không tìm thấy hồ sơ độc giả.'], 404);
if ($hanhDong === 'chiTietTaiLieu') {
    $ma = trim($_GET['ma'] ?? '');
    if (!$ma) traVeJson(['loi' => 'Thiếu mã tài liệu.'], 422);
    $lenh = $csdl->prepare('SELECT tl.maTaiLieu, tl.ma, tl.tieuDe, tl.namXuatBan, tl.anhBia, tl.tomTat, tl.soLuongCon AS soBan, tl.duocMuon, k.ten AS khoa, nxb.ten AS nhaXuatBan FROM taiLieu tl LEFT JOIN khoa k ON k.maKhoa = tl.maKhoa LEFT JOIN nhaXuatBan nxb ON nxb.maNhaXuatBan = tl.maNhaXuatBan WHERE tl.ma = ? LIMIT 1');
    $lenh->execute([$ma]);
    $taiLieu = $lenh->fetch(PDO::FETCH_ASSOC);
    if (!$taiLieu) traVeJson(['loi' => 'Không tìm thấy tài liệu.'], 404);
    traVeJson(['duLieu' => $taiLieu]);
}
if ($hanhDong === 'hoSo' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $lenh = $csdl->prepare('SELECT nd.hoTen, nd.thuDienTu, nd.soDienThoai, nd.ngaySinh, dg.maSo, dg.loaiDocGia, k.ten AS khoa FROM nguoiDung nd JOIN hoSoDocGia dg ON dg.maNguoiDung = nd.maNguoiDung LEFT JOIN khoa k ON k.maKhoa = dg.maKhoa WHERE nd.maNguoiDung = ?');
    $lenh->execute([$nguoiDung['id']]);
    traVeJson(['duLieu' => $lenh->fetch(PDO::FETCH_ASSOC)]);
}
if ($hanhDong === 'tongQuan') {
    $dangMuon = $csdl->prepare('SELECT COUNT(*) FROM chiTietPhieuMuon ct JOIN phieuMuon pm ON pm.maPhieuMuon = ct.maPhieuMuon WHERE pm.maDocGia = ? AND ct.ngayTra IS NULL');
    $dangMuon->execute([$maDocGia]);
    $sapDenHan = $csdl->prepare('SELECT COUNT(*) FROM chiTietPhieuMuon ct JOIN phieuMuon pm ON pm.maPhieuMuon = ct.maPhieuMuon WHERE pm.maDocGia = ? AND ct.ngayTra IS NULL AND ct.hanTra <= DATE_ADD(CURDATE(), INTERVAL 3 DAY)');
    $sapDenHan->execute([$maDocGia]);
    $choDuyet = $csdl->prepare('SELECT COUNT(*) FROM yeuCauMuon WHERE maDocGia = ? AND trangThai = "choDuyet"');
    $choDuyet->execute([$maDocGia]);
    $daTra = $csdl->prepare('SELECT COUNT(*) FROM chiTietPhieuMuon ct JOIN phieuMuon pm ON pm.maPhieuMuon = ct.maPhieuMuon WHERE pm.maDocGia = ? AND ct.ngayTra IS NOT NULL');
    $daTra->execute([$maDocGia]);
    $lenh = $csdl->prepare('SELECT ct.maChiTietPhieuMuon, tl.ma, tl.tieuDe, ct.hanTra FROM chiTietPhieuMuon ct JOIN phieuMuon pm ON pm.maPhieuMuon = ct.maPhieuMuon JOIN taiLieu tl ON tl.maTaiLieu = ct.maTaiLieu WHERE pm.maDocGia = ? AND ct.ngayTra IS NULL ORDER BY ct.hanTra LIMIT 2');
    $lenh->execute([$maDocGia]);
    $dangMuonGanDay = $lenh->fetchAll(PDO::FETCH_ASSOC);
    $lenh = $csdl->prepare('SELECT tl.ma, tl.tieuDe, k.ten AS khoa, nxb.ten AS nhaXuatBan FROM taiLieu tl LEFT JOIN khoa k ON k.maKhoa = tl.maKhoa LEFT JOIN nhaXuatBan nxb ON nxb.maNhaXuatBan = tl.maNhaXuatBan WHERE tl.soLuongCon > 0 AND tl.duocMuon = "co" ORDER BY CASE WHEN tl.maKhoa = (SELECT maKhoa FROM hoSoDocGia WHERE maDocGia = ?) THEN 0 ELSE 1 END, tl.tieuDe LIMIT 1');
    $lenh->execute([$maDocGia]);
    traVeJson(['duLieu' => ['dangMuon' => $dangMuon->fetchColumn(), 'sapDenHan' => $sapDenHan->fetchColumn(), 'choDuyet' => $choDuyet->fetchColumn(), 'daTra' => $daTra->fetchColumn()], 'dangMuonGanDay' => $dangMuonGanDay, 'goiY' => $lenh->fetch(PDO::FETCH_ASSOC) ?: null]);
}
if ($hanhDong === 'taiLieu') {
    $lenh = $csdl->query('SELECT tl.maTaiLieu, tl.ma, tl.tieuDe, k.ten AS danhMuc, nxb.ten AS nhaXuatBan, tl.soLuongCon AS soBan FROM taiLieu tl LEFT JOIN khoa k ON k.maKhoa = tl.maKhoa LEFT JOIN nhaXuatBan nxb ON nxb.maNhaXuatBan = tl.maNhaXuatBan ORDER BY tl.tieuDe');
    traVeJson(['duLieu' => $lenh->fetchAll(PDO::FETCH_ASSOC)]);
}
if ($hanhDong === 'phieuMuon') {
    $lichSu = ($_GET['loai'] ?? '') === 'lichSu';
    $dieuKien = $lichSu ? 'ct.ngayTra IS NOT NULL' : 'ct.ngayTra IS NULL';
    $lenh = $csdl->prepare("SELECT ct.maChiTietPhieuMuon, tl.ma, tl.tieuDe, pm.ngayMuon, ct.hanTra, ct.ngayTra, ct.trangThai, (SELECT yg.trangThai FROM yeuCauGiaHan yg WHERE yg.maChiTietPhieuMuon = ct.maChiTietPhieuMuon ORDER BY yg.ngayYeuCau DESC LIMIT 1) AS trangThaiGiaHan FROM chiTietPhieuMuon ct JOIN phieuMuon pm ON pm.maPhieuMuon = ct.maPhieuMuon JOIN taiLieu tl ON tl.maTaiLieu = ct.maTaiLieu WHERE pm.maDocGia = ? AND $dieuKien ORDER BY pm.ngayMuon DESC");
    $lenh->execute([$maDocGia]);
    traVeJson(['duLieu' => $lenh->fetchAll(PDO::FETCH_ASSOC)]);
}
if ($hanhDong === 'hoSo' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $duLieu = duLieuGuiLen();
    $hoTen = trim($duLieu['hoTen'] ?? '');
    $thuDienTu = trim($duLieu['thuDienTu'] ?? '');
    $soDienThoai = trim($duLieu['soDienThoai'] ?? '');
    $ngaySinh = ($duLieu['ngaySinh'] ?? '') ?: null;
    if (strlen($hoTen) < 3 || !filter_var($thuDienTu, FILTER_VALIDATE_EMAIL)) traVeJson(['loi' => 'Họ tên hoặc email chưa hợp lệ.'], 422);
    $lenh = $csdl->prepare('UPDATE nguoiDung SET hoTen = ?, thuDienTu = ?, soDienThoai = ?, ngaySinh = ? WHERE maNguoiDung = ?');
    $lenh->execute([$hoTen, $thuDienTu, $soDienThoai ?: null, $ngaySinh, $nguoiDung['id']]);
    $_SESSION['library_user']['name'] = $hoTen;
    $_SESSION['library_user']['email'] = $thuDienTu;
    $_SESSION['library_user']['phone'] = $soDienThoai;
    $_SESSION['library_user']['birth_date'] = $ngaySinh;
    traVeJson(['thanhCong' => true]);
}
if ($hanhDong === 'yeuCauMuon') {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $lenh = $csdl->prepare('SELECT yc.maYeuCauMuon, yc.ngayYeuCau, yc.trangThai, GROUP_CONCAT(CONCAT(tl.ma, "|", tl.tieuDe) SEPARATOR "||") AS taiLieu FROM yeuCauMuon yc JOIN chiTietYeuCauMuon ct ON ct.maYeuCauMuon = yc.maYeuCauMuon JOIN taiLieu tl ON tl.maTaiLieu = ct.maTaiLieu WHERE yc.maDocGia = ? AND yc.trangThai = "choDuyet" GROUP BY yc.maYeuCauMuon ORDER BY yc.ngayYeuCau DESC');
        $lenh->execute([$maDocGia]);
        traVeJson(['duLieu' => $lenh->fetchAll(PDO::FETCH_ASSOC)]);
    }
    $duLieu = duLieuGuiLen();
    $maTaiLieu = array_values(array_unique(array_map('intval', $duLieu['maTaiLieu'] ?? [])));
    if (!$maTaiLieu) traVeJson(['loi' => 'Chưa chọn tài liệu.'], 422);
    $csdl->beginTransaction();
    try {
        $lenh = $csdl->prepare('INSERT INTO yeuCauMuon (maDocGia, ngayYeuCau, trangThai) VALUES (?, NOW(), "choDuyet")');
        $lenh->execute([$maDocGia]);
        $maYeuCau = $csdl->lastInsertId();
        $chen = $csdl->prepare('INSERT INTO chiTietYeuCauMuon (maYeuCauMuon, maTaiLieu, trangThai) SELECT ?, maTaiLieu, "choDuyet" FROM taiLieu WHERE maTaiLieu = ? AND duocMuon = "co" AND soLuongCon > 0');
        foreach ($maTaiLieu as $ma) {
            $chen->execute([$maYeuCau, $ma]);
            if (!$chen->rowCount()) throw new Exception();
        }
        $csdl->commit();
        traVeJson(['thanhCong' => true]);
    } catch (Exception $loi) {
        $csdl->rollBack();
        traVeJson(['loi' => 'Có tài liệu không còn đủ điều kiện để mượn.'], 422);
    }
}
if ($hanhDong === 'huyYeuCau' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $duLieu = duLieuGuiLen();
    $maYeuCau = (int)($duLieu['maYeuCauMuon'] ?? 0);
    $csdl->beginTransaction();
    try {
        $lenh = $csdl->prepare('SELECT maYeuCauMuon FROM yeuCauMuon WHERE maYeuCauMuon = ? AND maDocGia = ? AND trangThai = "choDuyet"');
        $lenh->execute([$maYeuCau, $maDocGia]);
        if (!$lenh->fetchColumn()) throw new Exception();
        $lenh = $csdl->prepare('DELETE FROM chiTietYeuCauMuon WHERE maYeuCauMuon = ?'); $lenh->execute([$maYeuCau]);
        $lenh = $csdl->prepare('DELETE FROM yeuCauMuon WHERE maYeuCauMuon = ?'); $lenh->execute([$maYeuCau]);
        $csdl->commit();
        traVeJson(['thanhCong' => true]);
    } catch (Exception $loi) {
        if ($csdl->inTransaction()) $csdl->rollBack();
        traVeJson(['loi' => 'Không thể hủy yêu cầu này.'], 422);
    }
}
if ($hanhDong === 'giaHan' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $duLieu = duLieuGuiLen();
    $lenh = $csdl->prepare('INSERT INTO yeuCauGiaHan (maChiTietPhieuMuon, ngayYeuCau, hanTraMoi, trangThai) SELECT ct.maChiTietPhieuMuon, NOW(), DATE_ADD(ct.hanTra, INTERVAL 7 DAY), "choDuyet" FROM chiTietPhieuMuon ct JOIN phieuMuon pm ON pm.maPhieuMuon = ct.maPhieuMuon WHERE ct.maChiTietPhieuMuon = ? AND pm.maDocGia = ? AND ct.ngayTra IS NULL AND NOT EXISTS (SELECT 1 FROM yeuCauGiaHan yg WHERE yg.maChiTietPhieuMuon = ct.maChiTietPhieuMuon AND yg.trangThai = "choDuyet")');
    $lenh->execute([(int)($duLieu['maChiTietPhieuMuon'] ?? 0), $maDocGia]);
    if (!$lenh->rowCount()) traVeJson(['loi' => 'Không thể gửi yêu cầu; có thể bạn đã gửi yêu cầu gia hạn trước đó.'], 422);
    traVeJson(['thanhCong' => true]);
}
traVeJson(['loi' => 'API không hợp lệ.'], 404);
