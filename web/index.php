<?php
    require __DIR__ . "/template.php";
?>

<!DOCTYPE html>
<html lang="cs">
    <head>
        <meta charset="UTF-8">
        <title>Rozumím ti!</title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    </head>
    <body>
        <header>
            <?php
                _header(0);
            ?>   

        <div class="container mt-4">
            <div class="card bg-light p-4">
                <h2>Rozumím ti!</h2>

                <p>Rozumím ti! je webová aplikace určená k procvičování českého znakového jazyka (v aktuální verzi je dostupné pouze cvičení české obouruční abecedy). Princip je jednoduchý: stránka ti zadá znak, ty ho ukážeš do kamery a AI ti okamžitě řekne, zda znak ukazuješ správně.</p>
                <p>Pozor! Je důležité učit se znakový jazyk s neslyšícím nebo učitelem. Tato stránka slouží spíše jako procvičování navíc.</p>
            </div>
        </div>

        <div class="container mt-4">
            <div class="card bg-light p-4">
                <h2>Chci procvičovat.</h2>

                <p>Klikni na tlačítko níže a začni hned procvičovat.</p>
                <a href="cviceni.php" class="btn btn-primary col-2">Cvičení</a>
            </div>
        </div>
        
        <div class="container mt-4">
            <div class="card bg-light p-4">
                <h2>Umíte znakový jazyk?</h2>

                <p>Umíte znakový jazyk a chcete být součástí vývoje této aplikace? Pro více informací klikněte na tlačítko níže.</p>
                <a href="uzj.php" class="btn btn-primary col-2">Chci vědět víc</a>
            </div>
        </div>

    </body>
</html>
