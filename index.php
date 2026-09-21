<?php
session_start();
if (empty($_SESSION['library_user'])) {
  header('Location: login.php');
  exit;
}
$nguoiDungHienTai = $_SESSION['library_user'];
$vaiTroHienTai = $_SESSION['role'];
$thuDienTuHoSo = $nguoiDungHienTai['email'] ?? 'thuan@example.com';
$soDienThoaiHoSo = $nguoiDungHienTai['phone'] ?? '0901 234 567';
$ngaySinhHoSo = $nguoiDungHienTai['birth_date'] ?? '2003-05-12';
$maHoSo = $nguoiDungHienTai['username'] ?? 'DG-2026-0145';
$danhSachTaiLieu = [
  ['title' => 'Cơ Sở Dữ Liệu', 'author' => 'Abraham Silberschatz', 'category' => 'Công nghệ thông tin', 'code' => 'CNTT-101', 'available' => 8, 'color' => 'blue', 'icon' => '◫'],
  ['title' => 'Nhập Môn Trí Tuệ Nhân Tạo', 'author' => 'Stuart Russell & Peter Norvig', 'category' => 'Công nghệ thông tin', 'code' => 'CNTT-145', 'available' => 5, 'color' => 'purple', 'icon' => '◉'],
  ['title' => 'Lập Trình Hướng Đối Tượng Với Java', 'author' => 'Herbert Schildt', 'category' => 'Công nghệ thông tin', 'code' => 'CNTT-128', 'available' => 3, 'color' => 'green', 'icon' => '⌘'],
  ['title' => 'Nguyên Lý Kế Toán', 'author' => 'Weygandt, Kimmel & Kieso', 'category' => 'Kinh tế', 'code' => 'KT-021', 'available' => 6, 'color' => 'orange', 'icon' => '₫'],
  ['title' => 'Kinh Tế Vi Mô', 'author' => 'N. Gregory Mankiw', 'category' => 'Kinh tế', 'code' => 'KT-044', 'available' => 2, 'color' => 'green', 'icon' => '↗'],
  ['title' => 'Giáo Trình Luật Dân Sự Việt Nam', 'author' => 'Đại học Luật Hà Nội', 'category' => 'Luật', 'code' => 'LUAT-032', 'available' => 4, 'color' => 'purple', 'icon' => '§'],
  ['title' => 'Pháp Luật Đại Cương', 'author' => 'Nguyễn Văn Động', 'category' => 'Luật', 'code' => 'LUAT-008', 'available' => 0, 'color' => 'blue', 'icon' => '⚖'],
  ['title' => 'Giải Phẫu Người', 'author' => 'Nguyễn Quang Quyền', 'category' => 'Y dược', 'code' => 'YD-015', 'available' => 3, 'color' => 'orange', 'icon' => '✚'],
  ['title' => 'Sinh Lý Học Y Khoa', 'author' => 'Guyton & Hall', 'category' => 'Y dược', 'code' => 'YD-027', 'available' => 1, 'color' => 'green', 'icon' => '✥'],
  ['title' => 'English for Academic Purposes', 'author' => 'Edward de Chazal', 'category' => 'Ngôn ngữ Anh', 'code' => 'NNA-019', 'available' => 7, 'color' => 'blue', 'icon' => 'A'],
  ['title' => 'Academic Writing for Students', 'author' => 'Stephen Bailey', 'category' => 'Ngôn ngữ Anh', 'code' => 'NNA-033', 'available' => 2, 'color' => 'purple', 'icon' => '✎'],
];
?>
<!doctype html>
<html lang="vi">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>LIBRA | Quản lý thư viện</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/style.css">
  <link rel="stylesheet" href="assets/login.css">
  <link rel="stylesheet" href="assets/tuongTac.css">
  <link rel="stylesheet" href="assets/quanLySach.css">
  <link rel="stylesheet" href="assets/thuThu.css">
  <link rel="stylesheet" href="assets/yeuCauCho.css">
  <link rel="stylesheet" href="assets/chonTaiLieu.css">
</head>

<body data-role="<?= htmlspecialchars($vaiTroHienTai) ?>">
  <aside class="sidebar">
    <a class="brand" href="#"><span class="brand-mark">L</span><span>LIBRA<small>THƯ VIỆN SỐ</small></span></a>
    <?php if ($vaiTroHienTai === 'reader'): ?><nav id="reader-nav" class="nav-group">
        <p>KHÔNG GIAN ĐỘC GIẢ</p><a class="nav-link active" href="#dashboard" data-page="dashboard"><i>⌂</i> Tổng quan</a><a class="nav-link" href="#catalog" data-page="catalog"><i>⌕</i> Tìm kiếm sách</a><a class="nav-link" href="#loans" data-page="loans"><i>▣</i> Mượn & trả sách</a><a class="nav-link" href="#history" data-page="history"><i>◷</i> Lịch sử mượn trả</a><a class="nav-link" href="#profile" data-page="profile"><i>♙</i> Hồ sơ cá nhân</a>
      </nav><?php endif; ?>
    <?php if ($vaiTroHienTai === 'librarian'): ?><nav id="staff-nav" class="nav-group">
        <p>QUẢN LÝ NGHIỆP VỤ</p><a class="nav-link active" href="#staff-dashboard" data-page="staff-dashboard"><i>⌂</i> Tổng quan</a><a class="nav-link" href="#book-admin" data-page="book-admin"><i>▤</i> Quản lý sách</a><a class="nav-link" href="#borrow-admin" data-page="borrow-admin"><i>↔</i> Quản lý mượn sách</a><a class="nav-link" href="#return-admin" data-page="return-admin"><i>↵</i> Quản lý trả sách</a><a class="nav-link" href="#reader-admin" data-page="reader-admin"><i>♙</i> Hồ sơ độc giả</a><a class="nav-link" href="#fines" data-page="fines"><i>₫</i> Quản lý phạt</a>
      </nav><?php endif; ?>
    <?php if ($vaiTroHienTai === 'admin'): ?><nav id="admin-nav" class="nav-group">
        <p>QUẢN TRỊ HỆ THỐNG</p><a class="nav-link active" href="#admin-dashboard" data-page="admin-dashboard"><i>⌂</i> Tổng quan</a><a class="nav-link" href="#accounts" data-page="accounts"><i>♙</i> Quản lý tài khoản</a><a class="nav-link" href="#categories" data-page="categories"><i>▦</i> Quản lý danh mục</a><a class="nav-link" href="#policies" data-page="policies"><i>◈</i> Chính sách thư viện</a>
      </nav><?php endif; ?>
    <div class="sidebar-user">
      <div class="avatar"><?= htmlspecialchars($nguoiDungHienTai['initials']) ?></div>
      <div><b><?= htmlspecialchars($nguoiDungHienTai['name']) ?></b><span><?= htmlspecialchars($nguoiDungHienTai['role_name']) ?></span></div><a class="logout" href="logout.php" title="Đăng xuất">⇥</a>
    </div>
  </aside>
  <main>
    <header><button class="menu-btn">☰</button>
      <div class="crumb">Trang chủ <span>/</span> <b id="page-title">Tổng quan</b></div>
      <div class="header-actions"><button class="icon-btn">⌕</button><button class="notification icon-btn">♧<em></em></button><span class="date">Thứ Ba, 16 tháng 9, 2026</span></div>
    </header>
    <?php if ($vaiTroHienTai === 'reader'): ?>
      <section id="dashboard" class="page active">
        <div class="welcome">
          <div>
            <p class="eyebrow">XIN CHÀO, THU AN</p>
            <h1>Thư viện luôn<br><em>rộng mở.</em></h1>
            <p class="sub">Khám phá tri thức mới và quản lý hành trình đọc của bạn tại một nơi.</p><button class="primary" data-go="catalog">Khám phá sách <span>→</span></button>
          </div>
          <div class="welcome-art">
            <div class="sun"></div>
            <div class="book-shape b1"></div>
            <div class="book-shape b2"></div>
            <div class="book-shape b3"></div>
            <div class="plant">⌇</div>
          </div>
        </div>
        <div class="stats">
          <article>
            <div class="stat-icon peach">▣</div>
            <div><b>02</b><span>Sách đang mượn</span></div><small>01 sắp đến hạn</small>
          </article>
          <article>
            <div class="stat-icon lilac">◷</div>
            <div><b>12</b><span>Sách đã đọc</span></div><small>Trong năm 2026</small>
          </article>
          <article>
            <div class="stat-icon mint">♢</div>
            <div><b>08</b><span>Điểm thưởng</span></div><small>Độc giả tích cực</small>
          </article>
        </div>
        <div class="content-grid">
          <section class="panel loans-panel">
            <div class="section-head">
              <div>
                <p class="eyebrow">HÀNH TRÌNH ĐỌC</p>
                <h2>Sách đang mượn</h2>
              </div><a href="#loans" data-go="loans">Xem tất cả →</a>
            </div>
            <div class="loan-row">
              <div class="cover blue">◫</div>
              <div class="loan-info"><b>Cơ Sở Dữ Liệu</b><span>Abraham Silberschatz</span>
                <div class="progress"><i style="width:70%"></i></div><small>Còn <b>04 ngày</b> để trả sách</small>
              </div><button class="outline renew-btn">Gia hạn</button>
            </div>
            <div class="loan-row">
              <div class="cover green">↗</div>
              <div class="loan-info"><b>Kinh Tế Vi Mô</b><span>N. Gregory Mankiw</span>
                <div class="progress"><i style="width:35%"></i></div><small>Hạn trả: <b>28/09/2026</b></small>
              </div><button class="outline renew-btn">Gia hạn</button>
            </div>
          </section>
          <section class="panel">
            <div class="section-head">
              <div>
                <p class="eyebrow">GỢI Ý CHO BẠN</p>
                <h2>Có thể bạn thích</h2>
              </div><a href="#catalog" data-go="catalog">Khám phá →</a>
            </div>
            <div class="recommend">
              <div class="cover purple">◉</div>
              <div><b>Nhập Môn Trí Tuệ Nhân Tạo</b><span>Stuart Russell & Peter Norvig</span>
                <p>Học liệu gợi ý cho sinh viên quan tâm đến dữ liệu và trí tuệ nhân tạo.</p><a class="text-btn" href="chiTietTaiLieu.php?id=CNTT-145">Xem chi tiết →</a>
              </div>
            </div>
          </section>
        </div>
      </section>
      <section id="catalog" class="page">
        <div class="page-heading">
          <p class="eyebrow">KHO SÁCH LIBRA</p>
          <h1>Tìm cuốn sách dành cho bạn</h1>
          <p>Tra cứu theo tên sách, tác giả, thể loại hoặc mã sách.</p>
        </div>
        <div class="search-box"><span>⌕</span><input id="book-search" placeholder="Nhập tên sách, tác giả hoặc từ khóa..."><button class="primary">Tìm kiếm</button></div>
        <div class="filter-row"><button class="chip active" data-filter="">Tất cả</button><button class="chip" data-filter="công nghệ thông tin">Công nghệ thông tin</button><button class="chip" data-filter="kinh tế">Kinh tế</button><button class="chip" data-filter="luật">Luật</button><button class="chip" data-filter="y dược">Y dược</button><button class="chip" data-filter="ngôn ngữ anh">Ngôn ngữ Anh</button><span id="book-count">11 đầu sách</span></div>
        <div id="book-grid" class="book-grid"><?php foreach ($danhSachTaiLieu as $taiLieu): ?><article class="book-card" tabindex="0" role="link" data-search="<?= strtolower($taiLieu['title'] . ' ' . $taiLieu['author'] . ' ' . $taiLieu['category']) ?>" data-detail-url="chiTietTaiLieu.php?id=<?= urlencode($taiLieu['code']) ?>">
              <div class="cover large <?= $taiLieu['color'] ?>"><?= $taiLieu['icon'] ?><span><?= htmlspecialchars($taiLieu['code']) ?></span></div>
              <div class="book-card-body">
                <p><?= htmlspecialchars($taiLieu['category']) ?></p>
                <h3><?= htmlspecialchars($taiLieu['title']) ?></h3><span><?= htmlspecialchars($taiLieu['author']) ?></span>
                <footer><b class="<?= $taiLieu['available'] ? 'available' : 'unavailable' ?>">● <?= $taiLieu['available'] ? 'Còn ' . $taiLieu['available'] . ' cuốn' : 'Đã hết sách' ?></b><a class="detail-btn" href="chiTietTaiLieu.php?id=<?= urlencode($taiLieu['code']) ?>">Xem →</a></footer>
              </div>
            </article><?php endforeach; ?></div>
      </section>
      <section id="loans" class="page">
        <div class="page-heading">
          <p class="eyebrow">QUẢN LÝ MƯỢN</p>
          <h1>Sách của tôi</h1>
          <p>Đăng ký mượn, theo dõi thời hạn và gia hạn sách trực tuyến.</p>
        </div>
        <div class="tabs"><button class="active">Đang mượn (2)</button><button>Lịch sử mượn</button><button>Yêu cầu chờ duyệt</button></div>
        <section class="panel table-panel">
          <table>
            <thead>
              <tr>
                <th>SÁCH</th>
                <th>NGÀY MƯỢN</th>
                <th>HẠN TRẢ</th>
                <th>TRẠNG THÁI</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><b>Cơ Sở Dữ Liệu</b><span>CNTT-101 · Abraham Silberschatz</span></td>
                <td>07/09/2026</td>
                <td><b class="warning">20/09/2026</b></td>
                <td><mark class="yellow">Sắp đến hạn</mark></td>
                <td><button class="outline renew-btn">Gia hạn</button></td>
              </tr>
              <tr>
                <td><b>Kinh Tế Vi Mô</b><span>KT-044 · N. Gregory Mankiw</span></td>
                <td>14/09/2026</td>
                <td>28/09/2026</td>
                <td><mark class="green-mark">Đang mượn</mark></td>
                <td><button class="outline renew-btn">Gia hạn</button></td>
              </tr>
            </tbody>
          </table>
        </section>
      </section>
      <section id="history" class="page">
        <div class="page-heading">
          <p class="eyebrow">HÀNH TRÌNH ĐỌC</p>
          <h1>Lịch sử mượn & trả</h1>
        </div>
        <section class="panel table-panel">
          <table>
            <thead>
              <tr>
                <th>SÁCH</th>
                <th>MƯỢN</th>
                <th>ĐÃ TRẢ</th>
                <th>ĐÁNH GIÁ</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><b>Muôn Kiếp Nhân Sinh</b><span>Nguyên Phong</span></td>
                <td>12/08/2026</td>
                <td>25/08/2026</td>
                <td class="stars">★★★★★</td>
              </tr>
              <tr>
                <td><b>Tuổi Trẻ Đáng Giá Bao Nhiêu</b><span>Rosie Nguyễn</span></td>
                <td>02/07/2026</td>
                <td>16/07/2026</td>
                <td class="stars">★★★★☆</td>
              </tr>
            </tbody>
          </table>
        </section>
      </section>
      <section id="profile" class="page">
        <div class="page-heading">
          <p class="eyebrow">TÀI KHOẢN CỦA BẠN</p>
          <h1>Hồ sơ cá nhân</h1>
        </div>
        <div class="profile-layout">
          <section class="panel profile-card">
            <div class="avatar xl"><?= htmlspecialchars($nguoiDungHienTai['initials']) ?></div>
            <h2><?= htmlspecialchars($nguoiDungHienTai['name']) ?></h2>
            <p><?= htmlspecialchars($maHoSo) ?> · <?= htmlspecialchars($nguoiDungHienTai['role_name']) ?></p>
            <hr><span>Email</span><b><?= htmlspecialchars($thuDienTuHoSo) ?></b><span>Số điện thoại</span><b><?= htmlspecialchars($soDienThoaiHoSo) ?></b>
          </section>
          <section class="panel form-card">
            <h2>Thông tin cá nhân</h2>
            <div class="form-grid"><label>Họ và tên<input value="<?= htmlspecialchars($nguoiDungHienTai['name']) ?>"></label><label>Email<input value="<?= htmlspecialchars($thuDienTuHoSo) ?>"></label><label>Số điện thoại<input value="<?= htmlspecialchars($soDienThoaiHoSo) ?>"></label><label>Ngày sinh<input type="date" value="<?= htmlspecialchars($ngaySinhHoSo) ?>"></label></div><button class="primary save-btn">Lưu thay đổi</button>
          </section>
        </div>
      </section>
    <?php endif; ?>
    <?php
    $trangTheoVaiTro = [];
    if ($vaiTroHienTai === 'librarian') {
      $trangTheoVaiTro = ['staff-dashboard' => 'Bảng điều khiển thủ thư', 'book-admin' => 'Quản lý thông tin sách', 'borrow-admin' => 'Quản lý mượn sách', 'return-admin' => 'Quản lý trả sách', 'reader-admin' => 'Quản lý hồ sơ độc giả', 'fines' => 'Quản lý phạt'];
    } elseif ($vaiTroHienTai === 'admin') {
      $trangTheoVaiTro = ['admin-dashboard' => 'Bảng điều khiển quản trị', 'accounts' => 'Quản lý tài khoản', 'categories' => 'Quản lý danh mục', 'policies' => 'Quản lý chính sách'];
    }
    foreach ($trangTheoVaiTro as $maTrang => $tieuDeTrang):
      $duLieuMau = [
        'staff-dashboard' => ['code' => '#TT-093', 'title' => '12 phiếu mượn cần xác nhận', 'sub' => 'Ca trực sáng · Quầy lưu thông 01', 'date' => '16/09/2026', 'status' => 'Cần xử lý', 'class' => 'yellow'],
        'book-admin' => ['code' => '#TL-145', 'title' => 'Giáo trình Cơ sở dữ liệu', 'sub' => 'Tồn kho: 18 bản · Kệ CNTT-03', 'date' => '15/09/2026', 'status' => 'Đang lưu hành', 'class' => 'green-mark'],
        'borrow-admin' => ['code' => '#PM-2409', 'title' => 'Phiếu mượn của Nguyễn Minh Anh', 'sub' => 'MSSV 22110432 · 02 tài liệu', 'date' => '16/09/2026', 'status' => 'Chờ duyệt', 'class' => 'yellow'],
        'return-admin' => ['code' => '#PT-1128', 'title' => 'Trả sách: Nhà Giả Kim', 'sub' => 'Độc giả: Phạm Quốc Bảo · Không phát sinh phạt', 'date' => '16/09/2026', 'status' => 'Đã trả', 'class' => 'green-mark'],
        'reader-admin' => ['code' => '#DG-068', 'title' => 'Hồ sơ thẻ độc giả hết hạn', 'sub' => 'Lê Thị Hương · Khoa Kinh tế', 'date' => '14/09/2026', 'status' => 'Cần gia hạn', 'class' => 'yellow'],
        'fines' => ['code' => '#VP-031', 'title' => 'Quá hạn 03 ngày', 'sub' => 'Trần Gia Huy · Phí tạm tính 15.000 đ', 'date' => '16/09/2026', 'status' => 'Chưa thu', 'class' => 'yellow'],
        'admin-dashboard' => ['code' => '#HT-047', 'title' => 'Nhật ký đăng nhập hệ thống', 'sub' => '42 phiên truy cập trong ngày', 'date' => '16/09/2026', 'status' => 'Bình thường', 'class' => 'green-mark'],
        'accounts' => ['code' => '#TK-019', 'title' => 'Tài khoản thủ thư mới', 'sub' => 'Phạm Khánh Linh · Chờ cấp quyền', 'date' => '15/09/2026', 'status' => 'Chờ duyệt', 'class' => 'yellow'],
        'categories' => ['code' => '#DM-072', 'title' => 'Danh mục Khoa học dữ liệu', 'sub' => 'Thuộc Khoa Công nghệ thông tin', 'date' => '12/09/2026', 'status' => 'Đang sử dụng', 'class' => 'green-mark'],
        'policies' => ['code' => '#CS-014', 'title' => 'Quy định mượn dành cho sinh viên', 'sub' => 'Tối đa 03 sách · 14 ngày · Gia hạn 01 lần', 'date' => '01/09/2026', 'status' => 'Đang áp dụng', 'class' => 'green-mark'],
      ];
      $banGhi = $duLieuMau[$maTrang];
    ?><section id="<?= $maTrang ?>" class="page admin-page">
        <div class="page-heading">
          <p class="eyebrow"><?= strpos($maTrang, 'admin') !== false || in_array($maTrang, ['accounts', 'categories', 'policies']) ? 'QUẢN TRỊ HỆ THỐNG' : 'NGHIỆP VỤ THƯ VIỆN' ?></p>
          <h1><?= $tieuDeTrang ?></h1>
          <p>Quản lý dữ liệu và vận hành thư viện hiệu quả.</p>
        </div>
        <?php if ($maTrang === 'book-admin'): ?>
          <div class="inventory-stats">
            <article><span class="inventory-icon blue-soft">▤</span>
              <div><b>1.248</b><small>Tổng đầu sách</small></div>
            </article>
            <article><span class="inventory-icon green-soft">✓</span>
              <div><b>1.146</b><small>Bản sẵn sàng</small></div>
            </article>
            <article><span class="inventory-icon orange-soft">↔</span>
              <div><b>65</b><small>Đang được mượn</small></div>
            </article>
            <article><span class="inventory-icon red-soft">!</span>
              <div><b>37</b><small>Cần kiểm tra</small></div>
            </article>
          </div>
          <section class="panel inventory-panel">
            <div class="inventory-toolbar">
              <div class="inventory-search"><span>⌕</span><input id="inventory-search" placeholder="Tìm theo mã, tên tài liệu hoặc tác giả..."></div>
              <select class="inventory-filter">
                <option>Tất cả danh mục</option>
                <option>Công nghệ thông tin</option>
                <option>Kinh tế</option>
                <option>Luật</option>
                <option>Y dược</option>
              </select>
              <button class="primary book-action" data-message="Mở biểu mẫu thêm tài liệu mới.">+ Thêm sách</button>
            </div>
            <div class="inventory-caption"><b>Danh mục tài liệu</b><span>Hiển thị 5 trên 1.248 đầu sách</span></div>
            <div class="table-panel inventory-table">
              <table>
                <thead>
                  <tr>
                    <th>TÀI LIỆU</th>
                    <th>MÃ / ISBN</th>
                    <th>VỊ TRÍ</th>
                    <th>TỒN KHO</th>
                    <th>TRẠNG THÁI</th>
                    <th>THAO TÁC</th>
                  </tr>
                </thead>
                <tbody>
                  <tr data-book-row="cơ sở dữ liệu abraham silberschatz cntt-101">
                    <td>
                      <div class="table-book"><i class="blue">◫</i>
                        <div><b>Cơ Sở Dữ Liệu</b><span>Abraham Silberschatz</span></div>
                      </div>
                    </td>
                    <td>CNTT-101<span>ISBN 978-604-0-12345-6</span></td>
                    <td>Tầng 3 · Kệ CNTT-03</td>
                    <td><b>08</b> bản</td>
                    <td><mark class="green-mark">Sẵn sàng</mark></td>
                    <td><button class="table-action book-action" data-message="Mở form chỉnh sửa Cơ Sở Dữ Liệu.">Sửa</button><button class="table-action book-action" data-message="Đã mở lịch sử bản sao của Cơ Sở Dữ Liệu.">⋮</button></td>
                  </tr>
                  <tr data-book-row="nhập môn trí tuệ nhân tạo stuart russell cntt-145">
                    <td>
                      <div class="table-book"><i class="purple">◉</i>
                        <div><b>Nhập Môn Trí Tuệ Nhân Tạo</b><span>Stuart Russell & Peter Norvig</span></div>
                      </div>
                    </td>
                    <td>CNTT-145<span>ISBN 978-013-604259-4</span></td>
                    <td>Tầng 3 · Kệ AI-01</td>
                    <td><b>05</b> bản</td>
                    <td><mark class="green-mark">Sẵn sàng</mark></td>
                    <td><button class="table-action book-action" data-message="Mở form chỉnh sửa Nhập Môn Trí Tuệ Nhân Tạo.">Sửa</button><button class="table-action book-action" data-message="Đã mở lịch sử bản sao của tài liệu.">⋮</button></td>
                  </tr>
                  <tr data-book-row="giáo trình luật dân sự việt nam đại học luật luat-032">
                    <td>
                      <div class="table-book"><i class="purple">§</i>
                        <div><b>Giáo Trình Luật Dân Sự Việt Nam</b><span>Đại học Luật Hà Nội</span></div>
                      </div>
                    </td>
                    <td>LUAT-032<span>ISBN 978-604-72-9876-2</span></td>
                    <td>Tầng 2 · Kệ LUAT-05</td>
                    <td><b>04</b> bản</td>
                    <td><mark class="green-mark">Sẵn sàng</mark></td>
                    <td><button class="table-action book-action" data-message="Mở form chỉnh sửa Giáo Trình Luật Dân Sự Việt Nam.">Sửa</button><button class="table-action book-action" data-message="Đã mở lịch sử bản sao của tài liệu.">⋮</button></td>
                  </tr>
                  <tr data-book-row="sinh lý học y khoa guyton hall yd-027">
                    <td>
                      <div class="table-book"><i class="green">✥</i>
                        <div><b>Sinh Lý Học Y Khoa</b><span>Guyton & Hall</span></div>
                      </div>
                    </td>
                    <td>YD-027<span>ISBN 978-032-359712-8</span></td>
                    <td>Tầng 4 · Kệ YD-06</td>
                    <td><b>01</b> bản</td>
                    <td><mark class="yellow">Sắp hết</mark></td>
                    <td><button class="table-action book-action" data-message="Mở form chỉnh sửa Sinh Lý Học Y Khoa.">Sửa</button><button class="table-action book-action" data-message="Đã mở lịch sử bản sao của tài liệu.">⋮</button></td>
                  </tr>
                  <tr data-book-row="pháp luật đại cương nguyễn văn động luat-008">
                    <td>
                      <div class="table-book"><i class="blue">⚖</i>
                        <div><b>Pháp Luật Đại Cương</b><span>Nguyễn Văn Động</span></div>
                      </div>
                    </td>
                    <td>LUAT-008<span>ISBN 978-604-73-6241-8</span></td>
                    <td>Tầng 2 · Kệ LUAT-01</td>
                    <td><b>00</b> bản</td>
                    <td><mark class="red-mark">Hết sách</mark></td>
                    <td><button class="table-action book-action" data-message="Mở form chỉnh sửa Pháp Luật Đại Cương.">Sửa</button><button class="table-action book-action" data-message="Đã mở lịch sử bản sao của tài liệu.">⋮</button></td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="inventory-footer"><span>Trang 1 / 250</span>
              <div><button class="page-button">←</button><button class="page-button active">1</button><button class="page-button">2</button><button class="page-button">3</button><button class="page-button">→</button></div>
            </div>
          </section>
        <?php elseif ($vaiTroHienTai === 'librarian'): ?>
          <?php include __DIR__ . '/partials/giaoDienThuThu.php'; ?>
        <?php else: ?>
          <div class="staff-stats">
            <article><b>1,248</b><span>Tổng đầu sách</span></article>
            <article><b>86</b><span>Độc giả hoạt động</span></article>
            <article><b>14</b><span>Phiếu chờ xử lý</span></article>
          </div>
          <section class="panel table-panel">
            <div class="section-head">
              <h2>Danh sách cần xử lý</h2><button class="primary">+ Thêm mới</button>
            </div>
            <table>
              <thead>
                <tr>
                  <th>MÃ</th>
                  <th>THÔNG TIN</th>
                  <th>NGÀY TẠO</th>
                  <th>TRẠNG THÁI</th>
                  <th>THAO TÁC</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td><?= $banGhi['code'] ?></td>
                  <td><b><?= $banGhi['title'] ?></b><span><?= $banGhi['sub'] ?></span></td>
                  <td><?= $banGhi['date'] ?></td>
                  <td><mark class="<?= $banGhi['class'] ?>"><?= $banGhi['status'] ?></mark></td>
                  <td><button class="text-btn">Xem chi tiết</button></td>
                </tr>
                <tr>
                  <td>#<?= $maTrang === 'fines' ? 'THU-090' : 'LS-001' ?></td>
                  <td><b><?= $maTrang === 'fines' ? 'Thu phí vi phạm tháng 9' : 'Cập nhật gần nhất của hệ thống' ?></b><span><?= $maTrang === 'fines' ? 'Độc giả: Vũ Đức Long · 10.000 đ' : 'Dữ liệu mẫu phục vụ giao diện' ?></span></td>
                  <td>15/09/2026</td>
                  <td><mark class="green-mark">Hoàn tất</mark></td>
                  <td><button class="text-btn">Xem chi tiết</button></td>
                </tr>
              </tbody>
            </table>
          </section>
        <?php endif; ?>
      </section><?php endforeach; ?>
  </main>
  <div id="hop-chon-tai-lieu" class="hop-chon-tai-lieu" aria-hidden="true">
    <div class="noi-dung-chon-tai-lieu">
      <div class="dau-chon-tai-lieu">
        <div>
          <p class="eyebrow">KHO HỌC LIỆU</p>
          <h2 id="tieu-de-chon-tai-lieu">Chọn tài liệu</h2>
        </div><button class="dong-chon-tai-lieu" aria-label="Đóng">×</button>
      </div>
      <div class="o-tim-tai-lieu"><span>⌕</span><input id="tim-tai-lieu-trong-hop" placeholder="Tìm theo tên, mã tài liệu hoặc tác giả..."></div>
      <p class="ghi-chu-chon-tai-lieu">Chọn các tài liệu cần thêm vào phiếu. Hệ thống sẽ kiểm tra số lượng còn lại trước khi xác nhận.</p>
      <div class="danh-sach-chon-tai-lieu">
        <label data-muc-chon-tai-lieu="cơ sở dữ liệu abraham silberschatz cntt-101"><input type="checkbox" checked><i class="blue">◫</i><span><b>Cơ Sở Dữ Liệu</b><small>CNTT-101 · Còn 08 bản</small></span></label>
        <label data-muc-chon-tai-lieu="nhập môn trí tuệ nhân tạo stuart russell cntt-145"><input type="checkbox"><i class="purple">◉</i><span><b>Nhập Môn Trí Tuệ Nhân Tạo</b><small>CNTT-145 · Còn 05 bản</small></span></label>
        <label data-muc-chon-tai-lieu="nguyên lý kế toán weygandt kt-021"><input type="checkbox"><i class="orange">₫</i><span><b>Nguyên Lý Kế Toán</b><small>KT-021 · Còn 06 bản</small></span></label>
        <label data-muc-chon-tai-lieu="pháp luật đại cương nguyễn văn động luat-008"><input type="checkbox" disabled><i class="blue">⚖</i><span><b>Pháp Luật Đại Cương</b><small>LUAT-008 · Tạm hết bản</small></span><em>Hết sách</em></label>
      </div>
      <div class="chan-chon-tai-lieu"><span id="so-luong-da-chon">01 tài liệu đã chọn</span>
        <div><button class="outline dong-chon-tai-lieu">Hủy</button><button class="primary xac-nhan-chon-tai-lieu">Thêm vào phiếu <span>→</span></button></div>
      </div>
    </div>
  </div>
  <div id="hopThoai" class="modal">
    <div class="modal-card"><button class="close">×</button>
      <p class="eyebrow">THÔNG TIN SÁCH</p>
      <h2 id="hopThoai-title">Đắc Nhân Tâm</h2>
      <p>Cuốn sách hiện có trong kho. Bạn có thể gửi yêu cầu mượn và theo dõi trạng thái xử lý tại mục Sách của tôi.</p><button class="primary borrow-btn">Đăng ký mượn sách</button>
    </div>
  </div>
  <div id="hienThongBao" class="toast">Đã cập nhật thành công</div>
  <script src="assets/app.js"></script>
</body>

</html>
