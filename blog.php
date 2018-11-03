<?php
// molte delle funzioni seguenti sono già state dscritte in index.php
/*
Questa pagina mostra gli articoli del nostro miniblog
legge da DB e presenta tutti i record di tipo post
*/
require_once('functions.php'); // ormai sapete cosa fa
?>
<?php
// stabilisco una connessione con il DB
$link = mysqli_connect("localhost", "utentecorsophp", "password", "corsophp");
// gestisco gli errori di connessione
if (!$link) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}
// stampo un messaggio per rendere esplicito il risultato, è possibile nasconderlo
echo "Success: A proper connection to MySQL was made! The my_db database is great." . PHP_EOL;
echo "Host information: " . mysqli_get_host_info($link) . PHP_EOL;

/* scrivo una query (interrogazione) di SELEZIONE per recuperare tutti i record di tipo post...
 per farlo dovrete aver creato una tabella di nome "posts", in cui saranno presenti colonne
 dal titolo corrispondente alle parole che vedete separate da virgola (id, postType, datecreation, title, content)
 
 un metodo veloce per selezionare tutte le colonne è usare il carattere jolly *
 
 SELECT * FROM posts WHERE postType='post' ORDER BY datecreation DESC
 
 in MAIUSCOLO sono scritti i comandi di MYSQL (solo per distinguerli).
 
 Le query qui sopra significa:
 SELEZIONARE tutte le colonne DALLA TABELLA posts, DOVE (qui inizia la parte delle condizioni
 che mi permetterà di filtrare i risultati, non li vorrete mica tutti!)... dicevo
 DOVE il valore della colonna postType è uguale a "post",
 il risultato ORDINATO per la data di creazione in verso DISCENDENTE (dal più grande al più piccolo)
 che per le date significa dalla più recente alla più vecchia... per le lettere dalla Z ai numeri
 (si perchè quando un numero fa parte di una stringa esso ha la precedenza rispetto alle lettere)
 
 
 */

$query="SELECT id, postType, datecreation, title, content FROM posts
        WHERE postType='post' 
        ORDER BY datecreation DESC";

/* eseguo la query con la funzione mysqli_query e salvo il risultato in una variabile $result
    la funzione accetta due parametri:
    il primo ($link) è l'oggetto che rappresenta la connessione con il DB, il secondo è la query
    con cui viene interrogato il DB
*/
$result=mysqli_query($link,$query);/*i risultati dell'interrogazione 
vengono salvati in un oggetto di tipo risorsa di database ed assegnati alla 
variabile $result*/

/*per il momento non farò altre operazioni sul DB
 tutto quello che mi serviva è stato salvato in $result
*/

 
mysqli_close($link);// finito le operazioni chiudo la connessione
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
                    echo'<a href="login.php?logout=yes">Logout</a>';
        }
        
            // il menu ormai lo conoscete (vedi function.php)
            echo buildmenu('ilMiomenu',$array);
            ?>
        <div class="content">
            <?php
            // qui cicliamo la risorsa di database e stampiamo titolo, excerpt e bottone more
            /*
             while è uno dei costrutti per controllare un ciclo
             il ciclo continuerà a ripetersi finchè l'espressione tra parentesi rimane vera
             http://php.net/while
             
             nel nostro caso finchè $row avrà un valore... cioè finche la funzione mysqli_fetch_assoc
             restituirà qualcosa
             
             la funzione mysqli_fetch_assoc preleva (fetch) il valore di una riga di
             una risorsa di database ($result, ve lo ricordate), la restituisce sottoforma di 
             array associativo (dove la chiave corrisponde al nome della colonna della tabella che 
             è stata interrogata ed il valore corrisponde al valore del campo della tabella) e fa
             avanzare il puntatore (una variabile che tiene il conto delle righe presenti nella risorsa)
             alla riga successiva... se non trova righe successive mysqli_fetch_assoc restituisce NULL
             il nulla appunto e la condizione del while quindi fallisce.
             
             Attenzione, se eseguite due while sulla medesima risorsa (lo stesso $result nella medisima pagina o esecuzione di codice) dovrete resettare il puntatore della risorsa, altrimenti il secondo while non troverà nulla.
             Dopo il primo while resettate il puntatore con la funzione
             mysqli_data_seek ($result , 0);
             dove $result sarà la varibile in cui è stata salvata la risorsa e 0 la prima riga della risorsa
             
            */
            while($row = mysqli_fetch_assoc($result)){
                echo'<article>';
                echo '<h2>'.$row['title'].'</h2>';
                /*['title'] corrisponde al valore della colonna title in questa riga della risorsa*/
                echo '<div class="excerpt">'.excerpt($row['content'],'140').'</div>';
                /*['content'] corrisponde al valore della colonna content in questa riga della risorsa 
                    la funzione excerpt è spiegata in function.php
                    */
                
                echo '<a href="single.php?postid='.$row['id'].'" class="button more">More</a>';
                //['id']corrisponde al valore della colonna id in questa riga della risorsa 
                /*
                mi serve per costruire un link alla pagina dettaglio, nell'url passeremo
                il parametro postid che verrà usato dalla pagina single.php
                il parametro verrà usato in una query per individuare un record preciso
                nella tabella posts
                */
               echo'</article>';     
            }

            ?>
        </div>
    
    </body>
    
</html>

<?php
/*
    Ora proseguite con la pagina single.php
*/
?>