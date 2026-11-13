<?php
    require __DIR__ . "/template.php";

    if(isset($_REQUEST["odeslat"])){
        if(isset($_REQUEST["jmeno"]) && isset($_REQUEST["prijmeni"]) && isset($_REQUEST["email"]) && isset($_REQUEST["text"])){
            $conn = mysqli_connect('localhost:8889', 'root', 'root', 'rozumim_ti_SQL');

            if (!$conn) {
                die("Connection failed: ". mysqli_connect_error());
            }

            $sql = "INSERT INTO kontakt (jmeno, prijmeni, email, typ_zpravy, text_zpravy) VALUES (\"".$_REQUEST["jmeno"]."\",\"".$_REQUEST["prijmeni"]."\",\"".$_REQUEST["email"]."\",\"".$_REQUEST["typ_zpravy"]."\",\"".$_REQUEST["text_zpravy"]."\");";
            mysqli_query($conn, $sql);
        }
    }
?>

<!DOCTYPE html>
<html lang="cs">
    <head>
        <meta charset="UTF-8">
        <title>Rozumím ti! - Kontakt</title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    </head>
    <body>
        <header>
            <?php
                _header(3);
            ?>
        </header>  

        <div class="container mt-4">
            <div class="card bg-light p-4">
                <h2>Kontaktní formulář</h2>
                <form action="kontakt.php" method="get">
                    <div class="row g-3 mt-0">
                        <div class="col">
                            <input type="text" class="form-control" placeholder="Jméno" name="jmeno" aria-label="first-name" required>
                        </div>
                        <div class="col">
                            <input type="text" class="form-control" placeholder="Příjmení" name="prijmeni" aria-label="last-name" required>
                        </div>
                    </div>

                    <div class="row g-3 mt-0">
                        <div class="col">
                            <input type="email" class="form-control" name="email" placeholder="e-mail" required>
                        </div>
                        <div class="col">
                            <select id="inputState" class="form-select" name="typ_zpravy">
                                <option selected>Zpráva/dotaz</option>
                                <option>Spolupráce</option>
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 mt-0">
                        <div class="col">
                            <div class="form-floating">
                                <textarea class="form-control" id="floatingTextarea2" style="height: 200px" name="text_zpravy" required></textarea>
                                <label for="floatingTextarea2">Text zprávy</label>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mt-0">
                        <div class="col">
                            <button type="submit" class="btn btn-primary" name="odeslat">Odeslat</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>
