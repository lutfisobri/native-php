<?php
namespace Riyu\Foundation\Database\Builder;

use Riyu\Foundation\Database\Builder\Schema\CallableSchema;

/**
 * Definition class
 * 
 * This class is used to define the schema of the table.
 * 
 * @method $this date($name)
 * @method $this dateTime($name)
 * @method $this time($name)
 * @method $this timestamp($name)
 * @method $this year($name)
 * 
 * @method $this tinyInt($name, $length = 4)
 * @method $this smallInt($name, $length = 6)
 * @method $this mediumInt($name, $length = 9)
 * @method $this int($name, $length = 11)
 * @method $this bigInt($name, $length = 20)
 * @method $this decimal($name, $length = 10, $decimal = 0)
 * @method $this float($name, $length = 10, $decimal = 0)
 * @method $this double($name, $length = 10, $decimal = 0)
 * @method $this bit($name)
 * @method $this boolean($name)
 * @method $this serial($name) * 
 * 
 * @method $this char($name, $length = 255)
 * @method $this varchar($name, $length = 255)
 * @method $this tinyText($name)
 * @method $this text($name)
 * @method $this mediumText($name)
 * @method $this longText($name)
 * @method $this binary($name, $length = 255)
 * @method $this varbinary($name, $length = 255)
 * @method $this tinyBlob($name)
 * @method $this blob($name)
 * @method $this mediumBlob($name)
 * @method $this longBlob($name)
 * @method $this enum($name, array $values)
 * @method $this set($name, array $values)
 * 
 * @method $this geometry($name)
 * @method $this point($name)
 * @method $this linestring($name)
 * @method $this polygon($name)
 * @method $this multipoint($name)
 * @method $this multilinestring($name)
 * @method $this multipolygon($name)
 * @method $this geometrycollection($name)
 * 
 * @method $this json($name)
 * 
 * @package Riyu\Foundation\Database\Builder
 */
class Definition extends CallableSchema
{
    public function getAttributes(): array
    {
        return $this->attributes;
    }
}