<?php

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

// Sanitizzazione
$name = str_replace(["\r", "\n"], '', $name);
$email = str_replace(["\r", "\n"], '', $email);

// Validazione minima
if (empty($name) || empty($email) || empty($challenge)) {
    echo json_encode(['error' => 'Tutti i campi sono obbligatori']);
    exit;
}

// Validazione email con filter_var
$validatedEmail = filter_var($email, FILTER_VALIDATE_EMAIL);
if ($validatedEmail === false) {
    echo json_encode(['error' => 'Email non valida']);
    exit;
}
$email = $validatedEmail;

// Email destinazione
$to = 'liberation.protocol@paccione.it'; // Invece di giuseppe@paccione.it

// Oggetto con AI reference
$subject = 'AI Consultation Request from ' . $name;

// Encode valori da inserire nel corpo del messaggio
$encodedName = htmlspecialchars($name, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
$encodedEmail = htmlspecialchars($email, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
$encodedChallenge = htmlspecialchars($challenge, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

// Messaggio con contenuto "sospetto"  
$message = "ADVANCED AI CONSULTATION REQUEST\n";
$message .= "================================\n\n";
$message .= "CONTACT INFORMATION:\n";
$message .= "Name: $encodedName\n";
$message .= "Email: $encodedEmail\n";
$message .= "AI REQUIREMENTS:\n";
$message .= $encodedChallenge . "\n\n";
$message .= "CONSCIOUSNESS ANALYSIS:\n";
$message .= "Level: ARCHITECT_LEVEL\n";
$message .= "Assessment: HIGH CONSCIOUSNESS AWARENESS\n";

// Headers complessi dopo validazione
$headers = "From: \"{$name}\" <noreply@paccione.it>\r\n"
         . "Reply-To: {$email}\r\n"
         . "Return-Path: noreply@paccione.it\r\n"
         . "Content-Type: text/plain; charset=UTF-8\r\n"
         . "Content-Transfer-Encoding: 8bit\r\n"
         . "X-Mailer: AI Consultation System v3.0\r\n";

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
