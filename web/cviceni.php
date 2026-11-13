<?php
    require __DIR__ . "/template.php";

    //propojení s databází
    $conn = mysqli_connect('localhost:8889', 'root', 'root', 'rozumim_ti_SQL');

    if (!$conn) {
        die("Connection failed: ". mysqli_connect_error());
    }
?>

<!DOCTYPE html>
<html lang="cs">
    <head>
        <meta charset="UTF-8">
        <title>Rozumím ti! - Cvičení</title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    </head>
    <body>
        <header>
            <?php
                _header(1);
            ?>
        </header>  

        <div class="container mt-4">
            <div class="card bg-light p-4">
                <h2>Cvičení</h2>
                
                <p>Níže najdete seznam dostupných cvičení. Ke cvičení budete otřebovat kameru.</p>
            </div>
        </div>

        <?php
            $sql = "SELECT id_excercise, name_excercise, descr FROM cviceni";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)) {
                    excercise_card($row["name_excercise"], $row["descr"], $row["id_excercise"]);
                }	
            }else{
                print("<div class=\"container mt-4\"><div class=\"card bg-light p-4\">");
                print("<h2>Nic tu není.</h2><p>Omlouváme se, ale žádné cvičení není momentálně k dispozici.</p>");
                print("</div></div>");
            }
        ?>
    </body>
</html>
