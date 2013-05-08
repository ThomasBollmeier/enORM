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

// Type categories:
define("enorm\dbmodel\TYPE_INTEGER", 1);
define("enorm\dbmodel\TYPE_DECIMAL", 2);
define("enorm\dbmodel\TYPE_BOOLEAN", 3);
define("enorm\dbmodel\TYPE_VARCHAR", 4);
define("enorm\dbmodel\TYPE_STRING", 5);
define("enorm\dbmodel\TYPE_DATE", 6);
define("enorm\dbmodel\TYPE_TIME", 7);

abstract class Type {

	public function getCategory() {

		return $this->category;

	}

	protected function init($category) {

		$this->category = $category;

	}

	private $category;

}

class IntegerType extends Type {

	public static function get() {

		if (!self::$single) self::$single = new IntegerType();

		return self::$single;

	}

	private function __construct() {
	
		$this->init(TYPE_INTEGER);
	
	}

	private static $single = null;

}

class DecimalType extends Type {

	public function __construct($length, $digits) {

		$this->init(TYPE_DECIMAL);
		
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

class BooleanType extends Type {

	public static function get() {

		if (!self::$single) self::$single = new BooleanType();

		return self::$single;

	}

	private function __construct() {
	
		$this->init(TYPE_BOOLEAN);
	
	}

	private static $single = null;

}

class VarCharType extends Type {

	public function __construct($length) {

		$this->init(TYPE_VARCHAR);

		$this->length = $length;

	}

	public function getLength() {

		return $this->length;

	}

	private $length;

}

class StringType extends Type {

	public static function get() {

		if (!self::$single) self::$single = new StringType();

		return self::$single;

	}

	private function __construct() {
	
		$this->init(TYPE_STRING);
	
	}

	private static $single = null;

}

class DateType extends Type {

	public static function get() {

		if (!self::$single) self::$single = new DateType();

		return self::$single;

	}

	private function __construct() {
	
		$this->init(TYPE_DATE);
	
	}

	private static $single = null;

}

class TimeType extends Type {

	public static function get() {

		if (!self::$single) self::$single = new TimeType();

		return self::$single;

	}

	private function __construct() {
	
		$this->init(TYPE_TIME);
	
	}

	private static $single = null;

}

?>