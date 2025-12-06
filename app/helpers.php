<?php

declare(strict_types=1);

if (! function_exists('responseJson')) {

    function responseJson(bool $success = true, ?string $message = null, ?array $data = null, ?array $errors = null, int $code = 200)
    {
        $specs = [
            'success' => $success,
            'message' => $message,
        ];

        if ($success) {
            $specs['data'] = $data;
        } else {
            $specs['errors'] = $errors;
        }

        return response()->json($specs, $code);
    }

}