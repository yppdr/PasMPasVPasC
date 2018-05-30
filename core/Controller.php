<?php
/**
 * Class Controller : Elle fourni les mecanismes necessire au fonctionnement a la
 * couche intemediere
 */

abstract class Controller{

    protected $post;
    protected $get;


    /**
     * Controller constructor. Init des propiétés
     */
    public function __construct(){

        $this->get = $_GET;
        $this->post = $_POST;

    }

    

    /**
     * @param string $viewPath : chamin du fichier vue
     * @param array $datas : datas a injecter dabs la vue
     *
     */
    final protected function render($viewPath, $datas=null){
        //$keys = array_keys($datas);

        foreach ($datas as $k => $v){
            $$k = $v;
        };
        include "./views/". $viewPath .".php";

    }

}