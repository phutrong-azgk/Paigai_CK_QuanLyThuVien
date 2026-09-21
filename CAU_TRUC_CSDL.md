# Cau truc MySQL cho LIBRA

Tep [cauTrucCSDL.sql](cauTrucCSDL.sql) tao co so du lieu `thuVienLibra` gom 25 bang. Ten bang va ten cot dung tieng Viet khong dau theo lower camel case. Cac thuoc tinh dung kieu du lieu co ban: `INT`, `VARCHAR`, `DATE`, `DATETIME`, `DECIMAL` va `TEXT`.

## Nhom bang

- Tai khoan va phan quyen: `nguoiDung`, `vaiTro`, `nguoiDungVaiTro`.
- Don vi dao tao va doc gia: `khoa`, `chuyenNganh`, `hoSoDocGia`.
- Hoc lieu: `danhMucTaiLieu`, `nhaXuatBan`, `tacGia`, `taiLieu`, `taiLieuTacGia`, `viTriLuuTru`, `banSaoTaiLieu`.
- Nhap kho: `nhaCungCap`, `phieuNhap`, `chiTietPhieuNhap`.
- Luu thong: `chinhSachMuon`, `phieuMuon`, `chiTietPhieuMuon`, `yeuCauGiaHan`, `yeuCauDatCho`, `phieuPhat`, `thanhToanPhat`.
- Van hanh: `thongBao`, `nhatKyHeThong`.

Mot `taiLieu` co nhieu `banSaoTaiLieu`; moi `banSaoTaiLieu` co ma vach va vi tri ke rieng. Truong `anhBia` luu duong dan anh bia cua tai lieu. Doc gia muon ban sao qua `phieuMuon` va `chiTietPhieuMuon`; cac yeu cau gia han, dat cho, phat va thanh toan deu lien ket bang khoa ngoai.

## Cach tao co so du lieu

Mo MySQL Workbench, chon **File > Open SQL Script**, mo `cauTrucCSDL.sql` va chay toan bo script. Hoac dung dong lenh MySQL:

```sql
SOURCE cauTrucCSDL.sql;
```


