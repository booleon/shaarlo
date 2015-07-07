<?php
// Fonctions qui gèrent tout ce qui touchent aux urls du site urlmanager, urlencoding, redirection...

// Fonction qui redirige l'utilisateur sur la page de connection s'il doit etre connecté
function getUrlHost() {
    return "https://".$_SERVER['HTTP_HOST'];
}

// Fonction qui redirige l'utilisateur sur la page de connection s'il doit etre connecté
function getUrlCourante() {
    return getUrlHost().$_SERVER['REQUEST_URI'];
}
// Fonction qui redirige l'utilisateur sur la page de connection s'il doit etre connecté
function getRedirectUrl() {
    $urlCourante = getUrlCourante();
    return urlencode("_" . $urlCourante);
}



// Rend un nom plus beau 
function normalize($nom) {
    $nom = strtolower($nom);
    $nom = str_replace(array(' ', '_'), '-', $nom);
    $nom = str_replace(array("'", '"'), '', $nom);
    $nom = str_replace(array('?', '!', ':', ','), '', $nom);
    $nom = wd_remove_accents($nom);

    return $nom;
}

function redirection404() {
    redirige('/404');
}

// Force la redirection html
function redirige($url) {
    header('Location:'.$url);
    die('<meta http-equiv="refresh" content="0;URL='.$url.'">');
}
// alias vers redirige
function redirection($url) {
    return redirige($url);
}
// alias vers redirige
function redirect($url) {
    return redirige($url);
}
// alias vers redirige
function redir($url) {
    return redirige($url);
}


// -----------------------------------------
// Ajoute/Modifie un parametre à un URL.
// -----------------------------------------
function ajouterParametreGET($url, $paramNom, $paramValeur){
    $urlFinal = "";
    if($paramNom==""){
        $urlFinal = $url;
    }else{
        $t_url = explode("?",$url);
        if(count($t_url)==1){
            // pas de queryString
            $urlFinal .= $url;
            if(substr($url,strlen($url)-1,strlen($url))!="/"){
                $t_url2 = explode("/",$url);
            if(preg_match("/./",$t_url2[count($t_url2)-1])==false){
                $urlFinal .= "/";
                }
            }
            $urlFinal .= "?".$paramNom."=".$paramValeur;
        } elseif(count($t_url)==2){
            // il y a une queryString
            $paramAAjouterPresentDansQueryString = "non";
            $t_queryString = explode("&",$t_url[1]);
            foreach($t_queryString as $cle => $coupleNomValeur){
            $t_param = explode("=",$coupleNomValeur);
            if($t_param[0]==$paramNom){
            $paramAAjouterPresentDansQueryString = "oui";
            }
        }
        if($paramAAjouterPresentDansQueryString=="non"){
            // le parametre à ajouter n'existe pas encore dans la queryString
            if(!is_null($paramValeur)) {
                $urlFinal = $url."&".$paramNom."=".$paramValeur;
            } else {
                $urlFinal = $url;
            }
        } elseif($paramAAjouterPresentDansQueryString=="oui"){
                // le parametre à ajouter existe déjà dans la queryString
                // donc on va reconstruire l'URL
                $urlFinal = $t_url[0]."?";
                foreach($t_queryString as $cle => $coupleNomValeur){
                    $t_coupleNomValeur = explode("=",$coupleNomValeur);
                    if($t_coupleNomValeur[0]==$paramNom){
                        if(!is_null($paramValeur)) {
                            if($cle > 0){
                                $urlFinal .= "&";
                            }
                            $urlFinal .= $paramNom."=".$paramValeur;
                        }
                    }else{
                        if($cle > 0){
                            $urlFinal .= "&";
                        }
                        $urlFinal .= $t_coupleNomValeur[0]."=".$t_coupleNomValeur[1];
                    }
                }
            }
        }
    }
    return $urlFinal;
}

// Ajoute un tableau de paramètres
function ajouterParametresGET($url, $nomsvaleurs){
    foreach($nomsvaleurs as $nom => $valeur) {
        $url = ajouterParametreGET($url, $nom, $valeur);
    }
    
    return $url;
}

/**
* Affiche de manière protégée les éventuelles balises html
*/
function eh($string) {
    echo htmlentities($string);
}

