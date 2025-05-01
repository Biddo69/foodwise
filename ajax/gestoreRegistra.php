<?php
require_once("../includes/conn.php");

// Attiva gli errori MySQLi per debug
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Imposta risposta JSON
header('Content-Type: application/json');

// Recupera e valida input
$username      = trim($_POST['username'] ?? '');
$email         = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$password      = $_POST['password'] ?? '';
$data_nascita  = $_POST['data_nascita'] ?? '';
$sesso         = $_POST['sesso'] ?? '';
$peso          = $_POST['peso'] ?? '';
$altezza       = $_POST['altezza'] ?? '';

if (!$username || !$email || !$password || !$data_nascita || !$sesso || !$peso || !$altezza) {
    echo json_encode(['status'=>'ERR','msg'=>'Tutti i campi sono obbligatori.']);
    exit;
}

// Verifica duplicati (email o username)
$stmt = $conn->prepare("SELECT id FROM utente WHERE email = ? OR username = ? LIMIT 1");
$stmt->bind_param('ss', $email, $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(['status'=>'ERR','msg'=>'Email o username già registrati.']);
    exit;
}
$stmt->close();

// Calcola peso goal
$passmd5    = md5($password);
$altezza_m  = ((float)$altezza) / 100;

$bmi_ideale = ($sesso === 'M') ? 23 : 21;
$peso_goal  = round($bmi_ideale * $altezza_m * $altezza_m, 2);

// Inserisci nel DB
$stmt = $conn->prepare("
    INSERT INTO utente (username, passmd5, email, data_nascita, peso, altezza, peso_goal, sesso)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
");

$stmt->bind_param(
    'ssssdids',
    $username,
    $passmd5,
    $email,
    $data_nascita,
    $peso,
    $altezza,
    $peso_goal,
    $sesso
);

if ($stmt->execute()) {
    echo json_encode(['status'=>'OK','msg'=>'Registrazione avvenuta con successo.']);
} else {
    echo json_encode(['status'=>'ERR','msg'=>'Errore durante la registrazione: ' . $stmt->error]);
}
$stmt->close();
exit;
?>
