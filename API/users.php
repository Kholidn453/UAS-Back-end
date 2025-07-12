<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents("php://input"), true);

// Koneksi database
$conn = new mysqli("localhost", "root", "", "projek_fix");

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["message" => "Koneksi database gagal"]);
    exit();
}

// GET - ambil semua user atau satu user berdasarkan ID
if ($method == "GET") {
    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);
        $result = $conn->query("SELECT * FROM user WHERE id = $id");
        echo json_encode($result->fetch_assoc());
    } else {
        $result = $conn->query("SELECT * FROM user");
        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        echo json_encode($users);
    }
}

// POST - tambah user baru
elseif ($method == "POST") {
    $email = $conn->real_escape_string($input['email']);
    $password = password_hash($input['password'], PASSWORD_DEFAULT);
    $name = $conn->real_escape_string($input['name']);
    $alamat = $conn->real_escape_string($input['alamat']);
    $no_telepon = $conn->real_escape_string($input['no_telepon']);
    $jenis_kelamin = $conn->real_escape_string($input['jenis_kelamin']);
    $tanggal_lahir = $conn->real_escape_string($input['tanggal_lahir']);

    $query = "INSERT INTO user (email, password, name, alamat, no_telepon, jenis_kelamin, tanggal_lahir) 
              VALUES ('$email', '$password', '$name', '$alamat', '$no_telepon', '$jenis_kelamin', '$tanggal_lahir')";

    if ($conn->query($query)) {
        echo json_encode(["message" => "User berhasil ditambahkan"]);
    } else {
        http_response_code(400);
        echo json_encode(["message" => "Gagal menambahkan user"]);
    }
}

// PUT - update user
elseif ($method == "PUT" && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $email = $conn->real_escape_string($input['email']);
    $name = $conn->real_escape_string($input['name']);
    $alamat = $conn->real_escape_string($input['alamat']);
    $no_telepon = $conn->real_escape_string($input['no_telepon']);
    $jenis_kelamin = $conn->real_escape_string($input['jenis_kelamin']);
    $tanggal_lahir = $conn->real_escape_string($input['tanggal_lahir']);

    $query = "UPDATE user SET email='$email', name='$name', alamat='$alamat', no_telepon='$no_telepon',
              jenis_kelamin='$jenis_kelamin', tanggal_lahir='$tanggal_lahir' WHERE id=$id";

    if ($conn->query($query)) {
        echo json_encode(["message" => "User berhasil diupdate"]);
    } else {
        http_response_code(400);
        echo json_encode(["message" => "Gagal mengupdate user"]);
    }
}

// DELETE - hapus user
elseif ($method == "DELETE" && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    if ($conn->query("DELETE FROM user WHERE id=$id")) {
        echo json_encode(["message" => "User berhasil dihapus"]);
    } else {
        http_response_code(400);
        echo json_encode(["message" => "Gagal menghapus user"]);
    }
}

$conn->close();
?>
