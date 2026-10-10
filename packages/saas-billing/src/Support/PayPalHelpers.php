<?php

    /**
     * Map internal request interval to PayPal supported intervals
     */
    function mapPayPalInterval(string $interval): string
    {
        return match ($interval) {
            'day'   => 'DAY',
            'week'  => 'WEEK',
            'month' => 'MONTH',
            'year'  => 'YEAR',
        };
    }
