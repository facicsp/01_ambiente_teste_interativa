<?php
/**
 * CLASSE DE LOG PARA ENVIO DE EMAILS
 * Arquivo: EnvioEmail/EmailLogger.php
 * Descrição: Registra logs em arquivo e banco de dados
 */

class EmailLogger {
    private $log_file;
    private $pdo;
    private $habilitar_log_arquivo;
    private $habilitar_log_banco;
    
    public function __construct() {
        global $LOG_CONFIG;
        
        $this->habilitar_log_arquivo = $LOG_CONFIG['habilitar_log_arquivo'];
        $this->habilitar_log_banco = $LOG_CONFIG['habilitar_log_banco'];
        
        // Configurar arquivo de log:
        if ($this->habilitar_log_arquivo) {
            $data = date('Y-m-d');
            $this->log_file = $LOG_CONFIG['dir_logs'] . "emails_{$data}.log";
        }
        
        // Conectar ao banco:
        if ($this->habilitar_log_banco) {
            try {
                require_once __DIR__ . '/../conexao.php';
                // Assumindo que conexao.php define $pdo global
                global $pdo;
                $this->pdo = $pdo;
            } catch (Exception $e) {
                $this->habilitar_log_banco = false;
                $this->error("Não foi possível conectar ao banco para logs: " . $e->getMessage());
            }
        }
    }
    
    /**
     * Registra log de informação
     */
    public function info($mensagem) {
        $this->log('INFO', $mensagem);
    }
    
    /**
     * Registra log de sucesso
     */
    public function success($mensagem) {
        $this->log('SUCCESS', $mensagem);
    }
    
    /**
     * Registra log de aviso
     */
    public function warning($mensagem) {
        $this->log('WARNING', $mensagem);
    }
    
    /**
     * Registra log de erro
     */
    public function error($mensagem) {
        $this->log('ERROR', $mensagem);
    }
    
    /**
     * Registra log de debug
     */
    public function debug($mensagem) {
        $this->log('DEBUG', $mensagem);
    }
    
    /**
     * Método principal de log
     */
    private function log($nivel, $mensagem) {
        $timestamp = date('Y-m-d H:i:s');
        $linha = "[$timestamp] [$nivel] $mensagem\n";
        
        // Log em arquivo:
        if ($this->habilitar_log_arquivo && $this->log_file) {
            file_put_contents($this->log_file, $linha, FILE_APPEND);
        }
        
        // Log no error_log do PHP:
        error_log("EmailLogger [$nivel]: $mensagem");
    }
    
    /**
     * Registra envio no banco de dados
     */
    public function registrarEnvio($dados) {
        if (!$this->habilitar_log_banco || !$this->pdo) {
            return false;
        }
        
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO log_envio_email 
                (idprova, erro, enviou, status, data_log, destinatario, assunto, conta_smtp)
                VALUES (?, ?, ?, ?, NOW(), ?, ?, ?)
            ");
            
            $stmt->execute([
                $dados['id_referencia'] ?? null,
                $dados['erro'] ?? null,
                $dados['mensagem'] ?? '',
                $dados['status'] ?? 'erro',
                $dados['destinatario'] ?? '',
                $dados['assunto'] ?? '',
                $dados['conta_smtp'] ?? ''
            ]);
            
            return true;
        } catch (Exception $e) {
            $this->error("Falha ao registrar no banco: " . $e->getMessage());
            return false;
        }
    }
}

?>