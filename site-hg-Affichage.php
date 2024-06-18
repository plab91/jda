<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <title>Affichage</title>
</head>
<body>
  <?php 

$sqlHost = "localhost";
$sqlUser = "root";
$sqlPass = "root";
$dbname = "test";

/*
$sqlHost = 'localhost';
$sqlUser = 'root';
$sqlPass = 'root';
$dbname = 'dbs12345702';

*/
$link = mysqli_connect($sqlHost, $sqlUser, $sqlPass, $dbname);
mysqli_set_charset($link, "utf8");
 

  $execut_select = "SELECT * FROM documents ";  
  $resultat = $link->query($execut_select); 
  
  ?>
      <div class="navigation">
    <a href="saisie.php">Saisie d'un document</a>
    <a href="Criteres_affichage.php">Affichage des documents</a>
  </div>
  
  <section>
    <fieldset>
    <?php 

    $requete="SELECT * FROM `documents` JOIN `periode` ON `p_id`=`h_periode` 
            
            JOIN `nature` ON `n_id`=`h_nature`";
    
    $where_test=True;

    $theme=array();

    if (isset($_POST['T1'])){
        if ($where_test){
            //echo "zzzz".$_POST['T1'];
            $T1=" WHERE SUBSTRING(`h_theme`, 1,1 )=$_POST[T1] ";
            $where_test=False;
            $requete=$requete.$T1;
        }else{
            //echo $_POST['T1'];
            $T1=" AND SUBSTRING(`h_theme`, 1,1 )=$_POST[T1] ";
            $requete=$requete.$T1;
        }
    $theme[]="Politique";
    //print_r($theme);
}

if (isset($_POST['T2'])){
    if ($where_test){
        $T2=" WHERE SUBSTRING(`h_theme`, 2,1 )=$_POST[T2] ";
        $where_test=False;
        $requete=$requete.$T2;
    }else{
        $T2=" AND SUBSTRING(`h_theme`, 2,1 )=$_POST[T2] ";
        $requete=$requete.$T2;
    }

}

if (isset($_POST['T3'])){
    if ($where_test){
        $T3=" WHERE SUBSTRING(`h_theme`, 3,1 )=$_POST[T3] ";
        $where_test=False;
        $requete=$requete.$T3;
    }else{
        $T3=" AND SUBSTRING(`h_theme`, 3,1 )=$_POST[T3] ";
        $requete=$requete.$T3;
    }
    $theme[]="Economie";

}

if (isset($_POST['T4'])){
    if ($where_test){
        $T4=" WHERE SUBSTRING(`h_theme`, 4,1 )=$_POST[T4] ";
        $where_test=False;
        $requete=$requete.$T4;
    }else{
        $T4=" AND SUBSTRING(`h_theme`, 4,1 )=$_POST[T4] ";
        $requete=$requete.$T4;
    }
    $theme[]="Culture";
}
    
    if (!isset($_POST['periode1'])){
            if ($where_test){
                $periode=" WHERE `h_periode`=$_POST[periode] ";
                $where_test=False;
                $requete=$requete.$periode;
            }else{
                $periode=" AND `h_periode`=$_POST[periode] ";
                $requete=$requete.$periode;
            }
    }
    
    if (!isset($_POST['nature1'])){
        if ($where_test){
            $nature=" WHERE `h_nature`=$_POST[nature] ";
            $where_test=False;
            $requete=$requete.$nature;
        }else{
            $nature=" AND `h_nature`=$_POST[nature] ";
            $requete=$requete.$nature;
        }
    }

    if (!isset($_POST['auteur1'])){
        if ($where_test){
            $auteur=" WHERE `h_auteur`=$_POST[auteur] ";
            $where_test=False;
            $requete=$requete.$auteur;
        }else{
            $auteur=" AND `h_auteur`='$_POST[auteur]' ";
            $requete=$requete.$auteur;
        }
    }

    if ($_POST['date']!=""){
        if ($where_test){
            $date=" WHERE SUBSTRING(`h_date`, 1, 4)='$_POST[date]'";
            $where_test=False;
            $requete=$requete.$date;
        }else{
            $date=" AND SUBSTRING(`h_date`, 1, 4)='$_POST[date]'";
            $requete=$requete.$date;
        }
    }

    //echo $requete."<br><br><br>";
    $result = $link->query($requete); 

    //$result->data_seek(0);

    //print_r($theme);
    echo "<br><br><br>";

    while($row=$result->fetch_assoc()){
        $file_name=$row['h_fichier'];
    
        echo "<div class='document'>";
        echo "<h2>Nom : {$row['h_titre']}</h2>";
    
        echo "<p>Thèmes : ";
        if ($row["h_theme"][0]==1){
            echo " Politique ";
        }
        if ($row["h_theme"][1]==1){
            echo "Société ";
        }
        if ($row["h_theme"][2]==1){
            echo "Économie ";
        }
        if ($row["h_theme"][3]==1){
            echo "Culture ";
        }
        echo "</p>";
    
        echo "<p>Nature : {$row['n_nom']}</p>";
        echo "<p>Période : {$row['p_nom']}</p>";
        echo "<p>Auteur : {$row['h_auteur']}</p>";
        echo "<p>Date : {$row['h_date']}</p>";
        echo "<p>Sujet : {$row['h_sujet']}</p>";
        echo "<p>Source : {$row['h_source']}</p>";
    
        $file_ext_arr = explode('.',$file_name);
        $file_ext = strtolower(end($file_ext_arr));
    
        if ($file_ext=='jpeg' or $file_ext=='png' or $file_ext=='jpg' ){
            echo "<img src='fichiers/{$row['h_fichier']}' alt='Image'>";
        }
        elseif($file_ext==='pdf'){
            echo "<embed src='fichiers/{$row['h_fichier']}' width='100%' height='600px' type='application/pdf'/>";
        }
        elseif ($file_ext==='txt'){
            if (file_exists("fichiers/{$row['h_fichier']}")){
                $file_content = file_get_contents("fichiers/{$row['h_fichier']}");
                echo "<p>Contenu txt:</p>";
                echo "<pre>{$file_content}</pre>";
            }else{
                echo "<p>Fichier introuvable</p>";
            }
        }
        elseif($file_ext==='html'){
            if (file_exists("fichiers/{$row['h_fichier']}")){
                $file_content=file_get_contents("fichiers/{$row['h_fichier']}");
                echo "<p>Contenu HTML:</p>";
                echo "<pre>{$file_content}</pre>";
            }
        }
        echo "<p><a href='fichiers/{$row['h_fichier']}' download>Télécharger le document</a></p>";
        echo "</div>";
    }    
    ?>
</fieldset>
</section>
</body>
</html>
