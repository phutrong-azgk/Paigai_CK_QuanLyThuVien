<?php
require __DIR__ . '/khoiTao.php';
$thuThu = nguoiDungHienTai('librarian');
$csdl = ketNoiCSDL();
$hanhDong = $_GET['hanhDong'] ?? '';
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($hanhDong === 'yeuCau') {
        $lenh = $csdl->query("SELECT yc.maYeuCauMuon, yc.ngayYeuCau, dg.maSo, dg.trangThaiThe, nd.hoTen, nd.trangThai AS trangThaiTaiKhoan, GROUP_CONCAT(CONCAT(tl.ma, ' · ', tl.tieuDe) SEPARATOR '||') AS taiLieu, COUNT(ct.maChiTietYeuCauMuon) AS soTaiLieu, SUM(tl.soLuongCon > 0) AS soTaiLieuSanSang FROM yeuCauMuon yc JOIN hoSoDocGia dg ON dg.maDocGia = yc.maDocGia JOIN nguoiDung nd ON nd.maNguoiDung = dg.maNguoiDung JOIN chiTietYeuCauMuon ct ON ct.maYeuCauMuon = yc.maYeuCauMuon JOIN taiLieu tl ON tl.maTaiLieu = ct.maTaiLieu WHERE yc.trangThai = 'choDuyet' GROUP BY yc.maYeuCauMuon ORDER BY yc.ngayYeuCau");
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
        $muonHomNay = $csdl->query("SELECT COUNT(*) FROM phieuMuon WHERE DATE(ngayMuon) = CURDATE()")->fetchColumn();
        $traHomNay = $csdl->query("SELECT COUNT(*) FROM chiTietPhieuMuon WHERE DATE(ngayTra) = CURDATE()")->fetchColumn();
        $quaHan = $csdl->query("SELECT COUNT(*) FROM chiTietPhieuMuon WHERE ngayTra IS NULL AND hanTra < CURDATE()")->fetchColumn();
        $chuaThu = $csdl->query("SELECT COALESCE(SUM(soTien),0) FROM phieuPhat WHERE trangThai = 'chuaThanhToan'")->fetchColumn();
        $docGiaPhat = $csdl->query("SELECT COUNT(DISTINCT maDocGia) FROM phieuPhat WHERE trangThai = 'chuaThanhToan'")->fetchColumn();
        $lenh = $csdl->query("SELECT tl.tieuDe, nd.hoTen, dg.maSo, ct.hanTra FROM chiTietPhieuMuon ct JOIN phieuMuon pm ON pm.maPhieuMuon = ct.maPhieuMuon JOIN taiLieu tl ON tl.maTaiLieu = ct.maTaiLieu JOIN hoSoDocGia dg ON dg.maDocGia = pm.maDocGia JOIN nguoiDung nd ON nd.maNguoiDung = dg.maNguoiDung WHERE ct.ngayTra IS NULL AND ct.hanTra <= DATE_ADD(CURDATE(), INTERVAL 3 DAY) ORDER BY ct.hanTra LIMIT 5");
        traVeJson(['duLieu' => compact('choDuyet','muonHomNay','traHomNay','quaHan','chuaThu','docGiaPhat'), 'denHan' => $lenh->fetchAll(PDO::FETCH_ASSOC)]);
    }
    if ($hanhDong === 'traCuuDocGia') {
        $tuKhoa = trim($_GET['tuKhoa'] ?? '');
        if (!$tuKhoa) traVeJson(['loi' => 'Vui lòng nhập mã độc giả.'], 422);
        $lenh = $csdl->prepare('SELECT dg.maDocGia, dg.maSo, dg.loaiDocGia, dg.trangThaiThe, nd.hoTen, nd.trangThai, k.ten AS khoa, (SELECT COUNT(*) FROM phieuMuon pm JOIN chiTietPhieuMuon ct ON ct.maPhieuMuon = pm.maPhieuMuon WHERE pm.maDocGia = dg.maDocGia AND ct.ngayTra IS NULL) AS dangMuon, (SELECT cs.soSachToiDa FROM chinhSachMuon cs WHERE cs.loaiDocGia = dg.loaiDocGia AND cs.trangThai = "dangApDung" ORDER BY cs.ngayApDung DESC LIMIT 1) AS soSachToiDa, (SELECT cs.soNgayMuon FROM chinhSachMuon cs WHERE cs.loaiDocGia = dg.loaiDocGia AND cs.trangThai = "dangApDung" ORDER BY cs.ngayApDung DESC LIMIT 1) AS soNgayMuon FROM hoSoDocGia dg JOIN nguoiDung nd ON nd.maNguoiDung = dg.maNguoiDung LEFT JOIN khoa k ON k.maKhoa = dg.maKhoa WHERE dg.maSo = ? OR nd.tenDangNhap = ? LIMIT 1');
        $lenh->execute([$tuKhoa, strtoupper($tuKhoa)]);
        $docGia = $lenh->fetch(PDO::FETCH_ASSOC);
        if (!$docGia) traVeJson(['loi' => 'Không tìm thấy độc giả.'], 404);
        traVeJson(['duLieu' => $docGia]);
    }
    if ($hanhDong === 'taiLieuChon') {
        $loai = $_GET['loai'] ?? 'muon'; $tuKhoa = trim($_GET['tuKhoa'] ?? '');
        if ($loai === 'tra') {
            $lenh = $csdl->prepare('SELECT ct.maChiTietPhieuMuon, tl.ma, tl.tieuDe, nd.hoTen, dg.maSo, pm.ngayMuon, ct.hanTra FROM chiTietPhieuMuon ct JOIN phieuMuon pm ON pm.maPhieuMuon = ct.maPhieuMuon JOIN taiLieu tl ON tl.maTaiLieu = ct.maTaiLieu JOIN hoSoDocGia dg ON dg.maDocGia = pm.maDocGia JOIN nguoiDung nd ON nd.maNguoiDung = dg.maNguoiDung WHERE ct.ngayTra IS NULL AND CONCAT(tl.ma, " ", tl.tieuDe, " ", nd.hoTen, " ", dg.maSo) LIKE ? ORDER BY ct.hanTra');
            $lenh->execute(['%' . $tuKhoa . '%']);
        } else {
            $lenh = $csdl->prepare('SELECT maTaiLieu, ma, tieuDe, soLuongCon FROM taiLieu WHERE duocMuon = "co" AND soLuongCon > 0 AND CONCAT(ma, " ", tieuDe) LIKE ? ORDER BY tieuDe');
            $lenh->execute(['%' . $tuKhoa . '%']);
        }
        traVeJson(['duLieu' => $lenh->fetchAll(PDO::FETCH_ASSOC)]);
    }
    if ($hanhDong === 'yeuCauGiaHan') {
        $lenh = $csdl->query('SELECT yg.maYeuCauGiaHan, ct.maChiTietPhieuMuon, tl.tieuDe, tl.ma, dg.maSo, nd.hoTen, ct.hanTra, yg.hanTraMoi FROM yeuCauGiaHan yg JOIN chiTietPhieuMuon ct ON ct.maChiTietPhieuMuon = yg.maChiTietPhieuMuon JOIN phieuMuon pm ON pm.maPhieuMuon = ct.maPhieuMuon JOIN taiLieu tl ON tl.maTaiLieu = ct.maTaiLieu JOIN hoSoDocGia dg ON dg.maDocGia = pm.maDocGia JOIN nguoiDung nd ON nd.maNguoiDung = dg.maNguoiDung WHERE yg.trangThai = "choDuyet" ORDER BY yg.ngayYeuCau');
        traVeJson(['duLieu' => $lenh->fetchAll(PDO::FETCH_ASSOC)]);
    }
    traVeJson(['loi' => 'Dữ liệu không hợp lệ.'], 404);
}
$duLieu = duLieuGuiLen();
$hanhDong = $duLieu['hanhDong'] ?? '';
if ($hanhDong === 'lapPhieuMuon') {
    $maDocGia = (int)($duLieu['maDocGia'] ?? 0); $taiLieu = array_values(array_unique(array_map('intval', $duLieu['maTaiLieu'] ?? [])));
    if (!$maDocGia || !$taiLieu) traVeJson(['loi' => 'Chưa chọn độc giả hoặc tài liệu.'], 422);
    $csdl->beginTransaction();
    try {
        $lenh = $csdl->prepare('SELECT dg.loaiDocGia, dg.trangThaiThe, nd.trangThai FROM hoSoDocGia dg JOIN nguoiDung nd ON nd.maNguoiDung = dg.maNguoiDung WHERE dg.maDocGia = ? FOR UPDATE'); $lenh->execute([$maDocGia]); $docGia = $lenh->fetch(PDO::FETCH_ASSOC);
        if (!$docGia || $docGia['trangThaiThe'] !== 'hoatDong' || $docGia['trangThai'] !== 'hoatDong') throw new Exception('Thẻ hoặc tài khoản độc giả không hoạt động.');
        $lenh = $csdl->prepare('SELECT maChinhSach, soSachToiDa, soNgayMuon FROM chinhSachMuon WHERE loaiDocGia = ? AND trangThai = "dangApDung" ORDER BY ngayApDung DESC LIMIT 1'); $lenh->execute([$docGia['loaiDocGia']]); $chinhSach = $lenh->fetch(PDO::FETCH_ASSOC);
        if (!$chinhSach) throw new Exception('Chưa có chính sách mượn phù hợp.');
        $lenh = $csdl->prepare('SELECT COUNT(*) FROM phieuMuon pm JOIN chiTietPhieuMuon ct ON ct.maPhieuMuon = pm.maPhieuMuon WHERE pm.maDocGia = ? AND ct.ngayTra IS NULL'); $lenh->execute([$maDocGia]);
        if ((int)$lenh->fetchColumn() + count($taiLieu) > (int)$chinhSach['soSachToiDa']) throw new Exception('Độc giả đã vượt số lượng tài liệu được phép mượn.');
        $lenh = $csdl->prepare('INSERT INTO phieuMuon (maDocGia, maThuThu, maChinhSach, ngayMuon, trangThai) VALUES (?, ?, ?, NOW(), "dangMuon")'); $lenh->execute([$maDocGia, $thuThu['id'], $chinhSach['maChinhSach']]); $maPhieuMuon = $csdl->lastInsertId();
        $capNhat = $csdl->prepare('UPDATE taiLieu SET soLuongCon = soLuongCon - 1 WHERE maTaiLieu = ? AND soLuongCon > 0'); $them = $csdl->prepare('INSERT INTO chiTietPhieuMuon (maPhieuMuon, maTaiLieu, hanTra, trangThai) VALUES (?, ?, DATE_ADD(CURDATE(), INTERVAL ? DAY), "dangMuon")');
        foreach ($taiLieu as $maTaiLieu) { $capNhat->execute([$maTaiLieu]); if (!$capNhat->rowCount()) throw new Exception('Có tài liệu không còn sẵn sàng.'); $them->execute([$maPhieuMuon, $maTaiLieu, $chinhSach['soNgayMuon']]); }
        $csdl->commit(); traVeJson(['thanhCong' => true, 'maPhieuMuon' => $maPhieuMuon]);
    } catch (Exception $loi) { if ($csdl->inTransaction()) $csdl->rollBack(); traVeJson(['loi' => $loi->getMessage() ?: 'Không thể lập phiếu mượn.'], 422); }
}
if ($hanhDong === 'xacNhanTra') {
    $chiTiet = array_values(array_unique(array_map('intval', $duLieu['maChiTietPhieuMuon'] ?? [])));
    if (!$chiTiet) traVeJson(['loi' => 'Chưa chọn tài liệu cần trả.'], 422);
    $csdl->beginTransaction();
    try {
        $phi = 0; $phieuMuon = []; $layChiTiet = $csdl->prepare('SELECT ct.maPhieuMuon, ct.maTaiLieu, ct.hanTra, pm.maDocGia, dg.loaiDocGia FROM chiTietPhieuMuon ct JOIN phieuMuon pm ON pm.maPhieuMuon = ct.maPhieuMuon JOIN hoSoDocGia dg ON dg.maDocGia = pm.maDocGia WHERE ct.maChiTietPhieuMuon = ? AND ct.ngayTra IS NULL FOR UPDATE');
        $capNhat = $csdl->prepare('UPDATE chiTietPhieuMuon SET ngayTra = NOW(), trangThai = "daTra" WHERE maChiTietPhieuMuon = ?'); $tangKho = $csdl->prepare('UPDATE taiLieu SET soLuongCon = soLuongCon + 1 WHERE maTaiLieu = ?'); $layPhat = $csdl->prepare('SELECT tienPhatMoiNgay FROM chinhSachMuon WHERE loaiDocGia = ? AND trangThai = "dangApDung" ORDER BY ngayApDung DESC LIMIT 1'); $themPhat = $csdl->prepare('INSERT INTO phieuPhat (maDocGia, maChiTietPhieuMuon, loaiPhat, soTien, trangThai, ngayLap) VALUES (?, ?, "quaHan", ?, "chuaThanhToan", NOW())');
        foreach ($chiTiet as $maChiTiet) { $layChiTiet->execute([$maChiTiet]); $muc = $layChiTiet->fetch(PDO::FETCH_ASSOC); if (!$muc) throw new Exception('Có tài liệu không còn ở trạng thái đang mượn.'); $phieuMuon[] = $muc['maPhieuMuon']; $capNhat->execute([$maChiTiet]); $tangKho->execute([$muc['maTaiLieu']]); $ngayQuaHan = max(0, (int)$csdl->query('SELECT GREATEST(0, DATEDIFF(CURDATE(), ' . $csdl->quote($muc['hanTra']) . '))')->fetchColumn()); if ($ngayQuaHan) { $layPhat->execute([$muc['loaiDocGia']]); $soTien = $ngayQuaHan * (float)($layPhat->fetchColumn() ?: 0); if ($soTien) { $themPhat->execute([$muc['maDocGia'], $maChiTiet, $soTien]); $phi += $soTien; } } }
        $capNhatPhieu = $csdl->prepare('UPDATE phieuMuon SET trangThai = "daTra" WHERE maPhieuMuon = ? AND NOT EXISTS (SELECT 1 FROM chiTietPhieuMuon WHERE maPhieuMuon = ? AND ngayTra IS NULL)'); foreach (array_unique($phieuMuon) as $maPhieuMuon) $capNhatPhieu->execute([$maPhieuMuon, $maPhieuMuon]);
        $csdl->commit(); traVeJson(['thanhCong' => true, 'phi' => $phi]);
    } catch (Exception $loi) { if ($csdl->inTransaction()) $csdl->rollBack(); traVeJson(['loi' => $loi->getMessage() ?: 'Không thể xác nhận trả sách.'], 422); }
}
if ($hanhDong === 'xuLyGiaHan') {
    $maYeuCau = (int)($duLieu['maYeuCauGiaHan'] ?? 0); $trangThai = $duLieu['trangThai'] ?? '';
    if (!$maYeuCau || !in_array($trangThai, ['daDuyet', 'tuChoi'], true)) traVeJson(['loi' => 'Yêu cầu gia hạn không hợp lệ.'], 422);
    $csdl->beginTransaction();
    try {
        $lenh = $csdl->prepare('SELECT maChiTietPhieuMuon, hanTraMoi FROM yeuCauGiaHan WHERE maYeuCauGiaHan = ? AND trangThai = "choDuyet" FOR UPDATE'); $lenh->execute([$maYeuCau]); $yeuCau = $lenh->fetch(PDO::FETCH_ASSOC);
        if (!$yeuCau) throw new Exception('Yêu cầu đã được xử lý.');
        if ($trangThai === 'daDuyet') { $lenh = $csdl->prepare('UPDATE chiTietPhieuMuon SET hanTra = ? WHERE maChiTietPhieuMuon = ? AND ngayTra IS NULL'); $lenh->execute([$yeuCau['hanTraMoi'], $yeuCau['maChiTietPhieuMuon']]); if (!$lenh->rowCount()) throw new Exception('Tài liệu đã được trả.'); }
        $lenh = $csdl->prepare('UPDATE yeuCauGiaHan SET trangThai = ? WHERE maYeuCauGiaHan = ?'); $lenh->execute([$trangThai, $maYeuCau]);
        $csdl->commit(); traVeJson(['thanhCong' => true]);
    } catch (Exception $loi) { if ($csdl->inTransaction()) $csdl->rollBack(); traVeJson(['loi' => $loi->getMessage() ?: 'Không thể xử lý yêu cầu gia hạn.'], 422); }
}
if ($hanhDong === 'xuLyYeuCau') {
    $trangThai = $duLieu['trangThai'] ?? ''; $maYeuCau = (int)($duLieu['maYeuCauMuon'] ?? 0);
    if (!in_array($trangThai, ['daDuyet','tuChoi'], true) || !$maYeuCau) traVeJson(['loi' => 'Yêu cầu không hợp lệ.'], 422);
    $csdl->beginTransaction();
    try {
        $lenh = $csdl->prepare('SELECT yc.maDocGia, dg.loaiDocGia, dg.trangThaiThe, nd.trangThai FROM yeuCauMuon yc JOIN hoSoDocGia dg ON dg.maDocGia = yc.maDocGia JOIN nguoiDung nd ON nd.maNguoiDung = dg.maNguoiDung WHERE yc.maYeuCauMuon = ? AND yc.trangThai = "choDuyet" FOR UPDATE');
        $lenh->execute([$maYeuCau]); $yeuCau = $lenh->fetch(PDO::FETCH_ASSOC);
        if (!$yeuCau) throw new Exception('Yêu cầu đã được xử lý.');
        if ($trangThai === 'tuChoi') {
            $lenh = $csdl->prepare('UPDATE yeuCauMuon SET trangThai = "tuChoi", maNguoiXuLy = ?, ngayXuLy = NOW(), lyDoTuChoi = ? WHERE maYeuCauMuon = ?');
            $lenh->execute([$thuThu['id'], trim($duLieu['lyDoTuChoi'] ?? ''), $maYeuCau]);
            $csdl->commit(); traVeJson(['thanhCong' => true]);
        }
        if ($yeuCau['trangThai'] !== 'hoatDong' || $yeuCau['trangThaiThe'] !== 'hoatDong') throw new Exception('Thẻ hoặc tài khoản độc giả đang không hoạt động.');
        $lenh = $csdl->prepare('SELECT maChinhSach, soNgayMuon FROM chinhSachMuon WHERE loaiDocGia = ? AND trangThai = "dangApDung" ORDER BY ngayApDung DESC LIMIT 1');
        $lenh->execute([$yeuCau['loaiDocGia']]); $chinhSach = $lenh->fetch(PDO::FETCH_ASSOC);
        if (!$chinhSach) throw new Exception('Chưa có chính sách mượn phù hợp.');
        $lenh = $csdl->prepare('INSERT INTO phieuMuon (maDocGia, maThuThu, maChinhSach, maYeuCauMuon, ngayMuon, trangThai) VALUES (?, ?, ?, ?, NOW(), "dangMuon")');
        $lenh->execute([$yeuCau['maDocGia'], $thuThu['id'], $chinhSach['maChinhSach'], $maYeuCau]); $maPhieuMuon = $csdl->lastInsertId();
        $taiLieu = $csdl->prepare('SELECT maTaiLieu FROM chiTietYeuCauMuon WHERE maYeuCauMuon = ?'); $taiLieu->execute([$maYeuCau]);
        $capNhatSoLuong = $csdl->prepare('UPDATE taiLieu SET soLuongCon = soLuongCon - 1 WHERE maTaiLieu = ? AND soLuongCon > 0');
        $themChiTiet = $csdl->prepare('INSERT INTO chiTietPhieuMuon (maPhieuMuon, maTaiLieu, hanTra, trangThai) VALUES (?, ?, DATE_ADD(CURDATE(), INTERVAL ? DAY), "dangMuon")');
        foreach ($taiLieu->fetchAll(PDO::FETCH_COLUMN) as $maTaiLieu) { $capNhatSoLuong->execute([$maTaiLieu]); if (!$capNhatSoLuong->rowCount()) throw new Exception('Có tài liệu không còn sẵn sàng.'); $themChiTiet->execute([$maPhieuMuon, $maTaiLieu, $chinhSach['soNgayMuon']]); }
        $lenh = $csdl->prepare('UPDATE yeuCauMuon SET trangThai = "daDuyet", maNguoiXuLy = ?, ngayXuLy = NOW() WHERE maYeuCauMuon = ?'); $lenh->execute([$thuThu['id'], $maYeuCau]);
        $csdl->commit(); traVeJson(['thanhCong' => true]);
    } catch (Exception $loi) {
        if ($csdl->inTransaction()) $csdl->rollBack();
        traVeJson(['loi' => $loi->getMessage() ?: 'Không thể duyệt yêu cầu.'], 422);
    }
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
