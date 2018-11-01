<?php
/*
Questa pagina mostra il dettaglio di un articolo del nostro miniblog
legge da DB e presenta tutti i post di tipo post
*/
require_once('functions.php');// la conoscete
?>
<?php
// stabilisco una connessione con il DB
$link = mysqli_connect("localhost", "utentecorsophp", "password", "corsophp");
// gestisco gli errori
if (!$link) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}

echo "Success: A proper connection to MySQL was made! The my_db database is great." . PHP_EOL;
echo "Host information: " . mysqli_get_host_info($link) . PHP_EOL;

/* 
scrivo una query per recuperare il post con un particolare id
quello passato dal link more in blog.php
*/

$id=$_GET['postid'];/*$_GET è una array di tipo associativo che appartiene alle
varibili di tipo SUPERGLOBALS (varibili sempre disponibili nello *script corrente*, anche all'interno di 
funzioni e oggetti senza che vengano esplicitamente dichiarate tali)
http://php.net/manual/en/language.variables.superglobals.php

qui troverete la spiegazionie specifica della variabile $_GET (http://php.net/manual/en/reserved.variables.get.php)

Ogni volta che un parametro viene aggiunto ad un url ed inviato ad una pagina php
il server salva il suo nome ed il suo valore nell'array $_GET, il valore del parametro sarà sempre
disponibile (nello script corrente) e recuperabile tramite il nome.
*/

$query="SELECT id, postType, datecreation, title, content FROM posts
        WHERE id='$id'";

// eseguo la query e salvo il risultato in una variabile 

$result=mysqli_query($link,$query);
 
mysqli_close($link);

// a scopi didattici stampo l'array $_GET per vederne il contenuto

echo'<pre>';
print_r($_GET);
echo'</pre>';

?>


<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>
    
    <body>
        
        <?php
        if($_COOKIE['LOGIN']){
                    // stampo il bottone bottone di logout
                    echo"<p>Benvenuto ".$_COOKIE['LOGIN']."</p>";
                    echo'<a href="login.php?logout=yes">Logout</a>';
        }
        ?>
        
            <?php
            // il menu
            echo buildmenu('ilMiomenu',$array);
            ?>
        <div class="content">
            <?php
            
              /*Questa sezione verrà mostrata solo se autenticati
            * in caso contrario verrà mostrato un testo che invita ad autenticarsi
             *  */
            if(!$_COOKIE['LOGIN']){
                echo'<p class="error">Per accedere all\'admin dovrete <a href="login.php">autenticarvi</a></p>';
            }else{
             echo'<h2>Amministrazione</h2>';
            // aggiungo un ulteriore menu... un sottomenu se volete chiamarlo così
            echo buildmenu('submenu',$arrayAdmin);
            // $arrayAdmin si trova in function.php 
            
            
            echo'<article>';
               
            while($row = mysqli_fetch_assoc($result)){
                
                echo '<h2>'.$row['title'].'</h2>';
                echo '<div class="postContent">'.$row['content'].'</div>';
                    
            }
            
            echo'</article>';
            }
            ?>
        </div>
    
    </body>
    
</html>