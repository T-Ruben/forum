<?php

namespace App\Services;

use s9e\TextFormatter\Configurator;

class BBCodeParser
{
    protected static $parser;
    protected static $renderer;

    public static function init()
    {
        if (self::$parser) {
            return;
        }

        $configurator = new Configurator();

        $configurator->BBCodes->addFromRepository('B');
        $configurator->BBCodes->addFromRepository('I');
        $configurator->BBCodes->addFromRepository('U');
        $configurator->BBCodes->addFromRepository('S');
        $configurator->BBCodes->addFromRepository('URL');
        $configurator->BBCodes->addFromRepository('URL');

        $configurator->tags['URL']->template =
            '<a href="{@url}" class="text-blue-600 underline hover:text-blue-800 transition-colors duration-200"
             rel="nofollow ugc noopener" target="_blank"><xsl:apply-templates/></a>';

        $configurator->BBCodes->addFromRepository('IMG');
        $configurator->BBCodes->addFromRepository('QUOTE');
        $configurator->BBCodes->addFromRepository('LIST');
        $configurator->BBCodes->addFromRepository('*'); // List items
        $configurator->BBCodes->addFromRepository('SIZE');
        $configurator->BBCodes->addFromRepository('COLOR');

        $configurator->MediaEmbed->add('youtube');


        extract($configurator->finalize());

        self::$parser = $parser;
        self::$renderer = $renderer;
    }

    public static function parse($text)
    {
        self::init();
        $xml = self::$parser->parse($text);
        return self::$renderer->render($xml);
    }

    public static function preview($text, $length = 200)
    {
        $plain = strip_tags(self::parse($text));
        return mb_substr($plain, 0, $length) . (mb_strlen($plain) > $length ? '...' : '');
    }
}
