<?php
$maTrang = $maTrang ?? '';
if ($maTrang === 'admin-dashboard'):
?>
<div class="quan-tri-chi-so">
  <article><i class="xanh">♙</i><div><b>1.286</b><span>Tài khoản hệ thống</span></div><small>+18 trong tháng này</small></article>
  <article><i class="tim">◉</i><div><b>1.248</b><span>Đầu tài liệu</span></div><small>34 danh mục</small></article>
  <article><i class="cam">◷</i><div><b>42</b><span>Phiên truy cập hôm nay</span></div><small>08 đang hoạt động</small></article>
  <article><i class="do">!</i><div><b>03</b><span>Cần quản trị xử lý</span></div><small>Kiểm tra quyền truy cập</small></article>
</div>
<div class="quan-tri-luoi">
  <section class="panel"><div class="section-head"><div><p class="eyebrow">HOẠT ĐỘNG HỆ THỐNG</p><h2>Nhật ký gần đây</h2></div><button class="text-btn quan-tri-thao-tac" data-message="Đã mở nhật ký hệ thống đầy đủ.">Xem nhật ký →</button></div><div class="nhat-ky-quan-tri"><div><i>✓</i><p><b>Phạm Khánh Linh</b> đã cập nhật danh mục Công nghệ thông tin.<small>10:24 · 21/09/2026</small></p></div><div><i>+</i><p><b>Quản trị viên</b> đã tạo tài khoản thủ thư mới.<small>09:48 · 21/09/2026</small></p></div><div><i>◈</i><p><b>Chính sách mượn</b> được cập nhật cho nhóm sinh viên.<small>16:10 · 20/09/2026</small></p></div></div></section>
  <section class="panel canh-bao-quan-tri"><p class="eyebrow">CẦN LƯU Ý</p><h2>Kiểm tra trước khi kết thúc ngày</h2><div><span>02</span><p>Tài khoản thủ thư chưa được cấp quyền nghiệp vụ.</p></div><div><span>01</span><p>Chính sách mượn hết hiệu lực vào cuối tháng.</p></div><button class="outline quan-tri-thao-tac" data-message="Đã mở danh sách công việc quản trị.">Xem việc cần làm</button></section>
</div>
<?php elseif ($maTrang === 'accounts'): ?>
<section class="panel quan-tri-bang">
  <div class="thanh-cong-cu-quan-tri"><label><span>⌕</span><input id="tim-tai-khoan" placeholder="Tìm theo mã, họ tên hoặc email..."></label><select><option>Tất cả vai trò</option><option>Quản trị viên</option><option>Thủ thư</option><option>Độc giả</option></select><button class="primary quan-tri-thao-tac" data-message="Đã mở biểu mẫu tạo tài khoản mới.">+ Tạo tài khoản</button></div>
  <div class="tieu-de-bang-quan-tri"><b>Danh sách tài khoản</b><span>Hiển thị 5 trên 1.286 tài khoản</span></div>
  <div class="table-panel"><table><thead><tr><th>TÀI KHOẢN</th><th>VAI TRÒ</th><th>LIÊN HỆ</th><th>TRẠNG THÁI</th><th>TRUY CẬP GẦN NHẤT</th><th></th></tr></thead><tbody>
    <tr data-tai-khoan="qt-001 nguyen phu trong trong@example.com"><td><b>Nguyễn Phú Trọng</b><span>QT-001</span></td><td><mark class="vai-tro quan-tri">Quản trị viên</mark></td><td>trong@example.com</td><td><mark class="green-mark">Hoạt động</mark></td><td>21/09/2026 · 10:30</td><td><button class="nut-bang-quan-tri quan-tri-thao-tac" data-message="Đã mở tài khoản QT-001.">Chỉnh sửa</button></td></tr>
    <tr data-tai-khoan="tt-014 pham khanh linh linh@libra.edu.vn"><td><b>Phạm Khánh Linh</b><span>TT-014</span></td><td><mark class="vai-tro thu-thu">Thủ thư</mark></td><td>linh@libra.edu.vn</td><td><mark class="green-mark">Hoạt động</mark></td><td>21/09/2026 · 10:24</td><td><button class="nut-bang-quan-tri quan-tri-thao-tac" data-message="Đã mở tài khoản TT-014.">Chỉnh sửa</button></td></tr>
    <tr data-tai-khoan="tt-018 hoang minh duc duc@libra.edu.vn"><td><b>Hoàng Minh Đức</b><span>TT-018</span></td><td><mark class="vai-tro thu-thu">Thủ thư</mark></td><td>duc@libra.edu.vn</td><td><mark class="yellow">Chờ cấp quyền</mark></td><td>20/09/2026 · 16:42</td><td><button class="nut-bang-quan-tri quan-tri-thao-tac" data-message="Đã mở tài khoản TT-018.">Chỉnh sửa</button></td></tr>
    <tr data-tai-khoan="22110432 nguyen minh anh minhanh@student.edu.vn"><td><b>Nguyễn Minh Anh</b><span>22110432</span></td><td><mark class="vai-tro doc-gia">Độc giả</mark></td><td>minhanh@student.edu.vn</td><td><mark class="green-mark">Hoạt động</mark></td><td>20/09/2026 · 20:18</td><td><button class="nut-bang-quan-tri quan-tri-thao-tac" data-message="Đã mở tài khoản 22110432.">Chỉnh sửa</button></td></tr>
    <tr data-tai-khoan="22110615 pham quoc bao quocbao@student.edu.vn"><td><b>Phạm Quốc Bảo</b><span>22110615</span></td><td><mark class="vai-tro doc-gia">Độc giả</mark></td><td>quocbao@student.edu.vn</td><td><mark class="red-mark">Tạm khóa</mark></td><td>18/09/2026 · 09:10</td><td><button class="nut-bang-quan-tri quan-tri-thao-tac" data-message="Đã mở tài khoản 22110615.">Chỉnh sửa</button></td></tr>
  </tbody></table></div>
</section>
<?php elseif ($maTrang === 'categories'): ?>
<div class="dau-trang-quan-tri"><div><p>Phân loại tài liệu để độc giả dễ tra cứu và thủ thư dễ quản lý kho.</p></div><button class="primary quan-tri-thao-tac" data-message="Đã mở biểu mẫu thêm danh mục.">+ Thêm danh mục</button></div>
<div class="luoi-danh-muc-quan-tri">
  <article><i class="xanh">⌘</i><div><b>Công nghệ thông tin</b><span>286 tài liệu · 08 danh mục con</span></div><button class="quan-tri-thao-tac" data-message="Đang chỉnh sửa danh mục Công nghệ thông tin.">⋮</button></article>
  <article><i class="cam">₫</i><div><b>Kinh tế</b><span>194 tài liệu · 06 danh mục con</span></div><button class="quan-tri-thao-tac" data-message="Đang chỉnh sửa danh mục Kinh tế.">⋮</button></article>
  <article><i class="tim">§</i><div><b>Luật</b><span>158 tài liệu · 05 danh mục con</span></div><button class="quan-tri-thao-tac" data-message="Đang chỉnh sửa danh mục Luật.">⋮</button></article>
  <article><i class="do">✚</i><div><b>Y dược</b><span>123 tài liệu · 04 danh mục con</span></div><button class="quan-tri-thao-tac" data-message="Đang chỉnh sửa danh mục Y dược.">⋮</button></article>
  <article><i class="xanh">A</i><div><b>Ngôn ngữ Anh</b><span>97 tài liệu · 03 danh mục con</span></div><button class="quan-tri-thao-tac" data-message="Đang chỉnh sửa danh mục Ngôn ngữ Anh.">⋮</button></article>
  <article><i class="tim">◈</i><div><b>Kỹ năng và phát triển</b><span>76 tài liệu · 02 danh mục con</span></div><button class="quan-tri-thao-tac" data-message="Đang chỉnh sửa danh mục Kỹ năng và phát triển.">⋮</button></article>
</div>
<?php elseif ($maTrang === 'policies'): ?>
<div class="chinh-sach-quan-tri">
  <section class="panel bieu-mau-chinh-sach"><div class="section-head"><div><p class="eyebrow">QUY ĐỊNH MƯỢN</p><h2>Chính sách dành cho độc giả</h2></div><mark class="green-mark">Đang áp dụng</mark></div><div class="luoi-truong-chinh-sach"><label>Số sách tối đa<input type="number" value="3"></label><label>Thời hạn mượn (ngày)<input type="number" value="14"></label><label>Số lần gia hạn<input type="number" value="1"></label><label>Thời gian gia hạn (ngày)<input type="number" value="7"></label><label>Mức phạt quá hạn / ngày<input value="5.000 đ"></label><label>Đặt lại mật khẩu mặc định<input value="Ngày sinh (ddmmyyyy)"></label></div><button class="primary quan-tri-thao-tac" data-message="Đã lưu chính sách mượn tài liệu.">Lưu chính sách</button></section>
  <section class="panel lich-su-chinh-sach"><p class="eyebrow">LỊCH SỬ CẬP NHẬT</p><h2>Các thay đổi gần đây</h2><div><i>◈</i><p><b>Cập nhật mức phạt quá hạn</b><span>20/09/2026 · Phạm Khánh Linh</span></p></div><div><i>◈</i><p><b>Điều chỉnh thời hạn mượn cho sinh viên</b><span>01/09/2026 · Nguyễn Phú Trọng</span></p></div><div><i>◈</i><p><b>Ban hành quy định gia hạn trực tuyến</b><span>15/08/2026 · Nguyễn Phú Trọng</span></p></div></section>
</div>
<?php endif; ?>
