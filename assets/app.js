const $$ = (s) => document.querySelectorAll(s),
    $ = s => document.querySelector(s);

function hienThongBao(message) {
    const t = $('#hienThongBao');
    t.textContent = message;
    t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 2600)
}

function hienThiTrang(id) {
    $$('.page').forEach(p => p.classList.toggle('active', p.id === id));
    $$('.nav-link').forEach(a => a.classList.toggle('active', a.dataset.page === id));
    $('#page-title').textContent = $(`[data-page="${id}"]`)?.textContent.trim() || 'Tổng quan';
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    })
}
$$('[data-page]').forEach(a => a.addEventListener('click', e => {
    e.preventDefault();
    hienThiTrang(a.dataset.page)
}));
$$('[data-go]').forEach(a => a.addEventListener('click', e => {
    e.preventDefault();
    hienThiTrang(a.dataset.go)
}));
const oTimKiemSach = $('#book-search');
if (oTimKiemSach) {
    oTimKiemSach.addEventListener('input', e => {
        const q = e.target.value.toLocaleLowerCase('vi').trim();
        let n = 0;
        $$('.book-card').forEach(card => {
            const dangHienThi = card.dataset.search.toLocaleLowerCase('vi').includes(q);
            card.style.display = dangHienThi ? '' : 'none';
            if (dangHienThi) n++
        });
        $('#book-count').textContent = `${n} đầu sách`
    });
    $$('.chip').forEach(c => c.addEventListener('click', () => {
        $$('.chip').forEach(x => x.classList.remove('active'));
        c.classList.add('active');
        oTimKiemSach.value = c.dataset.filter || '';
        oTimKiemSach.dispatchEvent(new Event('input'))
    }));
}
$$('.book-card').forEach(card => {
    const moChiTiet = () => window.location.href = card.dataset.detailUrl;
    card.addEventListener('click', event => {
        if (!event.target.closest('a,button')) moChiTiet()
    });
    card.addEventListener('keydown', event => {
        if (event.key === 'Enter' || event.key === ' ') {
            event.preventDefault();
            moChiTiet()
        }
    })
});
const hopThoai = $('#hopThoai');
$$('button.detail-btn').forEach(b => b.addEventListener('click', () => {
    $('#hopThoai-title').textContent = b.dataset.title;
    hopThoai.classList.add('open')
}));
$$('.close').forEach(b => b.addEventListener('click', () => hopThoai.classList.remove('open')));
hopThoai.addEventListener('click', e => {
    if (e.target === hopThoai) hopThoai.classList.remove('open')
});
$$('.renew-btn').forEach(b => b.addEventListener('click', () => hienThongBao('Yêu cầu gia hạn đã được gửi thành công.')));
$$('.borrow-btn').forEach(b => b.addEventListener('click', () => {
    hopThoai.classList.remove('open');
    hienThongBao('Đăng ký mượn sách đã được gửi đến thủ thư.')
}));
$$('.save-btn').forEach(b => b.addEventListener('click', () => hienThongBao('Thông tin cá nhân đã được lưu.')));
const trangBatDau = {
    reader: 'dashboard',
    librarian: 'staff-dashboard',
    admin: 'admin-dashboard'
};
hienThiTrang(trangBatDau[document.body.dataset.role] || 'dashboard');
const oTimKiemKho = $('#inventory-search');
if (oTimKiemKho) {
    oTimKiemKho.addEventListener('input', event => {
        const tuKhoa = event.target.value.toLocaleLowerCase('vi').trim();
        $$('[data-book-row]').forEach(row => row.style.display = row.dataset.bookRow.includes(tuKhoa) ? '' : 'none');
    });
    $$('.book-action').forEach(button => button.addEventListener('click', () => hienThongBao(button.dataset.message || 'Đã cập nhật dữ liệu.')));
}
$$('.librarian-action').forEach(button => button.addEventListener('click', () => hienThongBao(button.dataset.message || 'Đã mở tác vụ nghiệp vụ.')));
$$('.duyet-yeu-cau, .tu-choi-yeu-cau').forEach(nut => nut.addEventListener('click', () => {
    const dongYeuCau = nut.closest('[data-yeu-cau-cho]');
    dongYeuCau?.remove();
    const huyHieuCho = $('.pending-count');
    if (huyHieuCho) huyHieuCho.textContent = $$('.pending-table tbody tr').length.toString().padStart(2, '0');
    hienThongBao(nut.dataset.message || 'Đã xử lý yêu cầu mượn.');
}));
const hopChonTaiLieu = $('#hop-chon-tai-lieu');
if (hopChonTaiLieu) {
    const oTimTaiLieuTrongHop = $('#tim-tai-lieu-trong-hop');
    const tieuDeChonTaiLieu = $('#tieu-de-chon-tai-lieu');
    const soLuongDaChon = $('#so-luong-da-chon');
    const nutXacNhanChon = $('.xac-nhan-chon-tai-lieu');
    let loaiThaoTac = 'muon';
    const capNhatSoLuongChon = () => {
        const soLuong = $$('.danh-sach-chon-tai-lieu input:checked').length;
        soLuongDaChon.textContent = `${String(soLuong).padStart(2, '0')} tài liệu đã chọn`;
    };
    $$('.mo-chon-tai-lieu').forEach(nut => nut.addEventListener('click', () => {
        loaiThaoTac = nut.dataset.loai;
        const laTraSach = loaiThaoTac === 'tra';
        tieuDeChonTaiLieu.textContent = laTraSach ? 'Chọn tài liệu cần trả' : 'Chọn tài liệu cho mượn';
        nutXacNhanChon.innerHTML = `${laTraSach ? 'Tiếp nhận trả sách' : 'Thêm vào phiếu'} <span>→</span>`;
        hopChonTaiLieu.classList.add('mo');
        hopChonTaiLieu.setAttribute('aria-hidden', 'false');
        oTimTaiLieuTrongHop.focus();
        capNhatSoLuongChon();
    }));
    $$('.dong-chon-tai-lieu').forEach(nut => nut.addEventListener('click', () => {
        hopChonTaiLieu.classList.remove('mo');
        hopChonTaiLieu.setAttribute('aria-hidden', 'true');
    }));
    hopChonTaiLieu.addEventListener('click', suKien => {
        if (suKien.target === hopChonTaiLieu) $('.dong-chon-tai-lieu').click();
    });
    oTimTaiLieuTrongHop.addEventListener('input', suKien => {
        const tuKhoa = suKien.target.value.toLocaleLowerCase('vi').trim();
        $$('[data-muc-chon-tai-lieu]').forEach(muc => muc.style.display = muc.dataset.mucChonTaiLieu.includes(tuKhoa) ? '' : 'none');
    });
    $$('.danh-sach-chon-tai-lieu input').forEach(oChon => oChon.addEventListener('change', capNhatSoLuongChon));
    nutXacNhanChon.addEventListener('click', () => {
        const soLuong = $$('.danh-sach-chon-tai-lieu input:checked').length;
        $('.dong-chon-tai-lieu').click();
        hienThongBao(loaiThaoTac === 'tra' ? `Đã chọn ${soLuong} tài liệu để tiếp nhận trả.` : `Đã thêm ${soLuong} tài liệu vào phiếu mượn tạm.`);
    });
}

