<?php
    require __DIR__ . "/template.php";
?>

<!DOCTYPE html>
<html lang="cs">
    <head>
        <meta charset="UTF-8">
        <title>Rozumím ti! - Umíte znakový jazyk?</title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    </head>
    <body>
        <header>
            <?php
                _header(2);
            ?>
        </header>  

        <div class="container mt-4">
            <div class="card bg-light p-4">
                <h2>Umíte Znakový jazyk?</h2>

                <p>Umíte znakový jazyk a chcete být součástí vývoje této služby? Hledám neslyšící nebo certifikované lektory ČZJ, kteří by byli ochotni poskytnout fotografie nebo video svých znaků. Vaše záznamy budou využity k trénování AI modelu, díky čemuž bude aplikace přesnější a schopná rozpoznávat více znaků.</p>
                
                <p>Stačí kliknout na tlačítko níže a vyplnit krátký kontaktní formulář. V případě dotazů mě kontaktujte přes stejný formulář.</p>
                <a href="kontakt.php" class="btn btn-primary col-2">Kontakt</a>
            </div>
        </div>
    </body>
</html>