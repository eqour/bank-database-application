<?php

namespace app\helpers;

use app\forms\Form;

class FormHelper {
    public static function invalidClass(Form $form, string $field): string {
        return $form->hasErrors($field) ? 'is-invalid' : '';
    }

    public static function invalidFeedback(Form $form, string $field): string {
        return '<div class="invalid-feedback">' . htmlspecialchars($form->getErrorMessage($field)) . '</div>';
    }

    public static function fieldName(Form $form, string $fieldName): string {
        return htmlspecialchars($form->name() . '[' . $fieldName . ']');
    }

    public static function generateGetParameters(array $parameters): string {
        $result = '';
        foreach ($parameters as $key => $parameter) {
            $result .= '<input hidden name="' . htmlspecialchars($key) . '" value="' . htmlspecialchars($parameter) . '">';
        }
        return $result;
    }
}
