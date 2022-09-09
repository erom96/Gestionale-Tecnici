<?php

namespace App\DB;

use App\DB\DBPDO;

class DbFactory {

    public static function create(array $options) {
        if (!array_key_exists('dsn', $options)) {
            if (!array_key_exists('driver', $options)) {
                throw new InvalidArgumentException(' Nessun driver predefinito');
            }
            $dsn = '';

            switch ($options['driver']) {
                case 'mysql':
                case 'oracle':
                case 'mssql':
                    $dsn = $options['driver'] . ':host=' . $options['host'] . ';dbname=' . $options['database'] . ';charset=utf8';
                    break;
                case 'sqlite':
                    $dsn = 'sqllite:' . $options['database'];
                    break;
                default:
                    throw new InvalidArgumentException(' Driver sconosciuto');
            }
            $options['dsn'] = $dsn;
        }
        return DBPDO::getInstance($options);
    }

}
