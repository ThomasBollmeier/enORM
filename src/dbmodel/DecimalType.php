<?php
/*
 * Copyright 2013-2016 Thomas Bollmeier <entwickler@tbollmeier.de>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 */

namespace tbollmeier\enorm\dbmodel;


class DecimalType extends Type {

    public function __construct($length, $digits) {

        $this->init(Type::DECIMAL);

        $this->length = $length;
        $this->digits = $digits;

    }

    public function getDigits() {
        return $this->digits;
    }

    public function getLength() {
        return $this->length;
    }

    private $length;
    private $digits;

}