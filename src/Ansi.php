<?php declare(strict_types=1);

namespace PHPAnsiCode;

use ErrorException;

class Ansi
{
    /**
     * List of Ansi tags for text styling
     * @var array|string[]
     */
    private static array $tags = [
        'black' => "\033[30m",
        'red' => "\033[31m",
        'green' => "\033[32m",
        'yellow' => "\033[33m",
        'blue' => "\033[34m",
        'magenta' => "\033[35m",
        'cyan' => "\033[36m",
        'white' => "\033[37m",
        'default' => "\033[39m",
        'bgBlack' => "\033[40m",
        'bgRed' => "\033[41m",
        'bgGreen' => "\033[42m",
        'bgYellow' => "\033[43m",
        'bgBlue' => "\033[44m",
        'bgMagenta' => "\033[45m",
        'bgCyan' => "\033[46m",
        'bgWhite' => "\033[47m",
        'bgDefault' => "\033[49m",
        'reset' => "\033[0m",
    ];

    /**
     * This function styles the text with the desired color
     * @param string|array $color
     * @param string|null $text
     * @return bool|string
     */
    public static function color(string|array $color, string $text = null): bool|string
    {
        if (is_array($color) and isset($color['color']) and isset($color['text'])) {
            return sprintf("%s%s%s", static::$tags[$color['color']], $color['text'], "\033[0m");
        } elseif ($text <> null and is_string($color)) {
            return sprintf("%s%s%s", static::$tags[$color], $text, "\033[0m");
        } else return false;
    }

    /**
     * This function gives single component colors to our text. Like 127
     * @param int $number
     * @param string $text
     * @return bool|string
     */
    public static function scColor(int $number, string $text): bool|string
    {
        if ($number > 255 or $number < 0) return false;
        return sprintf("\033[38;5;%dm%s\033[0m", $number, $text);
    }

    /**
     * This function is also for single-component colors, it is only applied to the background of the text
     * @param int $number
     * @param string $text
     * @return bool|string
     */
    public static function scBgColor(int $number, string $text): bool|string
    {
        if ($number > 255 or $number < 0) return false;
        return sprintf("\033[48;5;%dm%s\033[0m", $number, $text);
    }

    /**
     * Apply rgb colors to text
     * @param int $r
     * @param int $g
     * @param int $b
     * @param string $text
     * @return string|bool
     */
    public static function rgb(int $r, int $g, int $b, string $text): string|bool
    {
        foreach ([$r, $g, $b] as $number) {
            if ($number > 255 or $number < 0) return false;
        }
        return sprintf("\033[38;2;%d;%d;%dm%s\033[0m", $r, $g, $b, $text);
    }

    /**
     * Apply rgb colors to the text background
     * @param int $r
     * @param int $g
     * @param int $b
     * @param string $text
     * @return string|bool
     */
    public static function bgRgb(int $r, int $g, int $b, string $text): string|bool
    {
        foreach ([$r, $g, $b] as $number) {
            if ($number > 255 or $number < 0) return false;
        }
        return sprintf("\033[48;2;%d;%d;%dm%s\033[0m", $r, $g, $b, $text);
    }

    /**
     * Applying colors to the text with tags in the form of html tags such as <red>
     * @param string $string
     * @param bool $closedTags
     * @return string|null
     */
    public static function tagsToColor(string $string, bool $closedTags = false): ?string
    {
        if (!$closedTags) {
            preg_match_all("/<.*?>/i", $string, $tags);
            foreach ($tags[0] as $tag) {
                if (array_key_exists($key = substr($tag, 1, -1), static::$tags)) {
                    $string = str_replace($tag, static::$tags[$key], $string);
                }
            }
            return $string;
        }
        else {
            preg_match_all("/<.*?>/i", $string, $tags);
            foreach ($tags[0] as $tag) {
                preg_match("/<.*?>/i", $tag, $key); $key = $key[0];
                if (array_key_exists($index = substr($key, 1, -1), static::$tags))
                    $string = str_replace($key, static::$tags[$index], $string);
                else
                    $string = str_replace($key, static::$tags['reset'], $string);
            }
        }
        return $string;
    }

    /**
     * Printing a series of interesting colors and codes
     * @return void
     */
    public static function coolThing(): void
    {
        for ($i = 0; $i < 17; $i++) {
            for ($j = 1; $j < 16; $j++) {
                $code = ($i * 15 + $j);
                echo sprintf("\033[38;5;%dm%s \033[0m", $code, $code);
            }
            echo PHP_EOL;
        }
    }

    /**
     * Apply italic style to the text
     * @param string $text
     * @return string
     */
    public static function italic(string $text): string
    {
        return sprintf("%s%s%s", "\033[3m", $text, "\033[23m");
    }

    /**
     * Apply bold style to the text
     * @param string $text
     * @return string
     */
    public static function bold(string $text): string
    {
        return sprintf("%s%s%s", "\033[1m", $text, "\033[22m");
    }

    /**
     * Apply underline style to the text
     * @param string $text
     * @return string
     */
    public static function underline(string $text): string
    {
        return sprintf("%s%s%s", "\033[4m", $text, "\033[24m");
    }

    /**
     * Apply blinking style to the text
     * @param string $text
     * @return string
     */
    public static function blinking(string $text): string
    {
        return sprintf("%s%s%s", "\033[5m", $text, "\033[25m");
    }

    /**
     * Apply inverse style to the text
     * @param string $text
     * @return string
     */
    public static function inverse(string $text): string
    {
        return sprintf("%s%s%s", "\033[7m", $text, "\033[27m");
    }

    /**
     * Apply color to the text with magic invoke method
     * @param string|array $color
     * @param string|null $text
     * @return false|string
     */
    public function __invoke(string|array $color, string $text = null)
    {
        if (is_array($color) and isset($color['color']) and isset($color['text'])) {
            return sprintf("%s%s%s", static::$tags[$color['color']], $color['text'], "\033[0m");
        } elseif ($text <> null and is_string($color)) {
            return sprintf("%s%s%s", static::$tags[$color], $text, "\033[0m");
        } else return false;
    }

    /**
     * Applying color to text with magic method call.
     * We can use the class example and then put the desired color name and pass the text input to it.
     * @param string $name
     * @param array $arguments
     * @return string
     * @throws ErrorException
     */
    public function __call(string $name, array $arguments)
    {
        if (!isset(static::$tags[$name])) {
            throw new ErrorException("The desired item does not exist!");
        }
        try {
            return sprintf("%s%s%s", static::$tags[$name], $arguments[0], "\033[0m");
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Applying color to text with magic method callStatic.
     * We can use the class example and then put the desired color name and pass the text input to it.
     * @param string $name
     * @param array $arguments
     * @return string
     * @throws ErrorException
     */
    public static function __callStatic(string $name, array $arguments)
    {
        if (!isset(static::$tags[$name])) {
            throw new ErrorException("The desired item does not exist!");
        }
        try {
            return sprintf("%s%s%s", static::$tags[$name], $arguments[0], "\033[0m");
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
