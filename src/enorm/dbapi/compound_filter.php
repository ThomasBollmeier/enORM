<?php

namespace enorm\dbapi;

require_once 'filter.php';

class CompoundFilter extends Filter
{

    public function __construct($filter1, $filter2)
    {

        parent::__construct();

        $this->elements = array($filter1, $filter2);

    }

    public function add($filter)
    {

        array_push($this->elements, $filter);

    }

    public function getElements()
    {

        return $this->elements;

    }

    protected $elements;

}