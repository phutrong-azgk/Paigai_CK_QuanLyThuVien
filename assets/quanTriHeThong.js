(() => {
  if (document.body.dataset.role !== 'admin') return;
  const anToan = giaTri => String(giaTri ?? '').replace(/[&<>'"]/g, kyTu => ({
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    "'": '&#39;',
    '"': '&quot;'
  })[kyTu]);
  async function goiApi(duongDan, tuyChon = {}) {
    const phanHoi = await fetch(duongDan, tuyChon);
    const duLieu = await phanHoi.json().catch(() => ({}));
    if (!phanHoi.ok) throw new Error(duLieu.loi || 'Không thể thực hiện thao tác.');
    return duLieu;
  }
  const thongBao = noiDung => window.hienThongBao?.(noiDung);

  const chiSoTaiKhoan = document.querySelector('#chi-so-tai-khoan');
  if (chiSoTaiKhoan) {
    const nhatKy = document.querySelector('#nhat-ky-quan-tri');
    goiApi('api/quanTriHeThong.php?loai=tongQuan').then(ketQua => {
      const duLieu = ketQua.duLieu;
      chiSoTaiKhoan.textContent = duLieu.taiKhoan;
      document.querySelector('#chi-so-tai-lieu').textContent = duLieu.taiLieu;
      document.querySelector('#chi-so-yeu-cau').textContent = duLieu.canXuLy;
      document.querySelector('#chi-so-can-xu-ly').textContent = duLieu.canXuLy;
      document.querySelector('#chi-so-danh-muc').textContent = `${duLieu.danhMuc} danh mục`;
      nhatKy.innerHTML = ketQua.nhatKy.length ? ketQua.nhatKy.map(muc => `<div><i>✓</i><p><b>${anToan(muc.hoTen || 'Hệ thống')}</b> đã ${anToan(muc.hanhDong)}.<small>${anToan(muc.ngayTao)}</small></p></div>`).join('') : '<p>Chưa có nhật ký hoạt động.</p>';
    }).catch(loi => {
      nhatKy.textContent = loi.message;
    });
  }

  const formDanhMuc = document.querySelector('#form-danh-muc');
  if (formDanhMuc) {
    const danhSach = document.querySelector('#danh-sach-danh-muc');
    const nutHuy = document.querySelector('#huy-sua-danh-muc');
    const tieuDe = document.querySelector('#tieu-de-form-danh-muc');
    async function taiDanhMuc() {
      try {
        const ketQua = await goiApi('api/quanTriHeThong.php?loai=danhMuc');
        danhSach.innerHTML = ketQua.duLieu.map((danhMuc, chiSo) => `<article><i class="${['xanh','cam','tim','do'][chiSo % 4]}">⌘</i><div><b>${anToan(danhMuc.ten)}</b><span>${anToan(danhMuc.ma)} · ${danhMuc.soTaiLieu} tài liệu</span></div><button class="sua-danh-muc" type="button" data-id="${danhMuc.maDanhMuc}" data-ma="${anToan(danhMuc.ma)}" data-ten="${anToan(danhMuc.ten)}">Sửa</button><button class="xoa-danh-muc" type="button" data-id="${danhMuc.maDanhMuc}" data-ten="${anToan(danhMuc.ten)}">Xóa</button></article>`).join('') || '<p>Chưa có danh mục.</p>';
      } catch (loi) {
        danhSach.innerHTML = `<p>${anToan(loi.message)}</p>`;
      }
    }
    formDanhMuc.addEventListener('submit', async suKien => {
      suKien.preventDefault();
      try {
        await goiApi('api/quanTriHeThong.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            hanhDong: 'luuDanhMuc',
            ...Object.fromEntries(new FormData(formDanhMuc))
          })
        });
        formDanhMuc.reset();
        tieuDe.textContent = 'Thêm danh mục';
        nutHuy.hidden = true;
        await taiDanhMuc();
        thongBao('Đã lưu danh mục.');
      } catch (loi) {
        alert(loi.message);
      }
    });
    nutHuy.addEventListener('click', () => {
      formDanhMuc.reset();
      tieuDe.textContent = 'Thêm danh mục';
      nutHuy.hidden = true;
    });
    danhSach.addEventListener('click', async suKien => {
      const nutSua = suKien.target.closest('.sua-danh-muc'),
        nutXoa = suKien.target.closest('.xoa-danh-muc');
      if (nutSua) {
        formDanhMuc.maDanhMuc.value = nutSua.dataset.id;
        formDanhMuc.ma.value = nutSua.dataset.ma;
        formDanhMuc.ten.value = nutSua.dataset.ten;
        tieuDe.textContent = 'Sửa danh mục';
        nutHuy.hidden = false;
        formDanhMuc.ma.focus();
      }
      if (nutXoa && confirm(`Xóa danh mục ${nutXoa.dataset.ten}?`)) {
        try {
          await goiApi('api/quanTriHeThong.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({
              hanhDong: 'xoaDanhMuc',
              maDanhMuc: nutXoa.dataset.id
            })
          });
          await taiDanhMuc();
          thongBao('Đã xóa danh mục.');
        } catch (loi) {
          alert(loi.message);
        }
      }
    });
    taiDanhMuc();
  }

  const formChinhSach = document.querySelector('#form-chinh-sach');
  if (formChinhSach) {
    const danhSach = document.querySelector('#danh-sach-chinh-sach');
    let chinhSach = [];

    function apDungChinhSach() {
      const hienTai = chinhSach.find(muc => muc.loaiDocGia === formChinhSach.loaiDocGia.value && muc.trangThai === 'dangApDung');
      if (!hienTai) return formChinhSach.reset();
      ['soSachToiDa', 'soNgayMuon', 'soLanGiaHan', 'tienPhatMoiNgay'].forEach(ten => formChinhSach[ten].value = hienTai[ten]);
    }
    async function taiChinhSach() {
      try {
        const ketQua = await goiApi('api/quanTriHeThong.php?loai=chinhSach');
        chinhSach = ketQua.duLieu;
        apDungChinhSach();
        danhSach.innerHTML = chinhSach.map(muc => `<div><i>◈</i><p><b>${muc.loaiDocGia === 'sinhVien' ? 'Sinh viên' : 'Giảng viên'} · ${muc.soSachToiDa} sách / ${muc.soNgayMuon} ngày</b><span>${muc.trangThai === 'dangApDung' ? 'Đang áp dụng' : 'Ngừng áp dụng'} · ${muc.ngayApDung}</span></p></div>`).join('');
      } catch (loi) {
        danhSach.textContent = loi.message;
      }
    }
    formChinhSach.loaiDocGia.addEventListener('change', apDungChinhSach);
    formChinhSach.addEventListener('submit', async suKien => {
      suKien.preventDefault();
      try {
        await goiApi('api/quanTriHeThong.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            hanhDong: 'luuChinhSach',
            ...Object.fromEntries(new FormData(formChinhSach))
          })
        });
        await taiChinhSach();
        thongBao('Đã lưu phiên bản chính sách mới.');
      } catch (loi) {
        alert(loi.message);
      }
    });
    taiChinhSach();
  }
})();