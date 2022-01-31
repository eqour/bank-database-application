<?php

namespace app\forms;

class Form {
    private $errors = null;

    public function validate(): bool {
        $this->validateFields();
        return !$this->hasErrors();
    }

    public function hasErrors(?string $field = null): bool {
        if ($field === null) {
            return isset($this->errors);
        }

        if (!isset($this->errors)) {
            return false;
        }

        return isset($this->errors[$field])
            && isset($this->errors[$field][0])
            ? true
            : false;
    }

    public function getErrorMessage(string $field): string {
        return isset($this->errors)
            && isset($this->errors[$field])
            && isset($this->errors[$field][0])
            ? $this->errors[$field][0]
            : '';
    }

    protected function addError(string $field, string $message): void {
        if (!isset($errors)) {
            $errors = [];
        }
        $this->errors[$field][] = $message;
    }

    public function load(array $array): bool {
        if (isset($array[$this->name()])) {
            $this->loadFields($array[$this->name()]);
            return true;
        } else {
            return false;
        }
    }

    public function name(): string {
        return substr(strrchr(get_class($this), "\\"), 1);
    }

    private function loadFields(array $fieldsData): void {
        foreach ($this->fieldNames() as $fieldName) {
            $this->loadFieldIfExists($fieldsData, $fieldName);
        }
    }

    private function loadFieldIfExists(array $array, string $fieldName): void {
        if (isset($array[$fieldName])) {
            $this->$fieldName = $array[$fieldName];
        }
    }

    protected function fieldNames(): array {
        return [];
    }

    public function getField(string $name): string {
        return isset($this->$name) ? $this->$name : '';
    }

    protected function validateFields(): void {}
}
