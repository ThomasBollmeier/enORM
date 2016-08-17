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


class ValueFactory
{

    public static function createInitValue(Type $type)
    {
        $category = $type->getCategory();

        switch ($category) {
            case Type::INTEGER:
                return new IntegerValue();
            case Type::BOOLEAN:
                return new BooleanValue();
            case Type::DECIMAL:
                return new DecimalValue($type);
            case Type::VARCHAR:
                return new VarCharValue($type);
            case Type::STRING:
                return new StringValue();
            case Type::DATE:
                return new DateValue();
            case Type::TIME:
                return new TimeValue();
            default:
                return null;
        }

    }

}
