<?php

function random_by($num = 6): int
{
    $start = (int) str_repeat(1, $num);
    $end = (int) str_repeat(9, $num);
    return rand($start, $end);
}

function per_page($perPage = 10)
{
    if (request('per_page') == 'all') {
        return 999;
    }

    return request('per_page') ? (int) request('per_page') : $perPage;
}