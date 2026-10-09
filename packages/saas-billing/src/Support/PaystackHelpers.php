<?php

    /**
     * Map internal request interval to Paystack supported intervals
     */
    function mapPaystackIntervals(string $interval): string
    {
        return match ($interval) {
            'day'   => 'daily',
            'week'  => 'weekly',
            'month' => 'monthly',
            'year'  => 'annually',
        };
    }
