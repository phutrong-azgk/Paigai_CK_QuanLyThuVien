# Cau truc MySQL cho LIBRA

Tep [cauTrucCSDL.sql](cauTrucCSDL.sql) tao co so du lieu `thuVienLibra` gom 18 bang. Ten bang va ten cot dung tieng Viet khong dau theo lower camel case. Cac thuoc tinh dung kieu du lieu co ban: `INT`, `VARCHAR`, `DATE`, `DATETIME`, `DECIMAL` va `TEXT`.

## Nhom bang

- Tai khoan va phan quyen: `nguoiDung`, `vaiTro`.
- Don vi dao tao va doc gia: `khoa`, `hoSoDocGia`.
- Hoc lieu: `danhMucTaiLieu`, `nhaXuatBan`, `tacGia`, `taiLieu`, `taiLieuTacGia`, `banSaoTaiLieu`.
- Luu thong: `chinhSachMuon`, `yeuCauMuon`, `chiTietYeuCauMuon`, `phieuMuon`, `chiTietPhieuMuon`, `yeuCauGiaHan`, `phieuPhat`.
- Van hanh: `nhatKyHeThong`.

Mot `taiLieu` co nhieu `banSaoTaiLieu`; moi ban sao luu vi tri ke, tinh trang va trang thai luu thong. Truong `anhBia` luu duong dan anh bia cua tai lieu. Doc gia co the gui `yeuCauMuon` gom nhieu tai lieu qua `chiTietYeuCauMuon`; sau khi duyet, phieu muon luu lai yeu cau va chinh sach da ap dung. Phieu phat luu luon thong tin nguoi thu va ngay thanh toan.

## Cach tao co so du lieu

Mo MySQL Workbench, chon **File > Open SQL Script**, mo `cauTrucCSDL.sql` va chay toan bo script. Hoac dung dong lenh MySQL:

```sql
SOURCE cauTrucCSDL.sql;
SOURCE duLieuMau.sql;
```


