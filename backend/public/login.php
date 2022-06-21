<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Database\Connection;
use Firebase\JWT\JWT;

header('Access-Control-Allow-Origin: *');

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__FILE__, 2));
$dotenv->load();

$email = filter_input(INPUT_POST, 'email',  FILTER_SANITIZE_SPECIAL_CHARS);
$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);

$pdo = Connection::getConn();

$prepare = $pdo->prepare('SELECT * FROM users WHERE email = :email');
$prepare->execute([
    ':email' => $email
]);
$userFound = $prepare->fetch(PDO::FETCH_OBJ);

if( !$userFound || !password_verify($password, $userFound->password)){
    http_response_code(401);
    echo json_encode(['message' => 'Email or password is incorrect']);
    die();
}

$payload = [
    "exp" => time() + 10,
    "iat" => time(),
    "content" => [
        "id" => $userFound->id,
        "email" => $userFound->email,
        "name" => $userFound->name,
    ]
];

$encode = JWT::encode($payload, $_ENV['KEY'], 'HS256');

echo json_encode($encode);