<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Captura o número enviado pelo formulário
        $numero = $_POST['numero'];

        // Verifica se o valor é um número inteiro
        if (is_numeric($numero) && intval($numero) == $numero) {
            // Calcula a soma dos dígitos
            $soma = array_sum(array_map('intval', str_split(abs($numero))));
            echo "<p>A soma dos dígitos de $numero é: $soma</p>";
        } else {
            echo "<p>Por favor, insira um número inteiro válido.</p>";
        }
    }
    ?>