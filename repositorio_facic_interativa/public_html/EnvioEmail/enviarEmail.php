<?php
/**
 * SISTEMA UNIFICADO DE ENVIO DE EMAILS
 * Arquivo: EnvioEmail/enviarEmail.php
 * Versão: 2.0
 * Descrição: Função principal para envio de emails com conta única
 */

// Carregar configurações:
require_once __DIR__ . '/Config/smtp_config.php';
require_once __DIR__ . '/Config/email_settings.php';

// Carregar PHPMailer:
require_once __DIR__ . '/PHPMailer/class.phpmailer.php';
require_once __DIR__ . '/PHPMailer/class.smtp.php';

// Carregar logger:
require_once __DIR__ . '/EmailLogger.php';

/**
 * Envia um email usando PHPMailer com a conta única do sistema
 * 
 * @param string $destinatario Email do destinatário
 * @param string $assunto Assunto do email
 * @param string $corpo Corpo do email (HTML)
 * @param array $opcoes Opções adicionais (anexos, prioridade, etc)
 * @return array ['sucesso' => bool, 'erro' => string|null]
 */
function enviarEmail($destinatario, $assunto, $corpo, $opcoes = []) {
    global $SMTP_ACCOUNT, $SMTP_DEFAULT, $EMAIL_CONFIG, $VALIDATION_CONFIG;
    
    $logger = new EmailLogger();
    
    try {
        // ==========================================
        // 1. VALIDAR EMAIL DO DESTINATÁRIO
        // ==========================================
        
        $destinatario = trim($destinatario);
        
        if ($VALIDATION_CONFIG['validar_email']) {
            if (empty($destinatario)) {
                $logger->error("Destinatário vazio");
                return [
                    'sucesso' => false,
                    'erro' => 'Destinatário não fornecido'
                ];
            }
            
            if (!filter_var($destinatario, FILTER_VALIDATE_EMAIL)) {
                $logger->error("Email inválido: $destinatario");
                return [
                    'sucesso' => false,
                    'erro' => 'Email inválido'
                ];
            }
            
            // Verificar domínios bloqueados:
            $domain = substr(strrchr($destinatario, "@"), 1);
            if (in_array($domain, $VALIDATION_CONFIG['domains_bloqueados'])) {
                $logger->error("Domínio bloqueado: $domain");
                return [
                    'sucesso' => false,
                    'erro' => 'Domínio de email bloqueado'
                ];
            }
        }
        
        // ==========================================
        // 2. VALIDAR CONFIGURAÇÃO DA CONTA
        // ==========================================
        
        $conta = obterContaSMTP();
        
        if (!validarContaSMTP($conta)) {
            $logger->error("Conta SMTP mal configurada");
            return [
                'sucesso' => false,
                'erro' => 'Configuração SMTP inválida. Verificar Config/smtp_config.php'
            ];
        }
        
        $logger->info("Tentando enviar email para: $destinatario");
        
        // ==========================================
        // 3. CONFIGURAR PHPMAILER
        // ==========================================
        
        $mail = new PHPMailer(true);
        
        // Configurar SMTP:
        $mail->isSMTP();
        $mail->Host = $conta['host'];
        $mail->SMTPAuth = true;
        $mail->Username = $conta['email'];
        $mail->Password = $conta['password'];
        $mail->SMTPSecure = $conta['secure'];
        $mail->Port = $conta['port'];
        $mail->CharSet = $EMAIL_CONFIG['charset'];
        $mail->Encoding = $EMAIL_CONFIG['encoding'];
        $mail->Timeout = $conta['timeout'] ?? $EMAIL_CONFIG['timeout'];
        
        // Debug (se habilitado):
        if ($EMAIL_CONFIG['debug_level'] > 0) {
            $mail->SMTPDebug = $EMAIL_CONFIG['debug_level'];
            $mail->Debugoutput = function($str, $level) use ($logger) {
                $logger->debug("PHPMailer [$level]: $str");
            };
        }
        
        // ==========================================
        // 4. CONFIGURAR REMETENTE
        // ==========================================
        
        $from_email = $opcoes['from_email'] ?? $SMTP_DEFAULT['from_email'];
        $from_name = $opcoes['from_name'] ?? $SMTP_DEFAULT['from_name'];
        
        $mail->setFrom($from_email, $from_name);
        
        // Reply-To:
        $reply_email = $opcoes['reply_to_email'] ?? $SMTP_DEFAULT['reply_to_email'];
        $reply_name = $opcoes['reply_to_name'] ?? $SMTP_DEFAULT['reply_to_name'];
        $mail->addReplyTo($reply_email, $reply_name);
        
        // ==========================================
        // 5. CONFIGURAR DESTINATÁRIO
        // ==========================================
        
        $mail->addAddress($destinatario);
        
        // CC (se fornecido):
        if (isset($opcoes['cc']) && is_array($opcoes['cc'])) {
            foreach ($opcoes['cc'] as $cc_email) {
                $mail->addCC($cc_email);
            }
        }
        
        // BCC (se fornecido):
        if (isset($opcoes['bcc']) && is_array($opcoes['bcc'])) {
            foreach ($opcoes['bcc'] as $bcc_email) {
                $mail->addBCC($bcc_email);
            }
        }
        
        // ==========================================
        // 6. CONFIGURAR CONTEÚDO
        // ==========================================
        
        $mail->isHTML($EMAIL_CONFIG['is_html']);
        $mail->Subject = $assunto;
        $mail->Body = $corpo;
        $mail->AltBody = strip_tags($corpo);
        
        // Prioridade:
        $mail->Priority = $opcoes['priority'] ?? $EMAIL_CONFIG['priority'];
        
        // ==========================================
        // 7. ANEXOS
        // ==========================================
        
        if (isset($opcoes['anexos']) && is_array($opcoes['anexos'])) {
            foreach ($opcoes['anexos'] as $anexo) {
                if (is_array($anexo)) {
                    // Anexo com nome customizado:
                    $mail->addAttachment($anexo['caminho'], $anexo['nome'] ?? '');
                } else {
                    // Apenas caminho:
                    $mail->addAttachment($anexo);
                }
            }
        }
        
        // ==========================================
        // 8. ENVIAR
        // ==========================================
        
        $resultado = $mail->send();
        
        if ($resultado) {
            $logger->success("Email enviado com sucesso para $destinatario via {$conta['email']}");
            
            // Registrar no banco:
            $logger->registrarEnvio([
                'id_referencia' => $opcoes['id_referencia'] ?? null,
                'erro' => null,
                'mensagem' => "Email enviado com sucesso para: $destinatario",
                'status' => 'sucesso',
                'destinatario' => $destinatario,
                'assunto' => $assunto,
                'conta_smtp' => $conta['email']
            ]);
            
            return [
                'sucesso' => true,
                'erro' => null
            ];
        }
        
    } catch (Exception $e) {
        $erro = $mail->ErrorInfo ?? $e->getMessage();
        $logger->error("Falha ao enviar email: $erro");
        
        // Registrar erro no banco:
        $logger->registrarEnvio([
            'id_referencia' => $opcoes['id_referencia'] ?? null,
            'erro' => $erro,
            'mensagem' => "Falha ao enviar email para: $destinatario",
            'status' => 'erro',
            'destinatario' => $destinatario,
            'assunto' => $assunto,
            'conta_smtp' => $conta['email'] ?? 'N/A'
        ]);
        
        return [
            'sucesso' => false,
            'erro' => $erro
        ];
    }
}

/**
 * Versão simplificada que retorna apenas true/false
 */
function enviarEmailSimples($destinatario, $assunto, $corpo) {
    $resultado = enviarEmail($destinatario, $assunto, $corpo);
    return $resultado['sucesso'];
}

?>
