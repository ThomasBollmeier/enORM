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
namespace tbollmeier\enorm\dbapi;


abstract class FieldOperator
{

    const EQ = 1; // equal
    const NE = 2; // not equal
    const GT = 3; // greater than
    const GE = 4; // greater or equal
    const LT = 5; // less than
    const LE = 6; // less or equal
    const CP = 7; // contains pattern
    const NP = 8; // does not contain pattern

}