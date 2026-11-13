<?php
    require __DIR__ . "/template.php";
?>

<!DOCTYPE html>
<html lang="cs">
    <head>
        <meta charset="UTF-8">
        <title>Rozumím ti! - Přihlášení</title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    </head>
    <body>
        <header>
            <?php
                _header(4);
            ?>
        </header>  
        <?php
            if(isset($_REQUEST["odeslat"])){
                if($_REQUEST["email"] == "mail@mail.mail" && $_REQUEST["heslo"] == "d"){
                    $conn = mysqli_connect('localhost:8889', 'root', 'root', 'rozumim_ti_SQL');

                    if (!$conn) {
                        die("Connection failed: ". mysqli_connect_error());
                    }

                    $sql = "SELECT id_zprava, jmeno, prijmeni, email, typ_zpravy, text_zpravy FROM kontakt";
                    $result = mysqli_query($conn, $sql);
                    if (mysqli_num_rows($result) > 0) {
                        while($row = mysqli_fetch_assoc($result)) {
                            print("<div class=\"container mt-4\"><div class=\"card bg-light p-4\">");
                            print("<h2>".$row["id_zprava"].". ".$row["jmeno"]." ".$row["prijmeni"]."</h2>");
                            print("<h3>".$row["typ_zpravy"]."</h3>");
                            print("<p>".$row["email"]."</p>");
                            print("<p>".$row["text_zpravy"]."</p>");
                            print("</div></div>");
                        }	
                    }
                }
            }else{
                print("<div class=\"container mt-4\"><div class=\"card bg-light p-4\">");
                print("<h2>Přihlášení</h2><form action=\"login.php\" method=\"get\">");
                print("<div class=\"row g-3 mt-0\"><div class=\"col\"><input type=\"e-mail\" class=\"form-control\" name=\"email\" placeholder=\"e-mail\" aria-label=\"e-mail\" require></div></div>");
                print("<div class=\"row g-3 mt-0\"><div class=\"col\"><input type=\"password\" class=\"form-control\" name=\"heslo\" placeholder=\"heslo\" aria-label=\"password\" require></div></div>");
                print("<div class=\"row g-3 mt-0\"><div class=\"col\"><button type=\"submit\" class=\"btn btn-primary\" name=\"odeslat\">Přihlásit</button></div></div>");
                print("</form></div></div>");
            }
        ?>
    </body>
</html>
