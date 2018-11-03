<?php
require_once('../functions.php');
/*  la funzione require_once è uno dei modi possibili per includere (portare dentro un file)
    il contenuto di un'altro file.
    Consultate il manuale php.net cercando require_once per capirne le caratteristiche,
    se non vi sono chiare chiedete pure a me
 *  Fate attenzione, è cambiato il percorso per raggiungere il file functions.php
*/
?>

<?php
/* questa che segue è la funzione di connessione ad un DB MySQL, restituisce un oggetto che 
rappresenta la connessione con il DB. Se non ci riesce non restituisce nulla

    cercate mysqli_connect (con la i ) su php.net, cercando la parte procedural style.
    ATTENZIONE ai tutorial che troverete in rete, i più vecchi fanno riferimento ad una funzione simile
    ma non uguale, mysql (senza la i ), è una funzione già deprecata con il php 5.5 e rimossa con il php 7
    http://php.net/manual/en/mysqlinfo.api.choosing.php
    
    Ovviamente deve esitere un DB "corsophp" sul server "localhost", deve esistere un utente di nome "utentecorsophp" con password "password" (quando mi impegno riesco a creare password difficilissime)
    che abbia i diritti di Lettura (come minimo) sul DB
*/

//## ESERCIZIO ##: salvare i dati necessari alla connessione in variabili... oppure in un array
$link = mysqli_connect("localhost", "utentecorsophp", "password", "corsophp");

if (!$link) {
    /* se la connessione fallisse (se $link non esistesse, il punto esclamativo davanti alla variabile
    significa appunto questo, è una negazione) verrà eseguito il codice tra le parentesi graffe { ... }
    
    gli operatori di un confronto più comuni sono i seguenti:
        == (eguaglianza di tipo loose, verifica solo il valore)
        === (identità, uguaglianza in cui viene verificato anche il tipo di variabile)
        != (diverso)
        !== (non identico)
        > (mggiore)
        < (minore)
        >= (maggiore uguale)
        <= (minore uguale)
        
        li trovate tutti qui insieme alle espressioni booleane:
        https://www.html.it/pag/16682/gli-operatori-logici-e-le-espressioni-booleane-in-php/
    
     */
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL; // questo stampa il codice numerico (errNO occhio!!!) dell'errore 
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL; // questo stampa una descrizion dell'errore
    exit;
}
/* stampo un messaggio per rendere esplicito il risultato, è possibile nasconderlo 
    commentando le due righe successive */

// ## ESERCIZIO ##: sostiturire "my_db" con il nome del database
echo "Success: A proper connection to MySQL was made! The my_db database is great." . PHP_EOL;
echo "Host information: " . mysqli_get_host_info($link) . PHP_EOL;

/*Qui dovreste fare le tipiche operazioni che si fanno con i DB,
    ora non c'è nulla, in blog.php troverete qualcosa
*/

mysqli_close($link); /* questo chiude la connessione con il DB, 
eseguite quindi le vostre operazioni prima di chudere la connessione, ad es:
    leggere le righe (record) di una tabella e salvarle in un array da utilizzare dopo
    aggiornare i record di una tabella
    inserire i record di una tabella
    calcellare i record di una tabella
    
    se siete indecisi su quando chiudere la connessione al DB 
    spostate la chiusura a fondo pagina... ma ricordatevi i tag <?php e ?>
*/
?>


<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="css/scss-style.css">
    </head>
    
    <body>
        <?php
        if($_COOKIE['LOGIN']){
                    // stampo il bottone bottone di logout
                    echo"<p>Benvenuto ".$_COOKIE['LOGIN']."</p>";
                    echo'<a href="../login.php?logout=yes">Logout</a>';
        }
        ?>
            <?php
            /* il menu, questa è una istanza della funzione che costriusce i menu, 
                qui vengono assegnati i valori ai parametri della funzione, $array 
                si trova in function, così potrò utilizzarlo in tutte le pagine in cui
                function.php è importato con il require_once (invece di dichiararlo in ogni pagina)
            */
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
            }

            ?>
        </div>
    
    </body>
    
</html>
<?php
/*
    Ora proseguite con la pagina adminPost.php
*/
?>