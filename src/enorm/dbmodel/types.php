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

abstract class Type {

    // Type categories:
    const INTEGER = 1;
    const DECIMAL = 2;
    const BOOLEAN = 3;
    const VARCHAR = 4;
    const STRING = 5;
    const DATE = 6;
    const TIME = 7;

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
	
		$this->init(Type::INTEGER);
	
	}

	private static $single = null;

}

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

class BooleanType extends Type {

	public static function get() {

		if (!self::$single) self::$single = new BooleanType();

		return self::$single;

	}

	private function __construct() {
	
		$this->init(Type::BOOLEAN);
	
	}

	private static $single = null;

}

class VarCharType extends Type {

	public function __construct($length) {

		$this->init(Type::VARCHAR);

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
	
		$this->init(Type::STRING);
	
	}

	private static $single = null;

}

class DateType extends Type {

	public static function get() {

		if (!self::$single) self::$single = new DateType();

		return self::$single;

	}

	private function __construct() {
	
		$this->init(Type::DATE);
	
	}

	private static $single = null;

}

class TimeType extends Type {

	public static function get() {

		if (!self::$single) self::$single = new TimeType();

		return self::$single;

	}

	private function __construct() {
	
		$this->init(Type::TIME);
	
	}

	private static $single = null;

}
