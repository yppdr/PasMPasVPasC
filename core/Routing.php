<?php


class Routing {

    private $routing;

    function __construct() {
        $this->routing = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . './config/routing.json'), true);
    }

    public function test() {
        $a = $this->getRouting();
        if (empty($a)) {
            echo "vide";
        } else {
            var_dump($a);
        }
    }

    function getRouting() {
        return $this->routing;
    }

    function setRouting($routing) {
        $this->routing = $routing;
    }

    /*
     * Fonction sur laquelle repose tout le principe de routage en fonction de l'uri
     * et du fichier de config du routage, comparaison de l'uri & des routes afin de
     * retourner le controller adéquat ainsi que la méthode et les arguments demandé
     * lors de la requête
     */
    public function getController() {
        $routes = $this->getRouting();
        $uri = explode('/', $_SERVER['REQUEST_URI']);
        //unset permet de retirer un élément dans un tableau
        unset($uri[0]);
        //création d'un tableau afin d'y récupéré les arguments présent dans la requête
        $args = array();

        foreach ($routes as $key => $value) {
//on explode la route courante de la boucle afin de proceder plus simplement entre deux tableaux
            $routeExplo = explode("/", $key);
            unset($routeExplo[0]);

            /* si la taille entre l'uri et la route son similaire ET que chaque element dans les tableaux
             * sont identique on passe à la suite sinon pas la peine d'effectuer le traitement.
             */

            if ($this->laTailleCaComptePas($routeExplo, $uri) && $this->compare($routeExplo, $uri, $args)) {

                if ($this->checkValue($value)) {
                    //var_dump afin d'avoir un retour sur le navigateur ou Postman
                    $this->invoke(array($value => $args));

                }
            }
        }
    }

    /*
     * Méthode qui va traité le résultat de tous nos traitements effectués afin
     * de créé l'objet qui va bien puis l'appel de la méthode voulue et si ils existent
     * avec les arguments récupérés.
     */
    public function invoke($value) {

        $keys = array_keys($value);
        $elements = explode(":", $keys[0]);
        $object = new $elements[0]();

        return call_user_func_array(array($object, $elements[1]), $value[$keys[0]]);

    }
    /*
     * La méthode CheckValue permet de vérifier si la valeur passé en argument est un array ou non
     * Si c'est une array on check si la clé (qui est un verbe HTTP) est présente et on modifie la valeur de $value
     * pour retourner la valeur souhaité
     *
     * la présence de l'esperluette (&) permet d'effectuer un passage par référence et non
     * par copie autrement dit on modifie directement la variable en dehors de cette
     * méthode
     */
    public function checkValue(&$value) {
        if (is_array($value)) {
//Si la clé est présente dans notre JSON alors on modifie la valeur
//$value[$_SERVER['REQUEST_METHOD']] = "GET":"DaoUser:retrieve" par exemple
            if (isset($value[$_SERVER['REQUEST_METHOD']])) {
                $value = $value[$_SERVER['REQUEST_METHOD']];
                return $value;
            } else {
                return NULL;
            }
        } else {
            return $value;
        }
    }
    /*
     * Vérification de la taille de deux tableaux passé en argument
     */
    public function laTailleCaComptePas($a, $b) {
        if (count($a) === count($b)) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * Permet de comparer les éléments de deux tableaux passé en arguments et
     * si la correspondance est avérée on ajoute le ou les éléments variables inseré
     * dans l'uri à un tableau.
     *
     * la présence de l'esperluette (&) permet d'effectuer un passage par référence et non
     * par copie autrement dit on modifie directement la variable en dehors de cette
     * méthode
     *
    */
    public function compare($a, $b, &$args) {
        if ($this->laTailleCaComptePas($a, $b)) {
            for ($i = 0; $i <= count($a); $i++) {
                if ($a[$i] !== $b[$i]) {
                    if (strpos($a[$i], "$") === 0) {
                        array_push($args, $b[$i]);
                    } else {
                        return false;
                    }
                }
            }
            return true;
        }
    }

}