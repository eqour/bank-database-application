<?php

namespace app\helpers;

class TextHelper {
    public static function convertCamelCaseToDash(string $camelCaseString): string {
        $result = '';
        for ($i = 0; $i < mb_strlen($camelCaseString, 'UTF-8'); $i++) {
            if (ctype_upper($camelCaseString[$i]) && $i !== 0) {
                $result .= '-' . strtolower($camelCaseString[$i]);
            } else {
                $result .= strtolower($camelCaseString[$i]);
            }
        }
        return $result;
    }
    
    public static function convertDashToCamelCase(string $dashString): string {
        $result = '';
        for ($i = 0; $i < mb_strlen($dashString, 'UTF-8'); $i++) {
            if ($dashString[$i] === '-') {
                $result .= strtoupper($dashString[++$i]);
            } else {
                $result .= strtolower($dashString[$i]);
            }
        }
        return $result;
    }

    public static function paramsToQuery(array $params = []): string {
        if (!isset($params) || count($params) === 0) {
            return '';
        } else {
            $first = false;
            $query = '';
            foreach ($params as $key => $value) {
                if ($first) {
                    $query .= '&';
                } else {
                    $first = true;
                    $query .= '?';
                }
                $query .= $key . '=' . $value;
            }
            return $query;
        }
    }
}
