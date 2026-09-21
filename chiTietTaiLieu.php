<?php
session_start();
if (empty($_SESSION['library_user'])) {
  header('Location: login.php');
  exit;
}
$danhSachTaiLieu = [
  'CNTT-101' => ['title' => 'Cơ Sở Dữ Liệu', 'author' => 'Abraham Silberschatz', 'category' => 'Công nghệ thông tin', 'available' => 8, 'color' => 'blue', 'icon' => '◫', 'year' => '2023', 'publisher' => 'NXB Giáo Dục', 'pages' => '780 trang', 'shelf' => 'Tầng 3 · Phòng CNTT · Kệ CNTT-03', 'description' => 'Giáo trình nền tảng về mô hình dữ liệu, SQL, thiết kế cơ sở dữ liệu và quản trị giao dịch.'],
  'CNTT-145' => ['title' => 'Nhập Môn Trí Tuệ Nhân Tạo', 'author' => 'Stuart Russell & Peter Norvig', 'category' => 'Công nghệ thông tin', 'available' => 5, 'color' => 'purple', 'icon' => '◉', 'year' => '2022', 'publisher' => 'Pearson Education', 'pages' => '1.152 trang', 'shelf' => 'Tầng 3 · Phòng CNTT · Kệ AI-01', 'description' => 'Tài liệu chuyên sâu về các phương pháp tìm kiếm, học máy và ứng dụng trí tuệ nhân tạo.'],
  'CNTT-128' => ['title' => 'Lập Trình Hướng Đối Tượng Với Java', 'author' => 'Herbert Schildt', 'category' => 'Công nghệ thông tin', 'available' => 3, 'color' => 'green', 'icon' => '⌘', 'year' => '2021', 'publisher' => 'McGraw Hill', 'pages' => '690 trang', 'shelf' => 'Tầng 3 · Phòng CNTT · Kệ LT-06', 'description' => 'Học liệu thực hành lập trình Java với tư duy hướng đối tượng và các bài tập ứng dụng.'],
  'KT-021' => ['title' => 'Nguyên Lý Kế Toán', 'author' => 'Weygandt, Kimmel & Kieso', 'category' => 'Kinh tế', 'available' => 6, 'color' => 'orange', 'icon' => '₫', 'year' => '2023', 'publisher' => 'Wiley', 'pages' => '744 trang', 'shelf' => 'Tầng 2 · Phòng Kinh tế · Kệ KT-02', 'description' => 'Nhập môn hệ thống kế toán, báo cáo tài chính và các nguyên tắc ghi nhận nghiệp vụ.'],
  'KT-044' => ['title' => 'Kinh Tế Vi Mô', 'author' => 'N. Gregory Mankiw', 'category' => 'Kinh tế', 'available' => 2, 'color' => 'green', 'icon' => '↗', 'year' => '2022', 'publisher' => 'Cengage Learning', 'pages' => '560 trang', 'shelf' => 'Tầng 2 · Phòng Kinh tế · Kệ KT-07', 'description' => 'Giải thích các quyết định của hộ gia đình, doanh nghiệp và hoạt động của thị trường.'],
  'LUAT-032' => ['title' => 'Giáo Trình Luật Dân Sự Việt Nam', 'author' => 'Đại học Luật Hà Nội', 'category' => 'Luật', 'available' => 4, 'color' => 'purple', 'icon' => '§', 'year' => '2024', 'publisher' => 'NXB Công An Nhân Dân', 'pages' => '486 trang', 'shelf' => 'Tầng 2 · Phòng Luật · Kệ LUAT-05', 'description' => 'Giáo trình về chủ thể, tài sản, nghĩa vụ và các quan hệ dân sự theo pháp luật Việt Nam.'],
  'LUAT-008' => ['title' => 'Pháp Luật Đại Cương', 'author' => 'Nguyễn Văn Động', 'category' => 'Luật', 'available' => 0, 'color' => 'blue', 'icon' => '⚖', 'year' => '2021', 'publisher' => 'NXB Đại Học Quốc Gia', 'pages' => '354 trang', 'shelf' => 'Tầng 2 · Phòng Luật · Kệ LUAT-01', 'description' => 'Học liệu cơ sở về nhà nước, pháp luật và hệ thống pháp luật Việt Nam.'],
  'YD-015' => ['title' => 'Giải Phẫu Người', 'author' => 'Nguyễn Quang Quyền', 'category' => 'Y dược', 'available' => 3, 'color' => 'orange', 'icon' => '✚', 'year' => '2022', 'publisher' => 'NXB Y Học', 'pages' => '498 trang', 'shelf' => 'Tầng 4 · Phòng Y dược · Kệ YD-02', 'description' => 'Tài liệu giải phẫu hệ cơ quan, phục vụ đào tạo sinh viên khối ngành sức khỏe.'],
  'YD-027' => ['title' => 'Sinh Lý Học Y Khoa', 'author' => 'Guyton & Hall', 'category' => 'Y dược', 'available' => 1, 'color' => 'green', 'icon' => '✥', 'year' => '2023', 'publisher' => 'Elsevier', 'pages' => '1.184 trang', 'shelf' => 'Tầng 4 · Phòng Y dược · Kệ YD-06', 'description' => 'Giáo trình sinh lý học hiện đại, trình bày cơ chế hoạt động của các hệ cơ quan.'],
  'NNA-019' => ['title' => 'English for Academic Purposes', 'author' => 'Edward de Chazal', 'category' => 'Ngôn ngữ Anh', 'available' => 7, 'color' => 'blue', 'icon' => 'A', 'year' => '2022', 'publisher' => 'Oxford University Press', 'pages' => '176 trang', 'shelf' => 'Tầng 1 · Phòng Ngoại ngữ · Kệ NNA-04', 'description' => 'Rèn luyện tiếng Anh học thuật qua kỹ năng đọc, nghe, ghi chú và thuyết trình.'],
  'NNA-033' => ['title' => 'Academic Writing for Students', 'author' => 'Stephen Bailey', 'category' => 'Ngôn ngữ Anh', 'available' => 2, 'color' => 'purple', 'icon' => '✎', 'year' => '2021', 'publisher' => 'Routledge', 'pages' => '312 trang', 'shelf' => 'Tầng 1 · Phòng Ngoại ngữ · Kệ NNA-08', 'description' => 'Hướng dẫn viết luận học thuật, trích dẫn nguồn và xây dựng lập luận bằng tiếng Anh.'],
];
$maTaiLieu = $_GET['id'] ?? 'CNTT-101';
$taiLieu = $danhSachTaiLieu[$maTaiLieu] ?? $danhSachTaiLieu['CNTT-101'];
?>
<!doctype html>
<html lang="vi">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($taiLieu['title']) ?> | LIBRA</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/style.css">
  <link rel="stylesheet" href="assets/chiTietTaiLieu.css">
</head>

<body>
  <header class="detail-header"><a class="brand" href="index.php"><span class="brand-mark">L</span><span>LIBRA<small>THƯ VIỆN SỐ</small></span></a><a class="back-link" href="index.php">← Quay lại thư viện</a></header>
  <main class="detail-main">
    <p class="breadcrumb">Trang chủ / Tìm kiếm sách / <?= htmlspecialchars($taiLieu['category']) ?></p>
    <section class="book-hero">
      <div class="cover feature <?= $taiLieu['color'] ?>"><?= $taiLieu['icon'] ?><span><?= htmlspecialchars($maTaiLieu) ?></span></div>
      <div class="book-overview">
        <p class="eyebrow"><?= htmlspecialchars($taiLieu['category']) ?></p>
        <h1><?= htmlspecialchars($taiLieu['title']) ?></h1>
        <h2><?= htmlspecialchars($taiLieu['author']) ?></h2>
        <div class="availability <?= $taiLieu['available'] ? 'available-box' : 'unavailable-box' ?>">● <?= $taiLieu['available'] ? 'Hiện còn ' . $taiLieu['available'] . ' bản có thể mượn' : 'Tạm hết bản có thể mượn' ?></div>
        <p class="description"><?= htmlspecialchars($taiLieu['description']) ?></p>
        <div class="book-actions"><button class="primary action-btn" data-message="Yêu cầu mượn sách đã được gửi đến thủ thư.">Đăng ký mượn <span>→</span></button><button class="outline action-btn" data-message="Đã đặt chỗ. Chúng tôi sẽ thông báo khi sách sẵn sàng.">Đặt chỗ</button><button class="favorite action-btn" data-message="Đã lưu sách vào danh sách yêu thích.">♡ Lưu yêu thích</button></div>
      </div>
    </section>
    <section class="detail-grid">
      <article class="detail-panel">
        <h2>Thông tin thư mục</h2>
        <dl>
          <div>
            <dt>Mã tài liệu</dt>
            <dd><?= htmlspecialchars($maTaiLieu) ?></dd>
          </div>
          <div>
            <dt>Nhà xuất bản</dt>
            <dd><?= htmlspecialchars($taiLieu['publisher']) ?></dd>
          </div>
          <div>
            <dt>Năm xuất bản</dt>
            <dd><?= htmlspecialchars($taiLieu['year']) ?></dd>
          </div>
          <div>
            <dt>Số trang</dt>
            <dd><?= htmlspecialchars($taiLieu['pages']) ?></dd>
          </div>
        </dl>
      </article>
      <article class="detail-panel">
        <h2>Vị trí tại thư viện</h2>
        <p class="shelf">⌖ <?= htmlspecialchars($taiLieu['shelf']) ?></p>
        <p>Vui lòng xuất trình thẻ thư viện tại quầy để nhận sách. Thời hạn mượn mặc định là 14 ngày và có thể gia hạn trực tuyến.</p><button class="text-btn action-btn" data-message="Thông tin vị trí sách đã được sao chép.">Sao chép vị trí →</button>
      </article>
    </section>
  </main>
  <div id="toast" class="toast">Đã cập nhật</div>
  <script>
    document.querySelectorAll('.action-btn').forEach(b => b.onclick = () => {
      const t = document.querySelector('#toast');
      t.textContent = b.dataset.message;
      t.classList.add('show');
      setTimeout(() => t.classList.remove('show'), 2800)
    })
  </script>
</body>

</html>