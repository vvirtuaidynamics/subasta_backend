<?php


namespace App\Enums;
/**
 *  ENUM UserStatus
 *  case ACTIVE = 'active';      //Activo y validado
 *  case INACTIVE = 'inactive';  //Inactivo y validado
 *  case PENDING = 'pending';    //Inactivo y no validado
 *  case DELETED = 'deleted';    //Eliminado.
 */
enum UserStatus: string
{

    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case PENDING = 'pending';
    case DELETED = 'deleted';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

}
