<?php
include 'connect.php';
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

$data = json_decode(file_get_contents("php://input"), true);
$email = $data["email"];
$password = $data["password"];

// ❗️PERBAIKAN: Tambahkan kolom SELECT (id, email, password, name)
$sql = "SELECT * FROM users WHERE email = '$email'";
$result = $conn->query($sql);

if ($result->num_rows === 1) {
  $user = $result->fetch_assoc();

  // ✅ Gunakan password_verify
  if (password_verify($password, $user['password'])) {
    echo json_encode([
      "status" => "success",
      "message" => "Login successful",
      "name" => $user["name"]
    ]);
  } else {
    echo json_encode(["status" => "error", "message" => "Invalid password"]);
  }
} else {
  echo json_encode(["status" => "error", "message" => "User not found"]);
}
?>
