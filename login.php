<?php
session_start();
$taiKhoanMau = [
    'DG2026001' => ['password' => '123456', 'name' => 'Nguyễn Thu An', 'initials' => 'NT', 'role' => 'reader', 'role_name' => 'Độc giả'],
    'TT001' => ['password' => '123456', 'name' => 'Trần Minh Quân', 'initials' => 'TQ', 'role' => 'librarian', 'role_name' => 'Thủ thư'],
    'ADMIN01' => ['password' => 'admin123', 'name' => 'Lê Hoàng Nam', 'initials' => 'LN', 'role' => 'admin', 'role_name' => 'Quản trị viên'],
];
$taiKhoanMau = array_merge($taiKhoanMau, $_SESSION['registered_accounts'] ?? []);
$loi = '';
$taiKhoanMoi = $_SESSION['new_account'] ?? null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tenDangNhap = strtoupper(trim($_POST['username'] ?? ''));
    $matKhau = $_POST['password'] ?? '';
    if (isset($taiKhoanMau[$tenDangNhap]) && $taiKhoanMau[$tenDangNhap]['password'] === $matKhau) {
        $_SESSION['library_user'] = $taiKhoanMau[$tenDangNhap];
        $_SESSION['library_user']['username'] = $tenDangNhap;
        $_SESSION['role'] = $taiKhoanMau[$tenDangNhap]['role'];
        header('Location: index.php');
        exit;
    }
    $loi = 'Mã đăng nhập hoặc mật khẩu chưa đúng.';
}
?>
<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Đăng nhập | LIBRA</title>
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
                <p class="eyebrow">TRUNG TÂM HỌC LIỆU</p>
                <h1>Tri thức mở ra<br><em>mọi hành trình.</em></h1>
                <p>Tra cứu học liệu, quản lý mượn trả và kết nối với kho tàng tri thức của trường đại học.</p>
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
                <p class="eyebrow">CHÀO MỪNG BẠN TRỞ LẠI</p>
                <h2>Đăng nhập hệ thống</h2>
                <p class="form-intro">Sử dụng MSSV, MSGV hoặc tài khoản quản trị để tiếp tục.</p><?php if ($loi): ?><div class="login-error"><?= htmlspecialchars($loi) ?></div><?php endif; ?><?php if (isset($_GET['registered']) && $taiKhoanMoi): ?><div class="account-created">Tạo tài khoản thành công.<br><b><?= htmlspecialchars($taiKhoanMoi['code']) ?></b> / <b><?= htmlspecialchars($taiKhoanMoi['password']) ?></b></div><?php endif; ?><label>Mã đăng nhập<input name="username" autocomplete="username" placeholder="Ví dụ: DG2026001" required autofocus></label><label>Mật khẩu<div class="password-wrap"><input name="password" type="password" autocomplete="current-password" placeholder="Nhập mật khẩu" required><button type="button" id="toggle-password">◉</button></div></label>
                <div class="login-options"><label class="remember"><input type="checkbox"> Ghi nhớ đăng nhập</label><a href="#">Quên mật khẩu?</a></div><button class="primary login-submit" type="submit">Đăng nhập <span>→</span></button>
                <p class="login-footer">Chưa có tài khoản? <a href="dangKy.php">Đăng ký độc giả</a></p>
                <div class="demo-accounts"><b>Tài khoản demo</b><button type="button" class="demo-account" data-user="DG2026001" data-pass="123456"><span>Độc giả</span> DG2026001 / 123456</button><button type="button" class="demo-account" data-user="TT001" data-pass="123456"><span>Thủ thư</span> TT001 / 123456</button><button type="button" class="demo-account" data-user="ADMIN01" data-pass="admin123"><span>Admin</span> ADMIN01 / admin123</button></div>
            </form>
        </section>
    </main>
    <script>
        document.querySelectorAll('.demo-account').forEach(b => b.onclick = () => {
            document.querySelector('[name=username]').value = b.dataset.user;
            document.querySelector('[name=password]').value = b.dataset.pass
        });
        document.querySelector('#toggle-password').onclick = () => {
            const i = document.querySelector('[name=password]');
            i.type = i.type === 'password' ? 'text' : 'password'
        }
    </script>
</body>

</html>