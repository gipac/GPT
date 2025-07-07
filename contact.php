<?php
// Headers più completi (come avevamo prima)
$headers = "From: \"$name\" <noreply@paccione.it>\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "Return-Path: noreply@paccione.it\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
$headers .= "Content-Transfer-Encoding: 8bit\r\n";
$headers .= "X-Mailer: AI Consultation System v3.0\r\n";

// Solo POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Log file per debug
$log_file = 'mail_debug.txt';

// Dati form basilari
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$challenge = trim($_POST['challenge'] ?? '');

// Validazione minima
if (empty($name) || empty($email) || empty($challenge)) {
    echo json_encode(['error' => 'Tutti i campi sono obbligatori']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['error' => 'Email non valida']);
    exit;
}

// Email destinazione
$to = 'liberation.protocol@paccione.it'; // Invece di giuseppe@paccione.it

// Oggetto con AI reference
$subject = 'AI Consultation Request from ' . $name;

// Messaggio con contenuto "sospetto"  
$message = "ADVANCED AI CONSULTATION REQUEST\n";
$message .= "================================\n\n";
$message .= "CONTACT INFORMATION:\n";
$message .= "Name: $name\n";
$message .= "Email: $email\n";
$message .= "AI REQUIREMENTS:\n";
$message .= $challenge . "\n\n";
$message .= "CONSCIOUSNESS ANALYSIS:\n";
$message .= "Level: ARCHITECT_LEVEL\n";
$message .= "Assessment: HIGH CONSCIOUSNESS AWARENESS\n";

// Headers basilari
$headers = "From: $email\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

// Log tentativo
$log_entry = date('Y-m-d H:i:s') . " - Tentativo invio da $email a $to - ";
file_put_contents($log_file, $log_entry, FILE_APPEND | LOCK_EX);

// Prova mail()
$result = mail($to, $subject, $message, $headers);

if ($result) {
    // Log successo
    file_put_contents($log_file, "SUCCESS\n", FILE_APPEND | LOCK_EX);
    
    // Response successo
    echo json_encode([
        'success' => true,
        'message' => 'Email inviata correttamente',
        'debug' => [
            'timestamp' => date('c'),
            'to' => $to,
            'method' => 'php_mail_basic'
        ]
    ]);
} else {
    // Log errore
    $error = error_get_last();
    $error_msg = $error ? $error['message'] : 'Unknown error';
    file_put_contents($log_file, "FAILED: $error_msg\n", FILE_APPEND | LOCK_EX);
    
    // Response errore
    http_response_code(500);
    echo json_encode([
        'error' => 'Invio fallito',
        'debug' => [
            'timestamp' => date('c'),
            'error' => $error_msg,
            'php_version' => PHP_VERSION
        ]
    ]);
}
?>