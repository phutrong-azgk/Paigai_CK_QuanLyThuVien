<?php
require __DIR__ . '/api/khoiTao.php';
header('Content-Type: text/html; charset=utf-8');

$diaChiMay = $_SERVER['REMOTE_ADDR'] ?? '';
if (!in_array($diaChiMay, ['127.0.0.1', '::1'], true)) {
    http_response_code(403);
    exit('Trang này chỉ dùng trên máy cục bộ.');
}

$loi = '';
$vaiTroChon = $_POST['vaiTro'] ?? '';
$maVaiTro = ['docGia' => 'DOCGIA', 'thuThu' => 'THUTHU', 'quanTri' => 'ADMIN'][$vaiTroChon] ?? '';
if ($maVaiTro) {
    $csdl = ketNoiCSDL();
    $noiThem = $maVaiTro === 'DOCGIA' ? ' JOIN hoSoDocGia hs ON hs.maNguoiDung = nd.maNguoiDung' : '';
    $lenh = $csdl->prepare("SELECT nd.maNguoiDung, nd.tenDangNhap, nd.hoTen, nd.thuDienTu, nd.soDienThoai, nd.ngaySinh, vt.ten FROM nguoiDung nd JOIN vaiTro vt ON vt.maVaiTro = nd.maVaiTro$noiThem WHERE vt.ma = ? AND nd.trangThai = 'hoatDong' ORDER BY nd.maNguoiDung LIMIT 1");
    $lenh->execute([$maVaiTro]);
    $nguoiDung = $lenh->fetch(PDO::FETCH_ASSOC);
    if ($nguoiDung) {
        $kyTuDau = function_exists('mb_substr') ? mb_substr($nguoiDung['hoTen'], 0, 1) : substr($nguoiDung['hoTen'], 0, 1);
        $_SESSION['library_user'] = ['id' => (int)$nguoiDung['maNguoiDung'], 'username' => $nguoiDung['tenDangNhap'], 'name' => $nguoiDung['hoTen'], 'initials' => strtoupper($kyTuDau), 'role_name' => $nguoiDung['ten'], 'email' => $nguoiDung['thuDienTu'], 'phone' => $nguoiDung['soDienThoai'], 'birth_date' => $nguoiDung['ngaySinh']];
        $_SESSION['role'] = ['DOCGIA' => 'reader', 'THUTHU' => 'librarian', 'ADMIN' => 'admin'][$maVaiTro];
        header('Location: index.php');
        exit;
    }
    $loi = 'Chưa có tài khoản hoạt động phù hợp trong dữ liệu MySQL.';
}
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Đăng nhập nhanh | LIBRA</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/style.css">
  <link rel="stylesheet" href="assets/login.css">
  <style>
    .dang-nhap-nhanh { background:#fff; border:1px solid #e0e5df; border-radius:14px; box-shadow:0 15px 42px rgba(31,48,39,.09); max-width:470px; padding:32px; width:100% }
    .dang-nhap-nhanh h1 { color:#2d3935; font:700 29px 'Playfair Display', 'Segoe UI', Arial, sans-serif; margin:5px 0 10px }
    .dang-nhap-nhanh > p { color:#6e7773; font-size:13px; line-height:1.65; margin:0 0 22px }
    .vai-tro-nhanh { display:grid; gap:10px }
    .vai-tro-nhanh button { align-items:center; background:#fff; border:1px solid #dce4de; border-radius:9px; color:#34423c; cursor:pointer; display:flex; font:600 13px 'Playfair Display', 'Segoe UI', Arial, sans-serif; justify-content:space-between; padding:15px; text-align:left }
    .vai-tro-nhanh button:hover { border-color:#d26440; color:#c65d3c }
    .vai-tro-nhanh small { color:#8a928e; font-size:12px; font-weight:400 }
    .canh-bao-nhanh { background:#fff4de; border-radius:7px; color:#9a702d; font-size:12px; line-height:1.6; margin-top:20px; padding:10px 12px }
  </style>
</head>
<body class="login-body">
  <main class="login-wrap">
    <section class="login-story"><a class="brand" href="login.php"><span class="brand-mark">L</span><span>LIBRA<small>THƯ VIỆN SỐ</small></span></a><div class="story-copy"><p class="eyebrow">MÔI TRƯỜNG PHÁT TRIỂN</p><h1>Vào nhanh<br><em>từng vai trò.</em></h1><p>Chọn giao diện cần kiểm tra mà không phải nhập mật khẩu.</p></div></section>
    <section class="login-form-side"><div class="dang-nhap-nhanh"><p class="eyebrow">ĐĂNG NHẬP NHANH</p><h1>Chọn vai trò</h1><p>Hệ thống dùng tài khoản hoạt động đầu tiên của từng vai trò trong cơ sở dữ liệu.</p><?php if ($loi): ?><div class="login-error"><?= htmlspecialchars($loi) ?></div><?php endif; ?><form method="post" class="vai-tro-nhanh"><button name="vaiTro" value="docGia" type="submit">Độc giả <small>Mượn, trả và tra cứu tài liệu →</small></button><button name="vaiTro" value="thuThu" type="submit">Thủ thư <small>Quản lý nghiệp vụ thư viện →</small></button><button name="vaiTro" value="quanTri" type="submit">Quản trị viên <small>Quản lý tài khoản và chính sách →</small></button></form><div class="canh-bao-nhanh">Trang tạm thời dành cho phát triển. Xóa file <b>dangNhapNhanh.php</b> trước khi đưa hệ thống vào sử dụng chính thức.</div></div></section>
  </main>
</body>
</html>
