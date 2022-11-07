<?php

namespace App\Type;

abstract class PassengerTypeEnum
{
    public const TYPE_STUDENT = 'student';
    public const TYPE_ADULT = 'adult';
    public const TYPE_PENSIONER = 'pensioner';
    /**
     * @var string[]
     */
    protected static $typeName = [
        self::TYPE_STUDENT => 'Student',
        self::TYPE_ADULT => 'Adult',
        self::TYPE_PENSIONER => 'Pensioner',
    ];

    /**
     * @param $typeShortName
     * @return string
     */
    public static function getTypeName($typeShortName)
    {
        if (!isset(static::$typeName[$typeShortName])) {
            return "Unknown type ($typeShortName)";
        }

        return static::$typeName[$typeShortName];
    }

    public static function getAvailableTypes()
    {
        return [
            self::TYPE_STUDENT,
            self::TYPE_ADULT,
            self::TYPE_PENSIONER
        ];
    }
}
