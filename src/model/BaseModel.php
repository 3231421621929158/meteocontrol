<?php

namespace meteocontrol\vcomapi\model;

use JsonSerializable;

abstract class BaseModel implements JsonSerializable {

    /**
     * @param array $data
     * @return $this
     */
    public static function deserialize(array $data) {
        $object = new static();
        foreach ($data as $key => $value) {
            if (property_exists($object, $key)) {
                $object->{$key} = static::getPhpValue($value);
            }
        }
        return $object;
    }

    /**
     * @param array $decodedJsonArray
     * @return array
     */
    public static function deserializeArray(array $decodedJsonArray) {
        $objects = [];
        foreach ($decodedJsonArray as $item) {
            $objects[] = static::deserialize($item);
        }
        return $objects;
    }

    /**
     * @return array
     */
    public function jsonSerialize() {
        $values = get_object_vars($this);

        foreach ($values as $key => $value) {
            if ($value instanceof \DateTime) {
                $values[$key] = $this->serializeDateTime($value, $key);
            }
        }

        return $values;
    }

    /**
     * @param \DateTime $dateTime
     * @param null|string $key
     * @return string
     */
    protected function serializeDateTime(\DateTime $dateTime, $key = null) {
        return $dateTime->format(\DateTime::ATOM);
    }

    /**
     * @param string | int | float | null $value
     * @return \DateTime | string | int | float | null
     */
    protected static function getPhpValue($value) {
        if (self::isRFC3339DateString($value)) {
            return \DateTime::createFromFormat(\DateTime::RFC3339, $value);
        } else {
            return $value;
        }
    }

    /**
     * @param string $dateString
     * @return bool
     */
    private static function isRFC3339DateString($dateString) {
        return \DateTime::createFromFormat(\DateTime::RFC3339, $dateString);
    }
}
