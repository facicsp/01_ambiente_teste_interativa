<?php
/**
 * Endpoint para receber logs do JavaScript
 * Salva logs em arquivo automaticamente
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $logData = file_get_contents('php://input');
    $logObj = json_decode($logData, true);

    if ($logObj) {
        $logFile = 'logs_javascript_' . date('Y-m-d') . '.txt';
        $logPath = __DIR__ . '/' . $logFile;

        $timestamp = date('Y-m-d H:i:s');
        $sessao = session_id();

        $logEntry = "\n" . str_repeat('=', 80) . "\n";
        $logEntry .= "[$timestamp] LOG DO JAVASCRIPT\n";
        $logEntry .= str_repeat('=', 80) . "\n";

        if (isset($logObj['tipo'])) {
            $logEntry .= "TIPO: " . strtoupper($logObj['tipo']) . "\n";
        }

        if (isset($logObj['mensagem'])) {
            $logEntry .= "MENSAGEM: " . $logObj['mensagem'] . "\n";
        }

        if (isset($logObj['dados'])) {
            $logEntry .= "\nDADOS:\n";
            $logEntry .= print_r($logObj['dados'], true);
        }

        if (isset($logObj['erro'])) {
            $logEntry .= "\nERRO:\n";
            $logEntry .= print_r($logObj['erro'], true);
        }

        $logEntry .= "\n";

        file_put_contents($logPath, $logEntry, FILE_APPEND);

        echo json_encode(['success' => true, 'arquivo' => $logFile]);
    } else {
        echo json_encode(['success' => false, 'erro' => 'JSON inválido']);
    }
} else {
    echo json_encode(['success' => false, 'erro' => 'Método não permitido']);
}
?>
