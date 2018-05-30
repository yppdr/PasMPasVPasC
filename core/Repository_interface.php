<?php

/**
 * Interface Repository_interface
 */


interface Repository_interface {

    public function getAll();

    public function getAllBy($filter);

}