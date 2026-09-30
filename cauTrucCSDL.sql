CREATE DATABASE IF NOT EXISTS thuVienLibra;
USE thuVienLibra;

CREATE TABLE vaiTro (
  maVaiTro INT AUTO_INCREMENT PRIMARY KEY,
  ma VARCHAR(30) UNIQUE,
  ten VARCHAR(100)
);

CREATE TABLE nguoiDung (
  maNguoiDung INT AUTO_INCREMENT PRIMARY KEY,
  maVaiTro INT,
  tenDangNhap VARCHAR(50) UNIQUE,
  matKhau VARCHAR(255),
  hoTen VARCHAR(150),
  thuDienTu VARCHAR(150),
  soDienThoai VARCHAR(20),
  ngaySinh DATE,
  trangThai VARCHAR(30),
  ngayTao DATETIME,
  FOREIGN KEY (maVaiTro) REFERENCES vaiTro(maVaiTro)
);

CREATE TABLE khoa (
  maKhoa INT AUTO_INCREMENT PRIMARY KEY,
  ma VARCHAR(30) UNIQUE,
  ten VARCHAR(150)
);

CREATE TABLE hoSoDocGia (
  maDocGia INT AUTO_INCREMENT PRIMARY KEY,
  maNguoiDung INT UNIQUE,
  maSo VARCHAR(30) UNIQUE,
  maKhoa INT,
  loaiDocGia VARCHAR(50),
  trangThaiThe VARCHAR(30),
  FOREIGN KEY (maNguoiDung) REFERENCES nguoiDung(maNguoiDung),
  FOREIGN KEY (maKhoa) REFERENCES khoa(maKhoa)
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
  ma VARCHAR(30) UNIQUE,
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
  ma VARCHAR(30) UNIQUE,
  maDanhMuc INT,
  maNhaXuatBan INT,
  tieuDe VARCHAR(500),
  maIsbn VARCHAR(30) UNIQUE,
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

CREATE TABLE banSaoTaiLieu (
  maBanSao INT AUTO_INCREMENT PRIMARY KEY,
  maTaiLieu INT,
  viTri VARCHAR(150),
  ngayNhap DATE,
  giaNhap DECIMAL(12,2),
  tinhTrang VARCHAR(30),
  trangThai VARCHAR(30),
  FOREIGN KEY (maTaiLieu) REFERENCES taiLieu(maTaiLieu)
);

CREATE TABLE chinhSachMuon (
  maChinhSach INT AUTO_INCREMENT PRIMARY KEY,
  loaiDocGia VARCHAR(50),
  soSachToiDa INT,
  soNgayMuon INT,
  soLanGiaHan INT,
  tienPhatMoiNgay DECIMAL(12,2),
  ngayApDung DATE,
  trangThai VARCHAR(30)
);

CREATE TABLE yeuCauMuon (
  maYeuCauMuon INT AUTO_INCREMENT PRIMARY KEY,
  maDocGia INT,
  maNguoiXuLy INT,
  ngayYeuCau DATETIME,
  ngayXuLy DATETIME,
  trangThai VARCHAR(30),
  lyDoTuChoi TEXT,
  FOREIGN KEY (maDocGia) REFERENCES hoSoDocGia(maDocGia),
  FOREIGN KEY (maNguoiXuLy) REFERENCES nguoiDung(maNguoiDung)
);

CREATE TABLE chiTietYeuCauMuon (
  maChiTietYeuCauMuon INT AUTO_INCREMENT PRIMARY KEY,
  maYeuCauMuon INT,
  maTaiLieu INT,
  trangThai VARCHAR(30),
  FOREIGN KEY (maYeuCauMuon) REFERENCES yeuCauMuon(maYeuCauMuon),
  FOREIGN KEY (maTaiLieu) REFERENCES taiLieu(maTaiLieu)
);

CREATE TABLE phieuMuon (
  maPhieuMuon INT AUTO_INCREMENT PRIMARY KEY,
  maDocGia INT,
  maThuThu INT,
  maChinhSach INT,
  maYeuCauMuon INT,
  ngayMuon DATETIME,
  trangThai VARCHAR(30),
  FOREIGN KEY (maDocGia) REFERENCES hoSoDocGia(maDocGia),
  FOREIGN KEY (maThuThu) REFERENCES nguoiDung(maNguoiDung),
  FOREIGN KEY (maChinhSach) REFERENCES chinhSachMuon(maChinhSach),
  FOREIGN KEY (maYeuCauMuon) REFERENCES yeuCauMuon(maYeuCauMuon)
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

CREATE TABLE phieuPhat (
  maPhieuPhat INT AUTO_INCREMENT PRIMARY KEY,
  maDocGia INT,
  maChiTietPhieuMuon INT,
  loaiPhat VARCHAR(30),
  soTien DECIMAL(12,2),
  trangThai VARCHAR(30),
  ngayLap DATETIME,
  maNguoiThu INT,
  ngayThanhToan DATETIME,
  FOREIGN KEY (maDocGia) REFERENCES hoSoDocGia(maDocGia),
  FOREIGN KEY (maChiTietPhieuMuon) REFERENCES chiTietPhieuMuon(maChiTietPhieuMuon),
  FOREIGN KEY (maNguoiThu) REFERENCES nguoiDung(maNguoiDung)
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
