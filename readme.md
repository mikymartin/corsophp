# DOCUMENTAZIONE

Descrivo rapidamente la sequenza di lettura del materiale contenuto in questi file.

- Prima leggere il file index.php e function.php
- Poi il file blog.php
- Poi il file single.php
- Poi il file login.php
- poi il file admin/admin.php
- poi il file admin/adminpost.php
- poi il file admin/updateForm.php

Troverete i file estesamente commentati.

Il file function.php è cresciuto mano a mano che il progetto si è evoluto,
non è necessario che lo leggiate interamente, potete anche procedere un pò 
alla volta concentrandovi solo sulle funzioni utilizzate nelle singole pagine
che ho indicato in sequenza.

## Esercizi
Troverete degli esercizi da fare, modifiche di diversa entità, dalla creazione di nuove variabili,
alla creazione di nuove query e costruzione di intere pagine

Volutamente non ho stilizzato la maggior parte delle pagine, lo lascio a voi per fare pratica con i css

## scss??
Troverete anche un file .scss... cosè?!
E' un file scritto per il preprocessore SASS 

pagina ufficiale https://sass-lang.com/documentation/file.SASS_REFERENCE.html

la guida in italiano https://www.html.it/guide/guida-sass/

Il file SCSS è sempre un file css e ne conserva medesima sitassi ma è possibile introdurre 
alcune funzionalità molto potenti:

varibili, funzioni, annidamenti...

può essere imparato gradualmente, non è necessario usare tutte le sue potenzialità subito.

Iniziate con le variabili e fatemi sapere come vi trovate

Per poterlo usare è necessario installare SASS sul proprio computer:
https://sass-lang.com/install

e poi configurare netbeans per usarlo

## I miei riferimenti:

[me]: https://avatars0.githubusercontent.com/u/1137971?s=460&v=4 "Michele Martinello"

- e-mail: mikymartin@gmail.com
- cell/whatsapp: +393472738963

# How TO

Vi suggerisco questa guida: https://www.html.it/guide/guida-php-e-mysql-pratica/

## Connettersi ad un DB con PHP e chiudere la connessione

```php
$link = mysqli_connect("localhost", "utentecorsophp", "password", "corsophp");

// gestione errori di connessione, vengono stampati solo se la connessione fallisce
// il ! significa negazione quindi !$link si legge come "se $link non c'è"
if (!$link) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}
```
dopo aver fatto tutto ciò che serve nel DB chiudete la connessione

```php
mysqli_close($link);
```
## Eseguire una query in una tabella del DB

```php
$query="SELECT id, postType, datecreation, title, content FROM posts
        WHERE postType='post' 
        ORDER BY datecreation DESC";

/* eseguo la query con la funzione mysqli_query e salvo il risultato in una variabile $result
    la funzione accetta due parametri:
    il primo ($link) è l'oggetto che rappresenta la connessione con il DB, il secondo è la query
    con cui viene interrogato il DB
*/
$result=mysqli_query($link,$query);
// il risultato viene salvato in $result
```
## Accedere ai dati del risultato di una query

Supponendo di usare la query precedente utilizzerete un ciclo [while] ( http://php.net/while)
il ciclo while si esegiurà finche l'espressione tra parentesi è vera, nel caso specifico finchè
$row avrà un valore, cioè finchè mysqli_fetch_assoc($result) 
troverà una riga (detta anche recordset) in $result.
Ogni volta che viene trovata una riga il puntatore del ciclo while viene fatto avanzaredi uno,
procedendo così fino alla fine della risorsa $result

```php
while($row = mysqli_fetch_assoc($result)){
    $id=$row['id'];
    $postType=$row['postType'];
    $datadiCreazione=$row['datecreation'];
    $titolo=$row['title'];
    $titolo=$row['content']; 
    // qui dovrete fare qualcosa, tipo stampare (echo) questi valori
    // magari insieme a dei tag HTML per dare una struttura 
    // alla presentazione di questi dati
}
```
**NON potete fare due cicli while di fila** sulla medesima risorsa ($result), 
senza aver prima resettato il puntatore di $result.
Quindi se dovrete ripetere il ciclo while su $result , dopo il primo ciclo resettate il puntatore con il comando
```php
mysqli_data_seek ($result , 0);
```

## Salvare i dati in una tabella del DB
Prima di tutto dovrete connetervi al DB poi potrete eseguire
- una query di inserimento  (se dovete inserire per la prima volta un dato, o una serie di dati,  non ancora esistenti)
- una query di aggiornamento (se dovete aggiornare una dato, o una serie di dati,  già esistenti)


### Query di inserimento
```php
    $query = "INSERT INTO posts ('id','datecreation','title','content') VALUES ('', '','$title','$content')";
    // nella prima coppia parentesi vengono dichiarati i nomi dei campi della tabella
    // nella seconda coppia di parentesi i valori che verranno inseriti
    // notate i due apici singoli '' come valori dei due primi campi
    //non verrà inserito nulla ma dovranno essere dichiarati comunque) perchè iò numero degli inserimenti
    // dovrà essere uguale al numero dei campi dichiarati e nel corrispondete ordine
    // OCCHIO SONO DUE APICI SINGOLI ' (quello sotto il punto di domanda)

```
### Query di aggiornamento

```php
    $queryupdate="UPDATE posts SET  title='".$title"', content='".$content."' WHERE id=".$id;
    // echo"QUERY AGGIORNAMENTO $queryupdate"; // se volete vederla a schermo
    mysqli_query($link,$queryupdate);
```