<?php
require __DIR__ . '/khoiTao.php';
$nguoiDung = nguoiDungHienTai('reader');
$csdl = ketNoiCSDL();
$hanhDong = $_GET['hanhDong'] ?? '';
$lenhDocGia = $csdl->prepare('SELECT maDocGia FROM hoSoDocGia WHERE maNguoiDung = ?');
$lenhDocGia->execute([$nguoiDung['id']]);
$maDocGia = $lenhDocGia->fetchColumn();
if (!$maDocGia) traVeJson(['loi' => 'Không tìm thấy hồ sơ độc giả.'], 404);
if ($hanhDong === 'taiLieu') {
    $lenh = $csdl->query('SELECT tl.maTaiLieu, tl.ma, tl.tieuDe, dm.ten AS danhMuc, (SELECT GROUP_CONCAT(tg.hoTen SEPARATOR ", ") FROM taiLieuTacGia tlTG JOIN tacGia tg ON tg.maTacGia = tlTG.maTacGia WHERE tlTG.maTaiLieu = tl.maTaiLieu) AS tacGia, (SELECT COUNT(*) FROM banSaoTaiLieu bs WHERE bs.maTaiLieu = tl.maTaiLieu AND bs.trangThai = "sanSang") AS soBan FROM taiLieu tl LEFT JOIN danhMucTaiLieu dm ON dm.maDanhMuc = tl.maDanhMuc ORDER BY tl.tieuDe');
    traVeJson(['duLieu' => $lenh->fetchAll(PDO::FETCH_ASSOC)]);
}
if ($hanhDong === 'phieuMuon') {
    $lichSu = ($_GET['loai'] ?? '') === 'lichSu';
    $dieuKien = $lichSu ? 'ct.ngayTra IS NOT NULL' : 'ct.ngayTra IS NULL';
    $lenh = $csdl->prepare("SELECT ct.maChiTietPhieuMuon, tl.ma, tl.tieuDe, ct.hanTra, ct.ngayTra, ct.trangThai FROM chiTietPhieuMuon ct JOIN phieuMuon pm ON pm.maPhieuMuon = ct.maPhieuMuon JOIN banSaoTaiLieu bs ON bs.maBanSao = ct.maBanSao JOIN taiLieu tl ON tl.maTaiLieu = bs.maTaiLieu WHERE pm.maDocGia = ? AND $dieuKien ORDER BY pm.ngayMuon DESC");
    $lenh->execute([$maDocGia]);
    traVeJson(['duLieu' => $lenh->fetchAll(PDO::FETCH_ASSOC)]);
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
        $chen = $csdl->prepare('INSERT INTO chiTietYeuCauMuon (maYeuCauMuon, maTaiLieu, trangThai) SELECT ?, maTaiLieu, "choDuyet" FROM taiLieu WHERE maTaiLieu = ? AND duocMuon = "co" AND EXISTS (SELECT 1 FROM banSaoTaiLieu WHERE maTaiLieu = taiLieu.maTaiLieu AND trangThai = "sanSang")');
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
    $lenh = $csdl->prepare('DELETE FROM yeuCauMuon WHERE maYeuCauMuon = ? AND maDocGia = ? AND trangThai = "choDuyet"');
    $lenh->execute([(int)($duLieu['maYeuCauMuon'] ?? 0), $maDocGia]);
    traVeJson(['thanhCong' => $lenh->rowCount() > 0]);
}
if ($hanhDong === 'giaHan' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $duLieu = duLieuGuiLen();
    $lenh = $csdl->prepare('INSERT INTO yeuCauGiaHan (maChiTietPhieuMuon, ngayYeuCau, hanTraMoi, trangThai) SELECT ct.maChiTietPhieuMuon, NOW(), DATE_ADD(ct.hanTra, INTERVAL 7 DAY), "choDuyet" FROM chiTietPhieuMuon ct JOIN phieuMuon pm ON pm.maPhieuMuon = ct.maPhieuMuon WHERE ct.maChiTietPhieuMuon = ? AND pm.maDocGia = ? AND ct.ngayTra IS NULL');
    $lenh->execute([(int)($duLieu['maChiTietPhieuMuon'] ?? 0), $maDocGia]);
    traVeJson(['thanhCong' => $lenh->rowCount() > 0]);
}
traVeJson(['loi' => 'API không hợp lệ.'], 404);
