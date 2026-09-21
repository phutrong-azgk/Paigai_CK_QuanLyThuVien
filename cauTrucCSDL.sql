CREATE DATABASE IF NOT EXISTS thuVienLibra;
USE thuVienLibra;

CREATE TABLE vaiTro (
  maVaiTro INT AUTO_INCREMENT PRIMARY KEY,
  ma VARCHAR(30) UNIQUE,
  ten VARCHAR(100)
);

CREATE TABLE nguoiDung (
  maNguoiDung INT AUTO_INCREMENT PRIMARY KEY,
  tenDangNhap VARCHAR(50) UNIQUE,
  matKhau VARCHAR(255),
  hoTen VARCHAR(150),
  thuDienTu VARCHAR(150),
  soDienThoai VARCHAR(20),
  ngaySinh DATE,
  trangThai VARCHAR(30),
  ngayTao DATETIME
);

CREATE TABLE nguoiDungVaiTro (
  maNguoiDung INT,
  maVaiTro INT,
  PRIMARY KEY (maNguoiDung, maVaiTro),
  FOREIGN KEY (maNguoiDung) REFERENCES nguoiDung(maNguoiDung),
  FOREIGN KEY (maVaiTro) REFERENCES vaiTro(maVaiTro)
);

CREATE TABLE khoa (
  maKhoa INT AUTO_INCREMENT PRIMARY KEY,
  ma VARCHAR(30) UNIQUE,
  ten VARCHAR(150)
);

CREATE TABLE chuyenNganh (
  maChuyenNganh INT AUTO_INCREMENT PRIMARY KEY,
  maKhoa INT,
  ma VARCHAR(30) UNIQUE,
  ten VARCHAR(150),
  FOREIGN KEY (maKhoa) REFERENCES khoa(maKhoa)
);

CREATE TABLE hoSoDocGia (
  maDocGia INT AUTO_INCREMENT PRIMARY KEY,
  maNguoiDung INT UNIQUE,
  maSo VARCHAR(30) UNIQUE,
  maKhoa INT,
  maChuyenNganh INT,
  loaiDocGia VARCHAR(50),
  trangThaiThe VARCHAR(30),
  FOREIGN KEY (maNguoiDung) REFERENCES nguoiDung(maNguoiDung),
  FOREIGN KEY (maKhoa) REFERENCES khoa(maKhoa),
  FOREIGN KEY (maChuyenNganh) REFERENCES chuyenNganh(maChuyenNganh)
);

CREATE TABLE danhMucTaiLieu (
  maDanhMuc INT AUTO_INCREMENT PRIMARY KEY,
  maDanhMucCha INT,
  ma VARCHAR(30) UNIQUE,
  ten VARCHAR(150),
  FOREIGN KEY (maDanhMucCha) REFERENCES danhMucTaiLieu(maDanhMuc)
);

CREATE TABLE nhaXuatBan (
  maNhaXuatBan INT AUTO_INCREMENT PRIMARY KEY,
  ten VARCHAR(200),
  diaChi VARCHAR(255),
  soDienThoai VARCHAR(20)
);

CREATE TABLE tacGia (
  maTacGia INT AUTO_INCREMENT PRIMARY KEY,
  hoTen VARCHAR(200)
);

CREATE TABLE taiLieu (
  maTaiLieu INT AUTO_INCREMENT PRIMARY KEY,
  maDanhMuc INT,
  maNhaXuatBan INT,
  tieuDe VARCHAR(500),
  maIsbn VARCHAR(30),
  loaiTaiLieu VARCHAR(50),
  namXuatBan INT,
  soTrang INT,
  ngonNgu VARCHAR(50),
  anhBia VARCHAR(255),
  tomTat TEXT,
  duocMuon VARCHAR(10),
  FOREIGN KEY (maDanhMuc) REFERENCES danhMucTaiLieu(maDanhMuc),
  FOREIGN KEY (maNhaXuatBan) REFERENCES nhaXuatBan(maNhaXuatBan)
);

CREATE TABLE taiLieuTacGia (
  maTaiLieu INT,
  maTacGia INT,
  PRIMARY KEY (maTaiLieu, maTacGia),
  FOREIGN KEY (maTaiLieu) REFERENCES taiLieu(maTaiLieu),
  FOREIGN KEY (maTacGia) REFERENCES tacGia(maTacGia)
);

CREATE TABLE viTriLuuTru (
  maViTri INT AUTO_INCREMENT PRIMARY KEY,
  toaNha VARCHAR(100),
  tang VARCHAR(20),
  phong VARCHAR(30),
  ke VARCHAR(30)
);

CREATE TABLE banSaoTaiLieu (
  maBanSao INT AUTO_INCREMENT PRIMARY KEY,
  maTaiLieu INT,
  maViTri INT,
  maVach VARCHAR(50) UNIQUE,
  ngayNhap DATE,
  giaNhap DECIMAL(12,2),
  tinhTrang VARCHAR(30),
  FOREIGN KEY (maTaiLieu) REFERENCES taiLieu(maTaiLieu),
  FOREIGN KEY (maViTri) REFERENCES viTriLuuTru(maViTri)
);

CREATE TABLE nhaCungCap (
  maNhaCungCap INT AUTO_INCREMENT PRIMARY KEY,
  ten VARCHAR(200),
  soDienThoai VARCHAR(20),
  diaChi VARCHAR(255)
);

CREATE TABLE phieuNhap (
  maPhieuNhap INT AUTO_INCREMENT PRIMARY KEY,
  maNhaCungCap INT,
  maNguoiTao INT,
  ngayNhap DATETIME,
  tongTien DECIMAL(12,2),
  FOREIGN KEY (maNhaCungCap) REFERENCES nhaCungCap(maNhaCungCap),
  FOREIGN KEY (maNguoiTao) REFERENCES nguoiDung(maNguoiDung)
);

CREATE TABLE chiTietPhieuNhap (
  maChiTietPhieuNhap INT AUTO_INCREMENT PRIMARY KEY,
  maPhieuNhap INT,
  maTaiLieu INT,
  soLuong INT,
  donGia DECIMAL(12,2),
  FOREIGN KEY (maPhieuNhap) REFERENCES phieuNhap(maPhieuNhap),
  FOREIGN KEY (maTaiLieu) REFERENCES taiLieu(maTaiLieu)
);

CREATE TABLE chinhSachMuon (
  maChinhSach INT AUTO_INCREMENT PRIMARY KEY,
  loaiDocGia VARCHAR(50),
  soSachToiDa INT,
  soNgayMuon INT,
  soLanGiaHan INT,
  tienPhatMoiNgay DECIMAL(12,2)
);

CREATE TABLE phieuMuon (
  maPhieuMuon INT AUTO_INCREMENT PRIMARY KEY,
  maDocGia INT,
  maThuThu INT,
  ngayMuon DATETIME,
  trangThai VARCHAR(30),
  FOREIGN KEY (maDocGia) REFERENCES hoSoDocGia(maDocGia),
  FOREIGN KEY (maThuThu) REFERENCES nguoiDung(maNguoiDung)
);

CREATE TABLE chiTietPhieuMuon (
  maChiTietPhieuMuon INT AUTO_INCREMENT PRIMARY KEY,
  maPhieuMuon INT,
  maBanSao INT,
  hanTra DATE,
  ngayTra DATETIME,
  trangThai VARCHAR(30),
  FOREIGN KEY (maPhieuMuon) REFERENCES phieuMuon(maPhieuMuon),
  FOREIGN KEY (maBanSao) REFERENCES banSaoTaiLieu(maBanSao)
);

CREATE TABLE yeuCauGiaHan (
  maYeuCauGiaHan INT AUTO_INCREMENT PRIMARY KEY,
  maChiTietPhieuMuon INT,
  ngayYeuCau DATETIME,
  hanTraMoi DATE,
  trangThai VARCHAR(30),
  FOREIGN KEY (maChiTietPhieuMuon) REFERENCES chiTietPhieuMuon(maChiTietPhieuMuon)
);

CREATE TABLE yeuCauDatCho (
  maYeuCauDatCho INT AUTO_INCREMENT PRIMARY KEY,
  maDocGia INT,
  maTaiLieu INT,
  ngayDat DATETIME,
  trangThai VARCHAR(30),
  FOREIGN KEY (maDocGia) REFERENCES hoSoDocGia(maDocGia),
  FOREIGN KEY (maTaiLieu) REFERENCES taiLieu(maTaiLieu)
);

CREATE TABLE phieuPhat (
  maPhieuPhat INT AUTO_INCREMENT PRIMARY KEY,
  maDocGia INT,
  maChiTietPhieuMuon INT,
  loaiPhat VARCHAR(30),
  soTien DECIMAL(12,2),
  trangThai VARCHAR(30),
  ngayLap DATETIME,
  FOREIGN KEY (maDocGia) REFERENCES hoSoDocGia(maDocGia),
  FOREIGN KEY (maChiTietPhieuMuon) REFERENCES chiTietPhieuMuon(maChiTietPhieuMuon)
);

CREATE TABLE thanhToanPhat (
  maThanhToan INT AUTO_INCREMENT PRIMARY KEY,
  maPhieuPhat INT,
  maNguoiThu INT,
  soTien DECIMAL(12,2),
  ngayThanhToan DATETIME,
  FOREIGN KEY (maPhieuPhat) REFERENCES phieuPhat(maPhieuPhat),
  FOREIGN KEY (maNguoiThu) REFERENCES nguoiDung(maNguoiDung)
);

CREATE TABLE thongBao (
  maThongBao INT AUTO_INCREMENT PRIMARY KEY,
  maNguoiDung INT,
  tieuDe VARCHAR(200),
  noiDung TEXT,
  daDoc VARCHAR(10),
  ngayTao DATETIME,
  FOREIGN KEY (maNguoiDung) REFERENCES nguoiDung(maNguoiDung)
);

CREATE TABLE nhatKyHeThong (
  maNhatKy INT AUTO_INCREMENT PRIMARY KEY,
  maNguoiDung INT,
  hanhDong VARCHAR(100),
  doiTuong VARCHAR(100),
  maDoiTuong INT,
  ngayTao DATETIME,
  FOREIGN KEY (maNguoiDung) REFERENCES nguoiDung(maNguoiDung)
);
