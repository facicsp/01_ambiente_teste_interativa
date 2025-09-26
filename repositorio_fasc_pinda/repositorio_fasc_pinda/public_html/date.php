    <?php
            date_default_timezone_set('America/Sao_Paulo');
            echo date("d/m/Y H:i");

            if (function_exists('date_default_timezone_set')) {
                echo "Funciona";
            } else {
                echo "Não funciona";
            }
    ?>
