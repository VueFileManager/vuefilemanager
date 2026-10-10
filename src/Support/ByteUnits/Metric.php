<?php
namespace Support\ByteUnits;

use InvalidArgumentException;

/**
 * Minimal in-repo replacement of the unmaintained gabrielelana/byte-units
 * Metric class (base 1000). Keeps the same public API and output format,
 * without the dependency on ext-bcmath.
 */
class Metric
{
    public const DEFAULT_FORMAT_PRECISION = 2;

    private const BASE = 1000;

    private const UNITS = ['YB' => 8, 'ZB' => 7, 'EB' => 6, 'PB' => 5, 'TB' => 4, 'GB' => 3, 'MB' => 2, 'kB' => 1, 'B' => 0];

    private int|float $numberOfBytes;

    public function __construct(int|float|string|null $numberOfBytes, private int $precision = self::DEFAULT_FORMAT_PRECISION)
    {
        $numberOfBytes = is_numeric($numberOfBytes) ? $numberOfBytes + 0 : 0;

        if ($numberOfBytes < 0) {
            throw new InvalidArgumentException('Number of bytes cannot be negative');
        }

        $this->numberOfBytes = $numberOfBytes;
    }

    public static function bytes(int|float|string|null $numberOf, int $precision = self::DEFAULT_FORMAT_PRECISION): static
    {
        return new static($numberOf, $precision);
    }

    public static function kilobytes(int|float|string|null $numberOf): static
    {
        return new static(self::scaleFromUnit($numberOf, 'kB'));
    }

    public static function megabytes(int|float|string|null $numberOf): static
    {
        return new static(self::scaleFromUnit($numberOf, 'MB'));
    }

    public static function gigabytes(int|float|string|null $numberOf): static
    {
        return new static(self::scaleFromUnit($numberOf, 'GB'));
    }

    public static function terabytes(int|float|string|null $numberOf): static
    {
        return new static(self::scaleFromUnit($numberOf, 'TB'));
    }

    public static function petabytes(int|float|string|null $numberOf): static
    {
        return new static(self::scaleFromUnit($numberOf, 'PB'));
    }

    /**
     * Format bytes, e.g. format() => "1.50MB", format('GB/') => "2GB", format('kB/4') => "1.2345kB"
     */
    public function format(int|string|null $howToFormat = null, string $separator = ''): string
    {
        $precision = $this->precisionFrom($howToFormat);
        $unit = $this->unitFrom($howToFormat);

        if (self::UNITS[$unit] === 0) {
            return sprintf('%d%s%s', $this->numberOfBytes, $separator, $unit);
        }

        $scaled = $this->numberOfBytes / pow(self::BASE, self::UNITS[$unit]);

        return sprintf("%.{$precision}f%s%s", $scaled, $separator, $unit);
    }

    /**
     * Get number of bytes as numeric string
     */
    public function numberOfBytes(): string
    {
        if (floor($this->numberOfBytes) == $this->numberOfBytes) {
            return sprintf('%.0f', $this->numberOfBytes);
        }

        return rtrim(rtrim(sprintf('%.10F', $this->numberOfBytes), '0'), '.');
    }

    private static function scaleFromUnit(int|float|string|null $quantity, string $unit): int|float
    {
        $quantity = is_numeric($quantity) ? $quantity + 0 : 0;

        return $quantity * pow(self::BASE, self::UNITS[$unit]);
    }

    private function precisionFrom(int|string|null $howToFormat): int
    {
        if (is_int($howToFormat)) {
            return $howToFormat;
        }

        if (is_string($howToFormat)) {
            if (preg_match('/^.*\/(?<precision>0*)$/', $howToFormat, $matches)) {
                return strlen($matches['precision']);
            }

            if (preg_match('/^.*\/(?<precision>\d+)$/', $howToFormat, $matches)) {
                return intval($matches['precision']);
            }
        }

        return $this->precision;
    }

    private function unitFrom(int|string|null $howToFormat): string
    {
        if (is_string($howToFormat) && preg_match('/^(?P<unit>[^\/]+)(?:\/.*$)?/i', $howToFormat, $matches)) {
            foreach (self::UNITS as $unit => $_) {
                if (strtolower($unit) === strtolower(trim($matches['unit']))) {
                    return $unit;
                }
            }
        }

        foreach (self::UNITS as $unit => $power) {
            if ($this->numberOfBytes / pow(self::BASE, $power) >= 1) {
                return $unit;
            }
        }

        return 'B';
    }
}
