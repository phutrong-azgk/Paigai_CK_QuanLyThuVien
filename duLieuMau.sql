USE thuVienLibra;

INSERT INTO vaiTro (ma, ten) VALUES
('ADMIN', 'Quản trị viên'),
('THUTHU', 'Thủ thư'),
('DOCGIA', 'Độc giả');

INSERT INTO nguoiDung (maVaiTro, tenDangNhap, hoTen, thuDienTu, soDienThoai, ngaySinh, trangThai, ngayTao) VALUES
(1, 'admin', 'Nguyễn Phú Trọng', 'trong@example.com', '0901000001', '1990-01-15', 'hoatDong', '2026-09-01 08:00:00'),
(2, 'thuthu01', 'Phạm Khánh Linh', 'linh@libra.edu.vn', '0901000002', '1994-06-12', 'hoatDong', '2026-09-01 08:05:00'),
(2, 'thuthu02', 'Hoàng Minh Đức', 'duc@libra.edu.vn', '0901000003', '1992-11-20', 'choCapQuyen', '2026-09-15 09:00:00'),
(3, '22110432', 'Nguyễn Minh Anh', 'minhanh@student.edu.vn', '0901234567', '2003-05-12', 'hoatDong', '2026-09-02 08:00:00'),
(3, '22110820', 'Lê Thị Hương', 'huong@student.edu.vn', '0902345678', '2003-08-20', 'hoatDong', '2026-09-02 08:10:00'),
(3, '22110615', 'Phạm Quốc Bảo', 'quocbao@student.edu.vn', '0903456789', '2003-06-15', 'tamKhoa', '2026-09-02 08:20:00');

INSERT INTO khoa (ma, ten) VALUES
('CNTT', 'Công nghệ thông tin'),
('KT', 'Kinh tế'),
('LUAT', 'Luật'),
('NN', 'Ngoại ngữ');

INSERT INTO hoSoDocGia (maNguoiDung, maSo, maKhoa, loaiDocGia, trangThaiThe) VALUES
(4, '22110432', 1, 'sinhVien', 'hoatDong'),
(5, '22110820', 2, 'sinhVien', 'hoatDong'),
(6, '22110615', 3, 'sinhVien', 'tamKhoa');

INSERT INTO danhMucTaiLieu (maDanhMucCha, ma, ten) VALUES
(NULL, 'CNTT', 'Công nghệ thông tin'),
(NULL, 'KT', 'Kinh tế'),
(NULL, 'LUAT', 'Luật'),
(NULL, 'YD', 'Y dược'),
(NULL, 'NNA', 'Ngôn ngữ Anh'),
(1, 'AI', 'Trí tuệ nhân tạo'),
(1, 'CSDL', 'Cơ sở dữ liệu');

INSERT INTO nhaXuatBan (ma, ten, diaChi, soDienThoai) VALUES
('NXBGD', 'Nhà xuất bản Giáo dục Việt Nam', 'Hà Nội', '02438220801'),
('NXBLD', 'Nhà xuất bản Lao động', 'Hà Nội', '02438515380'),
('PEARSON', 'Pearson Education', 'London', '442070100000');

INSERT INTO tacGia (hoTen) VALUES
('Abraham Silberschatz'),
('Stuart Russell'),
('Peter Norvig'),
('Herbert Schildt'),
('N. Gregory Mankiw'),
('Đại học Luật Hà Nội'),
('Guyton và Hall'),
('Edward de Chazal');

INSERT INTO taiLieu (ma, maDanhMuc, maNhaXuatBan, tieuDe, maIsbn, loaiTaiLieu, namXuatBan, soTrang, ngonNgu, anhBia, tomTat, duocMuon) VALUES
('CNTT-101', 7, 3, 'Cơ Sở Dữ Liệu', '9780073523323', 'giaoTrinh', 2019, 1376, 'Tiếng Anh', 'images/coSoDuLieu.jpg', 'Giáo trình nền tảng về hệ quản trị cơ sở dữ liệu.', 'co'),
('CNTT-145', 6, 3, 'Nhập Môn Trí Tuệ Nhân Tạo', '9780136042594', 'giaoTrinh', 2021, 1152, 'Tiếng Anh', 'images/triTueNhanTao.jpg', 'Giáo trình về trí tuệ nhân tạo hiện đại.', 'co'),
('CNTT-128', 1, 3, 'Lập Trình Hướng Đối Tượng Với Java', '9781260440232', 'giaoTrinh', 2021, 1250, 'Tiếng Việt', 'images/java.jpg', 'Tài liệu học lập trình Java hướng đối tượng.', 'co'),
('KT-021', 2, 1, 'Nguyên Lý Kế Toán', '9786040301234', 'giaoTrinh', 2022, 680, 'Tiếng Việt', 'images/nguyenLyKeToan.jpg', 'Kiến thức nền tảng về kế toán.', 'co'),
('KT-044', 2, 3, 'Kinh Tế Vi Mô', '9781305585126', 'giaoTrinh', 2020, 832, 'Tiếng Việt', 'images/kinhTeViMo.jpg', 'Các nguyên lý kinh tế vi mô.', 'co'),
('LUAT-032', 3, 1, 'Giáo Trình Luật Dân Sự Việt Nam', '9786047269872', 'giaoTrinh', 2023, 720, 'Tiếng Việt', 'images/luatDanSu.jpg', 'Giáo trình luật dân sự.', 'co'),
('YD-027', 4, 1, 'Sinh Lý Học Y Khoa', '9780323597128', 'giaoTrinh', 2021, 1180, 'Tiếng Việt', 'images/sinhLyHoc.jpg', 'Tài liệu sinh lý học cơ bản.', 'co'),
('NNA-019', 5, 3, 'English for Academic Purposes', '9780194001780', 'giaoTrinh', 2020, 192, 'Tiếng Anh', 'images/englishAcademic.jpg', 'Tiếng Anh học thuật cho sinh viên.', 'co');

INSERT INTO taiLieuTacGia (maTaiLieu, maTacGia) VALUES
(1, 1), (2, 2), (2, 3), (3, 4), (4, 5), (5, 5), (6, 6), (7, 7), (8, 8);

INSERT INTO banSaoTaiLieu (maTaiLieu, viTri, ngayNhap, giaNhap, tinhTrang, trangThai) VALUES
(1, 'Tầng 3 · Kệ CNTT-03', '2026-08-01', 185000, 'nguyenVen', 'dangMuon'),
(1, 'Tầng 3 · Kệ CNTT-03', '2026-08-01', 185000, 'nguyenVen', 'sanSang'),
(2, 'Tầng 3 · Kệ AI-01', '2026-08-02', 220000, 'nguyenVen', 'sanSang'),
(3, 'Tầng 3 · Kệ CNTT-03', '2026-08-02', 160000, 'nguyenVen', 'sanSang'),
(4, 'Tầng 1 · Kệ KT-02', '2026-08-03', 145000, 'nguyenVen', 'sanSang'),
(5, 'Tầng 1 · Kệ KT-02', '2026-08-03', 150000, 'nguyenVen', 'dangMuon'),
(6, 'Tầng 2 · Kệ LUAT-05', '2026-08-04', 175000, 'nguyenVen', 'sanSang'),
(7, 'Tầng 4 · Kệ YD-06', '2026-08-04', 195000, 'huHongNhe', 'sanSang'),
(8, 'Tầng 3 · Kệ NNA-01', '2026-08-05', 120000, 'nguyenVen', 'sanSang');

INSERT INTO chinhSachMuon (loaiDocGia, soSachToiDa, soNgayMuon, soLanGiaHan, tienPhatMoiNgay, ngayApDung, trangThai) VALUES
('sinhVien', 3, 14, 1, 5000, '2026-09-01', 'dangApDung'),
('giangVien', 5, 30, 2, 5000, '2026-09-01', 'dangApDung'),
('sinhVien', 2, 10, 1, 3000, '2026-01-01', 'ngungApDung');

INSERT INTO yeuCauMuon (maDocGia, maNguoiXuLy, ngayYeuCau, ngayXuLy, trangThai, lyDoTuChoi) VALUES
(1, 2, '2026-09-18 08:42:00', '2026-09-18 09:00:00', 'daDuyet', NULL),
(2, NULL, '2026-09-21 09:15:00', NULL, 'choDuyet', NULL),
(3, 2, '2026-09-18 09:26:00', '2026-09-18 09:35:00', 'tuChoi', 'Thẻ độc giả đang tạm khóa.');

INSERT INTO chiTietYeuCauMuon (maYeuCauMuon, maTaiLieu, trangThai) VALUES
(1, 4, 'daDuyet'),
(2, 2, 'choDuyet'),
(2, 6, 'choDuyet'),
(3, 6, 'tuChoi');

INSERT INTO phieuMuon (maDocGia, maThuThu, maChinhSach, maYeuCauMuon, ngayMuon, trangThai) VALUES
(1, 2, 1, 1, '2026-09-18 09:00:00', 'dangMuon'),
(1, 2, 1, NULL, '2026-09-14 10:00:00', 'dangMuon'),
(2, 2, 1, NULL, '2026-08-12 08:30:00', 'daTra');

INSERT INTO chiTietPhieuMuon (maPhieuMuon, maBanSao, hanTra, ngayTra, trangThai) VALUES
(1, 1, '2026-10-02', NULL, 'dangMuon'),
(2, 6, '2026-09-28', NULL, 'dangMuon'),
(3, 5, '2026-08-26', '2026-08-25 14:00:00', 'daTra');

INSERT INTO yeuCauGiaHan (maChiTietPhieuMuon, ngayYeuCau, hanTraMoi, trangThai) VALUES
(1, '2026-09-20 08:00:00', '2026-10-09', 'choDuyet');

INSERT INTO phieuPhat (maDocGia, maChiTietPhieuMuon, loaiPhat, soTien, trangThai, ngayLap, maNguoiThu, ngayThanhToan) VALUES
(2, 3, 'quaHan', 10000, 'daThanhToan', '2026-08-26 15:00:00', 2, '2026-08-26 15:15:00'),
(3, NULL, 'theTamKhoa', 50000, 'chuaThanhToan', '2026-09-18 09:35:00', NULL, NULL);

INSERT INTO nhatKyHeThong (maNguoiDung, hanhDong, doiTuong, maDoiTuong, ngayTao) VALUES
(1, 'capNhatChinhSach', 'chinhSachMuon', 1, '2026-09-01 08:00:00'),
(2, 'duyetYeuCauMuon', 'yeuCauMuon', 1, '2026-09-18 09:00:00'),
(2, 'lapPhieuMuon', 'phieuMuon', 1, '2026-09-18 09:00:00'),
(4, 'guiYeuCauMuon', 'yeuCauMuon', 2, '2026-09-21 09:15:00');
