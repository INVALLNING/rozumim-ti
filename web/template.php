<?php 
    //funkce k vytvořením hlavičky stránky
    function _header($page){
        $sites = ["Domů", "Cvičení", "Umíte znakový jazyk?", "Kontakt", "Přihlášení"];
        $paths = ["index.php", "cviceni.php", "uzj.php", "kontakt.php", "login.php"];
        print("<nav class=\"navbar navbar-expand-lg bg-body-tertiary\"><div class=\"container-fluid\"><div class=\"collapse navbar-collapse\" id=\"navbarNav\"><ul class=\"navbar-nav\">");
        for($i=0;$i<=4;$i++){
            print("<li class=\"nav-item\"><a class=\"nav-link");
            if($page == $i){
                print(" active\" aria-current=\"page\"");
            }
            else{
                print("\"");
            }
            print(" href=\"".$paths[$i]."\">".$sites[$i]."</a></li>");
        }
        print("</ul></div></div></nav>");
    }

    //vytvoření karty se cvičením
    function excercise_card($name, $description, $id){
        print("<div class=\"container mt-4\"><div class=\"card bg-light p-4\">");
        print("<h2>".$name."</h2>");
        print("<p>".$description."</p>");
        print("<form action=\"procvicovani.php\" method=\"get\"><button type=\"submit\" name=\"submit\" value=\"".$id."\" class=\"btn btn-primary\">Procvičit!</button></form>");
        print("</div></div>");
    }
?>
 