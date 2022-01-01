<?php
// IS RECEIVED SHORTCUT
if(isset($_GET['q'])) {
    // VARIABLE
    $shortcut = htmlspecialchars($_GET['q']);

    // IS A SHORCUT ? 
    $bdd = new PDO('mysql:host=localhost;dbname=raccourcisseur de lien;charset=utf8', 'root', '');
    $req = $bdd->prepare('SELECT COUNT(*) AS x FROM links WHERE shortcut = ?');
    $req->execute(array($shortcut));

    while($result = $req->fetch()) {
        if($result['x'] != 1){
            header('location: ../?error=true&message=Adresse url non connue');
            exit();
        }
    }

    // REDIRECTION
    $req = $bdd->prepare('SELECT * FROM links WHERE shortcut = ?');
    $req->execute(array($shortcut));

    while($result = $req->fetch()){
        header('location: '.$result['url']);
        exit();
    }
    
}

// IS SENDING A FORM
if(isset($_POST['url'])) {
    // VARIABLE
    $url = $_POST['url'];

    // VERIFICATION URL 
    if(!filter_var($url, FILTER_VALIDATE_URL)) {
        // PAS UN LIEN 
        header('location: ../?error=true&message=Adresse url non valide');
        exit();
    }
    // SHORTCUT
    $shortcut = crypt($url, rand());

    // VERIFIER SI L'URL A DEJA ETE PROPOSER 
    $bdd = new PDO('mysql:host=localhost;dbname=raccourcisseur de lien;charset=utf8','root', '');
    $req = $bdd->prepare('SELECT COUNT(*) AS x FROM links WHERE url = ?');
    $req->execute(array($url));

    while($result = $req->fetch()){

        if($result['x'] != 0){
            header('location: ../?error=true&message=Adresse déjà raccourcie');
            exit();
        }
    }

    // SI TOUT EST OK ON ENVOIE TOUT DANS NOTRE BDD
    $req = $bdd->prepare('INSERT INTO links(url, shortcut) VALUES(?, ?)');
    $req->execute(array($url, $shortcut));

    header('location: ../?short='.$shortcut);
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="design/default.css">
    <link rel="icon" type="image/png" href="pictures/favico.png">
    <title>Raclink</title>
</head>
<body>
    <!-- SECTION PRESENTATION -->
    <section id="hello">

        <!-- CONTAINER -->
        <div class="container">
            <header>
                <img src="pictures/logo.png"  id="logo" alt="logo">
            </header>

            <!-- VP -->
            <h1> Une Url longue ? Raccourcissez-là ! </h1>
            <h2> Un lien plus court est plus facile à mémoriser ! </h2>

            <!-- FORMULAIRE -->
            <form method="post" action="../">
                <input type="url" name="url" placeholder="Coller votre lien à raccourcir">
                <input type="submit" value="Raccourcir">
            </form>

            <?php if(isset($_GET['error']) && isset($_GET['message'])) { ?>
                
                <div class="center">
                    <div id="result">
                        <b><?php echo htmlspecialchars($_GET['message']); ?></b>
                    </div>
                </div>
            <?php }  else if(isset($_GET['short'])) {
                ?>
                 <div class="center">
                    <div id="result">
                        <b>URL RACCOURCIE : http://localhost/?q=<?php echo htmlspecialchars($_GET['short']); ?></b> 
                    </div>
                </div>
            <?php } ?>
        </div>
    </section>

        <!-- SECTION 2 -->
        <section id="brands">
 
            <!-- CONTAINER 2 -->
            <div class="container">
                <h3>Ces marques nous font confiances</h3>
                <img src="pictures/1.png" alt="1" class="picture">
                <img src="pictures/2.png" alt="1" class="picture">
                <img src="pictures/3.png" alt="1" class="picture">
                <img src="pictures/4.png" alt="1" class="picture">
            </div>
        </section>

        <!-- FOOTER -->
        <footer>
            <img src="pictures/logo-footer.png" alt="logo-footer" id="logo"><br>
            2022 © RacLink's<br>
            <a href="#">Contact</a> - <a href="#">A propos</a>
        </footer>
</body>
</html>