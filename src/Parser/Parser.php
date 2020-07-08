<?php

namespace Pointsman\Parser;

use Pointsman\Parser\Element;
use Pointsman\Parser\ParsedPattern;

/**
 * Class PatternParser
 *
 * @package Pointsman
 */
class Parser
{
    const DEFAULT_TOKEN = '\w+';
    const VARIABLE_PARAMETERS_SEPARATOR = ':';

    /**
     * @param string $pattern /example/[num]
     *
     * @return ParsedPattern
     * @throws \Exception
     */
    public function parse($pattern)
    {
        /** Getting all variables from pattern */
        preg_match_all('/(\[|\().*?(\]|\))/m', $pattern, $variables);

        $parameters = [];
        $suffix = null;

        foreach($variables[0] as $variable) {
            /** If variable is required */
            if(substr($variable, 0, 1) === '[') {
                /** @var Element $patternElement */
                $patternElement = $this->getRegularExpressionParts($variable);

                /** If not required variable */
            } elseif(substr($variable, 0, 1) === '(') {
                /** @var Element $patternElement */
                $patternElement = $this->getRegularExpressionParts($variable, '()');

                /** Adding suffix for regular expression group, which will make it non-required */
                $suffix = '?';
            } else {
                throw new \Exception(sprintf('Unknown token `%s`', $variable));
            }

            $parameters[] = $patternElement->getName();
            $pattern = str_replace($variable, sprintf('(%s)%s', $patternElement->getRegexp(), $suffix), $pattern);
        }

        return new ParsedPattern($pattern, $parameters);
    }

    /**
     * Parsing variable from pattern
     *
     * @param string $variable
     * @param string $trimCharset
     *
     * @return Element
     */
    private function getRegularExpressionParts($variable, $trimCharset = '[]')
    {
        if (strpos($variable, self::VARIABLE_PARAMETERS_SEPARATOR) !== false) {
            list($name, $regexp) = explode(self::VARIABLE_PARAMETERS_SEPARATOR, trim($variable, $trimCharset));
        } else {
            $name = trim($variable, self::DEFAULT_TOKEN);
            $regexp = self::DEFAULT_TOKEN;
        }

        return new Element($name, $regexp);
    }
}