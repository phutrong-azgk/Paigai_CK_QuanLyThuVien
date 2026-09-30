<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

function traVeJson($duLieu, $maTrangThai = 200)
{
    http_response_code($maTrangThai);
    echo json_encode($duLieu, JSON_UNESCAPED_UNICODE);
    exit;
}

function duLieuGuiLen()
{
    $duLieu = json_decode(file_get_contents('php://input'), true);
    return is_array($duLieu) ? $duLieu : $_POST;
}

function ketNoiCSDL()
{
    static $ketNoi;
    if ($ketNoi) return $ketNoi;
    $mayChu = getenv('DB_HOST') ?: '127.0.0.1';
    $cong = getenv('DB_PORT') ?: '3306';
    $tenCSDL = getenv('DB_NAME') ?: 'thuVienLibra';
    $nguoiDung = getenv('DB_USER') ?: 'root';
    $matKhau = getenv('DB_PASS') ?: '';
    try {
        $ketNoi = new PDO("mysql:host=$mayChu;port=$cong;dbname=$tenCSDL;charset=utf8mb4", $nguoiDung, $matKhau, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        return $ketNoi;
    } catch (PDOException $loi) {
        traVeJson(['loi' => 'Không thể kết nối MySQL. Kiểm tra cấu hình DB_HOST, DB_NAME, DB_USER và DB_PASS.'], 500);
    }
}

function nguoiDungHienTai($vaiTro = null)
{
    if (empty($_SESSION['library_user']['id'])) traVeJson(['loi' => 'Bạn chưa đăng nhập.'], 401);
    if ($vaiTro && ($_SESSION['role'] ?? '') !== $vaiTro) traVeJson(['loi' => 'Bạn không có quyền thực hiện thao tác này.'], 403);
    return $_SESSION['library_user'];
}
