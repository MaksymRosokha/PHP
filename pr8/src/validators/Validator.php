<?php

declare(strict_types=1);

namespace Rosokha\App\validators;

/**
 *
 */
abstract class Validator
{

    /**
     * @var array
     */
    private static array $errors = [];

    /**
     * function validate data
     * @return array array of errors
     */
    public static abstract function validateData(array $data): array;

    /**
     * @param $data
     * @return string
     */
    public static function getValidatedData($data): string
    {
        $data = trim($data);//видаляє пробели
        $data = stripslashes($data);//видаляє екранування
        $data = htmlspecialchars($data);
        return $data;
    }

    /**
     * @param array $errors
     */
    public static function addError(string $error): void
    {
        self::$errors[] = $error;
    }

    /**
     * @return void
     */
    public static function clearErrors(): void
    {
        self::$errors[] = [];
    }

    /**
     * @return array
     */
    public static function getErrors(): array
    {
        return self::$errors;
    }

    /**
     * @param array $errors
     */
    public static function setErrors(array $errors): void
    {
        self::$errors = $errors;
    }
}