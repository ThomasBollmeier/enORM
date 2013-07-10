<?php

namespace enorm\dbapi;

require_once 'filter.php';

class Negation extends Filter
{

    public function __construct($filter)
    {

        parent::__construct();

        $this->filter = $filter;

    }

    public function getFilter()
    {

        return $this->filter;

    }

    private $filter;

}
