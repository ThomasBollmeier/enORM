<?php

namespace enorm\dbapi;

require_once 'compound_filter.php';

class Conjunction extends CompoundFilter
{

    public function __construct($filter1, $filter2)
    {

        parent::__construct($filter1, $filter2);

    }

}