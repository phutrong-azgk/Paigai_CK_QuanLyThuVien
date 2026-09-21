<?php
session_start();
$loi = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $maSo = strtoupper(trim($_POST['code'] ?? ''));
    $hoTen = trim($_POST['name'] ?? '');
    $thuDienTu = trim($_POST['email'] ?? '');
    $soDienThoai = trim($_POST['phone'] ?? '');
    $ngaySinh = $_POST['birth_date'] ?? '';
    $matKhau = $_POST['password'] ?? '';
    $xacNhanMatKhau = $_POST['password_confirm'] ?? '';
    if (!preg_match('/^[A-Z0-9]{5,16}$/', $maSo)) {
        $loi = 'MSSV hoặc MSGV chỉ gồm chữ in hoa và số, từ 5 đến 16 ký tự.';
    } elseif (strlen($hoTen) < 3 || !filter_var($thuDienTu, FILTER_VALIDATE_EMAIL) || !preg_match('/^[0-9+ ]{9,15}$/', $soDienThoai) || empty($ngaySinh)) {
        $loi = 'Vui lòng điền đầy đủ họ tên, email, số điện thoại và ngày sinh hợp lệ.';
    } elseif (strlen($matKhau) < 6) {
        $loi = 'Mật khẩu phải có ít nhất 6 ký tự.';
    } elseif ($matKhau !== $xacNhanMatKhau) {
        $loi = 'Xác nhận mật khẩu chưa khớp.';
    } else {
        $_SESSION['registered_accounts'][$maSo] = ['password' => $matKhau, 'name' => $hoTen, 'initials' => strtoupper(substr($hoTen, 0, 1)), 'role' => 'reader', 'role_name' => 'Độc giả', 'email' => $thuDienTu, 'phone' => $soDienThoai, 'birth_date' => $ngaySinh];
        $_SESSION['new_account'] = ['code' => $maSo, 'password' => $matKhau];
        header('Location: login.php?registered=1');
        exit;
    }
}
?>
<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Đăng ký | LIBRA</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="assets/login.css">
    <link rel="stylesheet" href="assets/login-layout.css">
</head>

<body class="login-body">
    <main class="login-wrap">
        <section class="login-story"><a class="brand" href="login.php"><span class="brand-mark">L</span><span>LIBRA<small>THƯ VIỆN SỐ</small></span></a>
            <div class="story-copy">
                <p class="eyebrow">ĐĂNG KÝ ĐỘC GIẢ</p>
                <h1>Bắt đầu hành trình<br><em>cùng tri thức.</em></h1>
                <p>MSSV hoặc MSGV của bạn sẽ là tên đăng nhập. Bạn tự thiết lập mật khẩu để bảo vệ tài khoản của mình.</p>
            </div>
            <div class="login-art">
                <div class="sun"></div>
                <div class="book-shape b1"></div>
                <div class="book-shape b2"></div>
                <div class="book-shape b3"></div>
                <div class="plant">⌇</div>
            </div>
        </section>
        <section class="login-form-side">
            <form class="login-card" method="post">
                <p class="eyebrow">TẠO TÀI KHOẢN</p>
                <h2>Đăng ký độc giả</h2>
                <p class="form-intro">Điền thông tin hồ sơ để tạo thẻ thư viện trực tuyến.</p><?php if ($loi): ?><div class="login-error"><?= htmlspecialchars($loi) ?></div><?php endif; ?><label>MSSV hoặc MSGV<input name="code" value="<?= htmlspecialchars($_POST['code'] ?? '') ?>" placeholder="Ví dụ: 22110456" required></label><label>Họ và tên<input name="name" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" placeholder="Nguyễn Văn A" required></label><label>Email<input name="email" type="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" placeholder="sinhvien@university.edu.vn" required></label><label>Số điện thoại<input name="phone" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>" placeholder="0901 234 567" required></label><label>Ngày sinh<input name="birth_date" type="date" value="<?= htmlspecialchars($_POST['birth_date'] ?? '') ?>" required></label><label>Mật khẩu<input name="password" type="password" placeholder="Tối thiểu 6 ký tự" required></label><label>Xác nhận mật khẩu<input name="password_confirm" type="password" placeholder="Nhập lại mật khẩu" required></label><button class="primary login-submit" type="submit">Tạo tài khoản <span>→</span></button>
                <p class="login-footer">Đã có tài khoản? <a href="login.php">Đăng nhập</a></p>
            </form>
        </section>
    </main>
</body>

</html>