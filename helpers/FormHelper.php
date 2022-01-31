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
}
