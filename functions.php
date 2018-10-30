<?php

$array=array(
    /*  Array degli elementi del menu 
    ogni record (riga) dovrà avere una etichetta ed una destinazione:
    'home' sarà l'etichetta che verrà piazzata tra <a href="..."> ETICHETTA </a>
    'index.php' sarà la destinazione del link che verrà generato con la funzione buildmenu
    ed apparirà quindi nella proprietà href del link 
    */
    'Home'=>'index.php',
    'il mio blog'=>'blog.php',
    'Login'=>'login.php'
    /*
    
    ## ESERCIZIO ##: fare in modo che l'array sia generato leggendo il DB usando i post di tipo page
    
    */
);

if($_COOKIE['LOGIN']){
    // se sono autenticato aggiungo una riga al menu principale, quella della pagina admin
    $array['admin'] = 'admin.php';
}

// questo array mi permette di configurare un ulteriore menu, quello della pagina di admin
$arrayAdmin=array(
    'Pagine'=>'#',
    'Post'=>'adminPost.php'
);

function buildmenu ($idwrapper, $array){
    /*  questa funzione construisce una lista di link, comunemente nota come menu
        prevede due parametri:
        
        $idwrapper, tipo stringa, il nome dell'id da assegnare al menu, guardando il codice vedrete che 
        viene assegnata al tag ul
        
        $array, tipo array, deve essere un array che contiente la lista dei link da utilizzare e le rispettive 
        etichette, leggete il commento relativo
        
        Queste varibili esistono solo all'interno di questa funzione e viceversa, qualsiasi variabile
        dichiarata all'esterno della funzione, normalmente, non viene vista all'interno della funzione (a meno che non venga passata come parametro della funzione) es:
        
        $a=5
        function stampa(){
        $b=10;
        echo $a;
        }
        
        echo $b;
        
        Non stamperanno nulla perchè $a non esiste all'interno della funzione e $b esiste solo
        all'interno di essa
        
        
        Un modo per andare oltre questo comportamento è quello di accedere alla variabile globale
        
        $a=5
        function stampa(){
        global a; // così posso accedere alla variabile $a
        $b=10;
        echo $a;
        }
        
        echo $b;
        
        cosa è la variabile globale?!?
        Ogni volta che dichiariamo una variabile in uno script essa viene aggiunta alla lista global
        delle variabili, qualsiasi $qualcosa dichiareremo verrà aggiunta... e sovrascritta in caso
        di omonimia, quindi occhio, non affidatevi troppo ad essa
        
        
    */
    
    $list="<ul id=\"$idwrapper\">";
    /* foreach è un costrutto per ciclare un array, continuerà a ciclare l'array finchè non arriverà
        alla fine poi resetterà il puntatore del ciclo all'inizio (con php 7),
        http://php.net/manual/en/control-structures.foreach.php  
        ad ogni ciclo verranno utilizzati chiave e valore di una riga (al primo ciclo verranno usati
        chiave e valore della prima riga dell'array, al ciclo successivo quelli della riga successiva,
        e così via fino al raggiungimento dell'ultima riga)
    */
    foreach($array as $etichetta=>$link){
        $list.='<li><a href="'.$link.'">'.$etichetta.'</a></li>';
        /* .= è un concatenamento e va letto così: 
           prendi quello che è già presente nella variabile a sinistra del punto 
           (nel nostro caso $list) e attaccagli quello che c'è a destra dell'uguale
           ora il valore di $list sarà
           <ul id="$idwrapper">
            <li><a href=".$link.">'.$etichetta.'</a></li>;
           
           essendo noi all'interno di un ciclo la parte .= verrà ripetuta fino al
           termine del ciclo, nel nostro caso fino a quando non saranno state lette 
           tutte le righe dell'array
           il risultato finale, nel caso ci siano 2 righe nell'array sarà
           <ul id="$idwrapper">
           <li><a href=".$link.">'.$etichetta.'</a></li>
           <li><a href=".$link.">'.$etichetta.'</a></li>
        */
    }
    $list.='</ul>';
    /* completiamo il codice html chiudendo la lista con il solito concatenamenento
        <ul id="$idwrapper">
        <li><a href=".$link.">'.$etichetta.'</a></li>
        <li><a href=".$link.">'.$etichetta.'</a></li>
        
        </ul>
    
    */
    return $list;
}


function excerpt($string,$length)
{
    /*  Questa funzione serve a crare un excerpt, una versione accorciata di un testo,
        un riassunto...
        prevede due parametri:
        
        $string, tipo stringa, corrisponde al testo da troncare
        $lenght, tipo numerico intero, corrisponde al numero di caratteri da conservare (la lunghezza del riassunto)
    */
    
    $str_len = strlen($string); // misura la lunghezza della stringa (cercate strlen nel manuale di php.net)
    $string = strip_tags($string); // rimuove i tag html che trova, per evitare che la troncatura spezzi a metà un tag (cercate strip_tags su php.net)

    if ($str_len > $length) {

        // substr preleva una parte della string $string, da 0 al numero inserito come $lenght -15
        $stringCut = substr($string, 0, $length-15);
        $string = $stringCut.'...'; // concateniamo i tre puntini (in inglese si chiamano ellipses) alla fine della stringa tagliata
    }
    return $string;
}


function settaCookie($ID_UTENTE, $TEMPO){
            // creaiamo un cookies, gli assegnamo in valore e definiamo quanto deve durare
            setcookie('LOGIN', $ID_UTENTE, time()+$TEMPO); // Identifica l'utente
      }


function printContent ($htmlTag,$class='',$content=''){
    if ($class!=''){
        $class='class="'.$class.'"';
    }
    $print="";
    $print.="<$htmlTag $class>";
    $print.= $content;
    $print.="</$htmlTag>";
    return $print;
}
?>