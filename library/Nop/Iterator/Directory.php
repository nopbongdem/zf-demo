<?php

class Nop_Iterator_Directory extends DirectoryIterator {

    public function getExtension() {
        return substr($this->getFilename(), strrpos($this->getFilename(), '.') + 1);
    }

    public function getFilenameWithoutExtension() {
        return substr($this->getFilename(), 0, strrpos($this->getFilename(), '.'));
    }

}