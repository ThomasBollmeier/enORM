<?php
namespace enorm\dbmodel;

class Field {

    public function __construct($name, $type, $properties = array("nullAllowed" => FALSE)) {

        $this->name = $name;
        $this->type = $type;
        $this->properties = $properties;

    }

    public function getName() {

        return $this->name;

    }

    public function getType() {

        return $this->type;

    }

    public function isNullAllowed() {

        return array_key_exists("nullAllowed", $this->properties) ?
            $this->properties["nullAllowed"] :
            FALSE;

    }

    private $name;
    private $type;
    private $properties;

}
