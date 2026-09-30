<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Khởi tạo Admin | LIBRA</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/style.css">
  <link rel="stylesheet" href="assets/login.css">
</head>
<body class="login-body">
  <main class="login-wrap">
    <section class="login-story"><a class="brand" href="login.php"><span class="brand-mark">L</span><span>LIBRA<small>THƯ VIỆN SỐ</small></span></a><div class="story-copy"><p class="eyebrow">THIẾT LẬP LẦN ĐẦU</p><h1>Khởi tạo<br><em>quản trị viên.</em></h1><p>Biểu mẫu này chỉ hoạt động khi chưa có mật khẩu Admin trong cơ sở dữ liệu.</p></div></section>
    <section class="login-form-side">
      <form id="form-khoi-tao-admin" class="login-card">
        <p class="eyebrow">BẢO MẬT HỆ THỐNG</p><h2>Tạo mật khẩu Admin</h2><p class="form-intro">Nhập token thiết lập trong biến môi trường <code>APP_SETUP_TOKEN</code>, sau đó đặt mã đăng nhập và mật khẩu.</p>
        <div id="loi-khoi-tao" class="login-error" hidden></div>
        <label>Token thiết lập<input name="token" type="password" required autocomplete="off"></label>
        <label>Mã đăng nhập Admin<input name="tenDangNhap" placeholder="Ví dụ: ADMIN01" minlength="5" maxlength="30" required autocomplete="username"></label>
        <label>Mật khẩu<div class="password-wrap"><input name="matKhau" type="password" minlength="12" required autocomplete="new-password"><button type="button" id="hien-mat-khau">◉</button></div></label>
        <button class="primary login-submit" type="submit">Hoàn tất khởi tạo <span>→</span></button>
      </form>
    </section>
  </main>
  <script>
    const form = document.querySelector('#form-khoi-tao-admin'), loi = document.querySelector('#loi-khoi-tao');
    document.querySelector('#hien-mat-khau').onclick = () => { const o = form.matKhau; o.type = o.type === 'password' ? 'text' : 'password'; };
    form.onsubmit = async suKien => { suKien.preventDefault(); loi.hidden = true; try { const phanHoi = await fetch('api/khoiTaoAdmin.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(Object.fromEntries(new FormData(form))) }); const duLieu = await phanHoi.json(); if (!phanHoi.ok) throw new Error(duLieu.loi || 'Không thể khởi tạo Admin.'); location.href = 'login.php'; } catch (loiApi) { loi.textContent = loiApi.message; loi.hidden = false; } };
  </script>
</body>
</html>
