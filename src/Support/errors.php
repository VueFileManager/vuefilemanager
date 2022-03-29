<?php

function accessDeniedError(): array
{
    return [
        'type'    => 'error',
        'message' => 'Access Denied',
    ];
}

function userActionNotAllowedError(): array
{
    return [
        'type'    => 'error',
        'message' => 'This user action is not allowed.',
    ];
}
