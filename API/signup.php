<?php
include 'connect.php';
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

$data = json_decode(file_get_contents("php://input"), true);
$name = $data["name"];
$email = $data["email"];
$password = $data["password"];

// Validasi jika email sudah digunakan
$checkEmail = "SELECT * FROM users WHERE email = '$email'";
$result = $conn->query($checkEmail);
if ($result->num_rows > 0) {
  echo json_encode(["status" => "error", "message" => "Email sudah terdaftar"]);
  exit;
}

// Enkripsi password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Simpan user baru
$sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$hashedPassword')";
if ($conn->query($sql) === TRUE) {
  echo json_encode(["status" => "success", "message" => "Registrasi berhasil"]);
} else {
  echo json_encode(["status" => "error", "message" => "Registrasi gagal"]);
}
?>
