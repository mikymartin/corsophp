<?php
/*
Questa pagina è la pagina con cui realizzeremo il login all'area amministrativa del
nostro miniblog

il FORM invierà i dati NELLA STESSA PAGINA IN CUI E' STATO CREATO, IN QUESTA, quindi tutta
la logica che fa la verifica dovrà essere fatta prima di arrivare a stampare il form.

Questo verrà mostrato solo se non si è ancora loggati

se l'utente è loggato stampa il nome dell'utente ed il pulsante log out

Una particolarità del FORM è quella di inviare dati, i dati che esso invia vengono
salvati in un array associativo superglobale (ancora? Si ancora) $_POST
*/

require_once('functions.php');// ancora? Che palle...

/*
Se viene inviato il parametro logout=yes distruggo il cookie
lo faccio qui perchè setcookie va fatto prima di qualsiasi output a schermo
*/
if($_GET['logout']=='yes'){
    setcookie("LOGIN", "", time()-3600);
    // al prossimo ricaricamento di pagina il cookie non esiterà più
    // è necessario forzare il ricaricamento della pagina per non vederlo più
    
    Header('Location: login.php');
}


?>

<?php
// ## ESERCIZIO ##, trasformare la procedura di connessione in funzione, è sempre la stessa in tutte le pagine
// connessione al DB
$link = mysqli_connect("localhost", "utentecorsophp", "password", "corsophp");
// gestione degli errori
if (!$link) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}

echo "Success: A proper connection to MySQL was made! The my_db database is great." . PHP_EOL;
echo "Host information: " . mysqli_get_host_info($link) . PHP_EOL;

/* 
dovrà essere creata una tabella per immagazzinare i dati degli utenti:
nome: users
campi: 
    idUtente (primary,autoincrement),
    username (potrebbe essere la mail, tutte le mail sono univoche, non esiste una mail uguale all'altra),
    password (md5)
    active (set true or false o simili per indicare che è attivo o meno)
    
Controllerò si esiste un utente con le credenziali inviate
Se esiste creerò un cookies (http://php.net/manual/en/features.cookies.php) che verrà
salvato nel browser

Se visitando il miniblog il cookies già esiste allora è un utente autenticato e mostro
quindi il pulsante di log out

Se visitando il miniblog il cookies non esiste dovrò effettuare la procedura di login


I Cookies sono un modo per salvare i dati in un browser ed identificare gli utenti che ritornano
in un sito.

I cookies si attivano con la funzione set_cookies (vedi functions.php)
PHP salva i cookie che dichiariamo in un array associativo superglobal $_COOKIE, accederemo quindi
al valore di un cookie tramite il suo nome es: $_COOKIE['ilnomedelmiocookie']
*/

/* 
Se il coookie NON esiste dovrò sicuramente eseguire la procedura di identificazione
Se esiste non sarà necessario farlo
*/
/* 
    a scopi didattici stampo gli array $_POST e $_COOKIE per vederne il contenuto
    attenzione al method del form, il defaul è GET, non POST, il secondo va esplicitamente
    dichiarato nel FORM
*/

echo'<pre>POST<br>';
print_r($_POST);
echo'</pre>';

echo'<pre>COOKIE<br>';
print_r($_COOKIE);
echo'</pre>';

if(!$_COOKIE['LOGIN']){
    /*
    Se il cookie non esiste (!$_COOKIE) esegui la procedura tra le graffe
    
    ricevo i dati dal form e li uso per costruire la query che identificherà un utente
    dalla coppia username e password
    */
    $userName=$_POST['uname']; // i valori andrebbero sanitizzati
    $myPassword=$_POST['psw'];
    
    $query="SELECT * FROM users WHERE username='$userName' AND password='$myPassword' AND active='1'";
    
    /*
    dove users è la tabella dove sono salvati gli utenti, username fa riferimento alla campo della colonna username, password al campo della colonna password, active fa riferimento al campo della colonna active
    il valore 1 è tra ' perchè il tipo di campo SET accetta come valori delle stringhe (e quindi vanno tra ')
    
    le relazioni che esprime un database relazionale come mysql si chiamano "tuple" e corrispondono ad
    una riga della tabella. Le tuple vengono anche comunemente chiamate record (di una tabella) oppure righe (row)
    */
    
    $result=mysqli_query($link,$query);
    
    // se ottengo una coincidenza (1 riga) allora ho trovato un utente
    if(mysqli_affected_rows($link )!=0){
        // trovato, recupero i dati e setto il cookie
            while($row = mysqli_fetch_assoc($result)){
                
                $ID_UTENTE=$row['username'];                
                if($_POST['remember']=='on'){
                    $TEMPO=2592000;  //Cookie attivo per 30 giorni
                }else{
                    $TEMPO=72000; //Cookie attivo per 2 ore
                }
                
                settaCookie($ID_UTENTE, $TEMPO);       
            }
        // al prossimo ricaricamento di pagina il cookie ESISTERA'
        // così forzo il ricaricamento
    
    Header('Location: login.php');
        
    }else{
       // se non trovo alcuna riga le credenziali sono sbagliate, dovrò stampare un messaggio
        
        // il messaggio verrà creato solo se ho effettivamente inviato il form
        if(($_POST['uname']!='')&&($_POST['psw'])){
            $errorMessage="<p class='error'>Le credenziali sono errate</p>"; 
        }
       
    }
    
}


mysqli_close($link);
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
        ?>
        
            <?php
            // il menu
            echo buildmenu('ilMiomenu',$array);
            ?>
        <div class="content">
            <?php
            echo"$errorMessage";
            //echo"$query";
            
            
                // il form verrà stampato solo se NON esiste il cookies
                // e se fallisce il login
                if(!$_COOKIE['LOGIN']||$errorMessage!=''){ // qui apro la graffa, la chiuderò alla fine del form
            ?>
            <form enctype="multipart/form-data" action="login.php" method="post">
            <div class="container">
            <div class="imgcontainer">
            <img src="img/img_avatar2.png" alt="Avatar" class="avatar">
            </div>    
            <label for="uname"><b>Username</b></label>
            <input type="text" placeholder="Enter Username" name="uname" required>

            <label for="psw"><b>Password</b></label>
            <input type="password" placeholder="Enter Password" name="psw" required>

            <button type="submit">Login</button>
            <label>
            <input type="checkbox" checked="checked" name="remember"> Remember me
            </label>
            </div>

            <div class="container" style="background-color:#f1f1f1">
            <button type="button" class="cancelbtn">Cancel</button>
            <span class="psw">Forgot <a href="#">password?</a></span>
            </div>
            </form>
            <?php
                // chiudo la graffa
                /*
                il php è fatto per lavorare mischiandosi all'html, non approfittatene troppo
                non è facile leggere una sequenza di graffe aperte e chiuse in questo modo
                */
                } else{
                    echo"<h2>Bentornato</h2><p>Ti sei già autenticato</p>";
                }
            
            ?>
        </div>
    
    </body>
    
</html>

