<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $username);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $db_username, $hashed_password);
    if ($stmt->num_rows > 0) {
        $stmt->fetch();
        if (password_verify($password, $hashed_password)) {
            session_start();
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = ucfirst(strtolower($db_username));
            header("Location: ./index.php");
            exit();
        } else {
            echo "Senha incorreta.";
        }
    } else {
        echo "Nome de usuário ou email não existe.";
    }
    $stmt->close();
    $conn->close();
}
?>
