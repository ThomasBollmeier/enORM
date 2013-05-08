<?php
/*
 * Copyright 2013 Thomas Bollmeier <tbollmeier@web.de>
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

namespace enorm\dbmodel;

require_once 'dbmodel/types.php';

interface Value {

	public function getType();

}

class SimpleValue implements Value {

	public function __construct($content, $type) {

		$this->content = $content;
		$this->type = $type;

	}

	public function getContent() {

		return $this->content;

	}

	public function getType() {
		
		return $this->type;

	}

	private $content;
	private $type;

}

class VarCharValue extends SimpleValue {

	public function __construct($content, $maxlen) {

		$tmp = substr($content, 0, $maxlen);

		parent::__construct($tmp, new VarCharType($maxlen));

	}

}

class ValueFactory {

	public static function createInteger($ival) {

		return new SimpleValue($ival, IntegerType::get());

	}

	public static function createBoolean($bval=TRUE) {

		return new SimpleValue($bval, BooleanType::get());

	}

	public static function createDecimal($decval, $length=0, $digits=0) {

		return new SimpleValue($decval, new DecimalType($length, $digits));

	}

	public static function createText($textval, $maxlen=0) {

		return !$maxlen ? 
			new SimpleValue($textval, StringType::get()) : 
			new VarCharValue($textval, $maxlen); 

	}

}

?>