<?php
require __DIR__ . '/khoiTao.php';
$thuThu = nguoiDungHienTai('librarian');
$csdl = ketNoiCSDL();
$hanhDong = $_GET['hanhDong'] ?? '';
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($hanhDong === 'yeuCau') {
        $lenh = $csdl->query("SELECT yc.maYeuCauMuon, yc.ngayYeuCau, dg.maSo, nd.hoTen, nd.trangThai AS trangThaiTaiKhoan, GROUP_CONCAT(CONCAT(tl.ma, ' · ', tl.tieuDe) SEPARATOR '||') AS taiLieu FROM yeuCauMuon yc JOIN hoSoDocGia dg ON dg.maDocGia = yc.maDocGia JOIN nguoiDung nd ON nd.maNguoiDung = dg.maNguoiDung JOIN chiTietYeuCauMuon ct ON ct.maYeuCauMuon = yc.maYeuCauMuon JOIN taiLieu tl ON tl.maTaiLieu = ct.maTaiLieu WHERE yc.trangThai = 'choDuyet' GROUP BY yc.maYeuCauMuon ORDER BY yc.ngayYeuCau");
        traVeJson(['duLieu' => $lenh->fetchAll(PDO::FETCH_ASSOC)]);
    }
    if ($hanhDong === 'docGia') {
        $lenh = $csdl->query("SELECT dg.maDocGia, dg.maSo, dg.loaiDocGia, dg.trangThaiThe, nd.hoTen, nd.thuDienTu, nd.trangThai, k.ten AS khoa, (SELECT COUNT(*) FROM phieuMuon pm JOIN chiTietPhieuMuon ct ON ct.maPhieuMuon = pm.maPhieuMuon WHERE pm.maDocGia = dg.maDocGia AND ct.ngayTra IS NULL) AS dangMuon, (SELECT COALESCE(SUM(soTien),0) FROM phieuPhat pp WHERE pp.maDocGia = dg.maDocGia AND pp.trangThai = 'chuaThanhToan') AS tienPhat FROM hoSoDocGia dg JOIN nguoiDung nd ON nd.maNguoiDung = dg.maNguoiDung LEFT JOIN khoa k ON k.maKhoa = dg.maKhoa ORDER BY nd.hoTen");
        traVeJson(['duLieu' => $lenh->fetchAll(PDO::FETCH_ASSOC)]);
    }
    if ($hanhDong === 'phat') {
        $lenh = $csdl->query("SELECT pp.maPhieuPhat, pp.loaiPhat, pp.soTien, pp.trangThai, pp.ngayLap, dg.maSo, nd.hoTen FROM phieuPhat pp JOIN hoSoDocGia dg ON dg.maDocGia = pp.maDocGia JOIN nguoiDung nd ON nd.maNguoiDung = dg.maNguoiDung ORDER BY pp.ngayLap DESC");
        traVeJson(['duLieu' => $lenh->fetchAll(PDO::FETCH_ASSOC)]);
    }
    if ($hanhDong === 'tongQuan') {
        $choDuyet = $csdl->query("SELECT COUNT(*) FROM yeuCauMuon WHERE trangThai = 'choDuyet'")->fetchColumn();
        $quaHan = $csdl->query("SELECT COUNT(*) FROM chiTietPhieuMuon WHERE ngayTra IS NULL AND hanTra < CURDATE()")->fetchColumn();
        $chuaThu = $csdl->query("SELECT COALESCE(SUM(soTien),0) FROM phieuPhat WHERE trangThai = 'chuaThanhToan'")->fetchColumn();
        traVeJson(['duLieu' => compact('choDuyet','quaHan','chuaThu')]);
    }
    traVeJson(['loi' => 'Dữ liệu không hợp lệ.'], 404);
}
$duLieu = duLieuGuiLen();
$hanhDong = $duLieu['hanhDong'] ?? '';
if ($hanhDong === 'xuLyYeuCau') {
    $trangThai = $duLieu['trangThai'] ?? ''; $maYeuCau = (int)($duLieu['maYeuCauMuon'] ?? 0);
    if (!in_array($trangThai, ['daDuyet','tuChoi'], true) || !$maYeuCau) traVeJson(['loi' => 'Yêu cầu không hợp lệ.'], 422);
    $lenh = $csdl->prepare('UPDATE yeuCauMuon SET trangThai = ?, maNguoiXuLy = ?, ngayXuLy = NOW(), lyDoTuChoi = ? WHERE maYeuCauMuon = ? AND trangThai = "choDuyet"');
    $lenh->execute([$trangThai, $thuThu['id'], $trangThai === 'tuChoi' ? trim($duLieu['lyDoTuChoi'] ?? '') : null, $maYeuCau]);
    traVeJson(['thanhCong' => $lenh->rowCount() > 0]);
}
if ($hanhDong === 'capNhatThe') {
    $maDocGia = (int)($duLieu['maDocGia'] ?? 0); $trangThai = $duLieu['trangThaiThe'] ?? '';
    if (!in_array($trangThai, ['hoatDong','tamKhoa'], true)) traVeJson(['loi' => 'Trạng thái không hợp lệ.'], 422);
    $lenh = $csdl->prepare('UPDATE hoSoDocGia SET trangThaiThe = ? WHERE maDocGia = ?'); $lenh->execute([$trangThai,$maDocGia]);
    traVeJson(['thanhCong' => $lenh->rowCount() > 0]);
}
if ($hanhDong === 'thuPhat') {
    $lenh = $csdl->prepare('UPDATE phieuPhat SET trangThai = "daThanhToan", maNguoiThu = ?, ngayThanhToan = NOW() WHERE maPhieuPhat = ? AND trangThai = "chuaThanhToan"');
    $lenh->execute([$thuThu['id'], (int)($duLieu['maPhieuPhat'] ?? 0)]);
    traVeJson(['thanhCong' => $lenh->rowCount() > 0]);
}
traVeJson(['loi' => 'Thao tác không hợp lệ.'], 404);
