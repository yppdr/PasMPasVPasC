<?php


class ViewController extends Controller {


    public function test(){
        return $this->get;
    }

    /**
     * Passe des argument dans un array
     */
    public function home(){
     $this->render("home");
    }



}