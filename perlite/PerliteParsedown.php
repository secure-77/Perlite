<?php

/*!
  * Perlite v1.5 (https://github.com/secure-77/Perlite)
  * Author: sec77 & Toaa (https://secure77.de)
  * Licensed under MIT (https://github.com/secure-77/Perlite/blob/main/LICENSE)
*/

include('Parsedown.php');

class PerliteParsedown extends Parsedown
{


    function text($text)
    {
        # make sure no definitions are set
        $this->DefinitionData = array();

        # standardize line breaks
        $text = str_replace(array("\r\n", "\r"), "\n", $text);

        # remove surrounding line breaks
        $text = trim($text, "\n");

        # split text into lines
        $lines = explode("\n", $text);

        # YAML front matter
        $parsedYamlBlockText = "";
        if ($lines[0] === '---') {

            # search ending
            $yamlBlockArray = array_slice($lines, 1, count($lines));
            $endIndex = 0;
            foreach ($yamlBlockArray as $line) {
                $endIndex += 1;
                if ($line === '---') {
                    break;
                }
            }
            $yamlBlockArray = array_slice($lines, 0, $endIndex);
            $yamlBlockText = implode("\n", $yamlBlockArray);
            $lines = array_slice($lines, $endIndex + 1, count($lines));
            $parsedYamlBlockText = $this->yamlFrontmatter($yamlBlockText);
        }

        # iterate through lines to identify blocks
        $markup = $this->lines($lines);

        # add front matter
        $markup = $parsedYamlBlockText . $markup;
        # trim line breaks
        $markup = trim($markup, "\n");

        return $markup;
    }

    protected function yamlFrontmatter($yaml)
    {

        if (!extension_loaded("yaml")) {
            return "YAML front matter found but PHP YAML Parse extension is missing!<br>";
        } else {

            // var_dump($yaml);
            $parsed = yaml_parse($yaml);
            $yamlText = '
            <div class="frontmatter-container">
             <div class="frontmatter-container-header">
                <div class="frontmatter-collapse-indicator collapse-indicator collapse-icon"><svg
                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="svg-icon right-triangle">
                <path d="M3 8L12 17L21 8"></path>
            </svg></div>Metadata</div>';

            # Parse Aliase if they are there
            if (array_key_exists("aliases", $parsed)) {
                $yamlText .= '<div class="frontmatter-section mod-aliases"><span class="frontmatter-section-label">Aliases</span>
                <div class="frontmatter-section-aliases">';
                foreach ($parsed["aliases"] as $alias) {
                    $yamlText .=  '<span class="frontmatter-alias"><span
                    class="frontmatter-alias-icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="svg-icon lucide-forward">
                        <polyline points="15 17 20 12 15 7"></polyline>
                        <path d="M4 18v-2a4 4 0 0 1 4-4h12"></path>
                    </svg></span>' . $alias . '</span>';
                }

                $yamlText .= '</div></div>';
            }

            # Parse Tags if they are there

            if (isset($parsed["tags"])) {
                $yamlText .= '
                <div class="frontmatter-section mod-tags"><span class="frontmatter-section-label">Tags</span>
                <div class="frontmatter-section-tags">';

                foreach ($parsed["tags"] as $tag) {

                    $Block = array(
                        'element' => array(
                            'name' => 'p',
                            'text' => '#' . $tag,
                            'handler' => 'line',
                        ),
                    );

                    $yamlText .= $this->elements($Block);
                }
                $yamlText .= '</div></div>';
            }


            $yamlText .= '</div>';
            return $yamlText;
        }
    }

    #
    # Callout (based on blockQuotes)
    # See: https://help.obsidian.md/How+to/Use+callouts

    protected function blockQuote($Line)
    {

        if (preg_match('/^>[ ]?(.*)/', $Line['text'], $matches)) {
            $Block = array(
                'element' => array(
                    'name' => 'div',
                    'handler' => 'lines',
                    'text' => (array) $matches[1],
                ),
            );

            if (preg_match('/^>\s?\[\!(.*?)\](.*?)$/m', $Line['text'], $matches)) {
                $type = strtolower($matches[1]);
                $title = $matches[2];

                $Block['element']['attributes']['class'] = "callout";
                $Block['element']['attributes']['data-callout'] = $type;
                $Block['element']['text'][0] = $title ?: ucfirst($type);
            }

            return $Block;
        }
    }


    // blockHeader seperated from Tags

    protected function blockHeader($Line)
    {
        if (isset($Line['text'][1]) && ($Line['text'][1] === ' ' || $Line['text'][1] === '#')) {
            $level = 1;

            while (isset($Line['text'][$level]) and $Line['text'][$level] === '#') {
                $level++;
            }

            if ($level > 6) {
                return;
            }

            $text = trim($Line['text'], '# ');

            $Block = array(
                'element' => array(
                    'name' => 'h' . min(6, $level),
                    'text' => $text,
                    'handler' => 'line',
                ),
            );

            return $Block;
        }
    }

    // extend to obsidian tags

    protected $inlineMarkerList = '!"*_#&[:<>`~\\';
    protected $InlineTypes = array(
        '"' => array('SpecialCharacter'),
        '!' => array('Image'),
        '&' => array('SpecialCharacter'),
        '*' => array('Emphasis'),
        ':' => array('Url'),
        '<' => array('UrlTag', 'EmailTag', 'Markup', 'SpecialCharacter'),
        '>' => array('SpecialCharacter'),
        '[' => array('Link'),
        '#' => array('Tag'),
        '_' => array('Emphasis'),
        '`' => array('Code'),
        '~' => array('Strikethrough'),
        '\\' => array('EscapeSequence'),
    );



    // handle obsidian tags
    protected function inlineTag($Excerpt)
    {
        if (!isset($Excerpt['text'][1]) or $Excerpt['text'][0] !== '#') {
            return;
        }

        if (preg_match('/#\w+/ui', $Excerpt['context'], $matches, PREG_OFFSET_CAPTURE)) {
            $tag = $matches[0][0];

            $Inline = array(
                'extent' => strlen($matches[0][0]),
                'position' => $matches[0][1],
                'element' => array(
                    'name' => 'a',
                    'text' => $tag,
                    'attributes' => array(
                        'href' => $tag,
                        'class' => 'tag'
                    ),
                ),
            );

            return $Inline;
        }
    }

    // handle external Urls
    protected function inlineUrl($Excerpt)
    {
        if ($this->urlsLinked !== true or !isset($Excerpt['text'][2]) or $Excerpt['text'][2] !== '/') {
            return;
        }

        if (preg_match('/\bhttps?:[\/]{2}[^\s<]+\b\/*/ui', $Excerpt['context'], $matches, PREG_OFFSET_CAPTURE)) {
            $url = $matches[0][0];

            $Inline = array(
                'extent' => strlen($matches[0][0]),
                'position' => $matches[0][1],
                'element' => array(
                    'name' => 'a',
                    'text' => $url,
                    'attributes' => array(
                        'href' => $url,
                        'class' => 'external-link perlite-external-link',
                        'target' => '_blank',
                        'rel' => 'noopener noreferrer',
                    ),
                ),
            );

            return $Inline;
        }
    }

    // handle external Urls
    protected function inlineLink($Excerpt)
    {
        $Element = array(
            'name' => 'a',
            'handler' => 'line',
            'nonNestables' => array('Url', 'Link'),
            'text' => null,
            'attributes' => array(
                'href' => null,
                'title' => null,
                'class' => 'external-link perlite-external-link',
                'target' => '_blank',
                'rel' => 'noopener noreferrer',
            ),
        );

        $extent = 0;

        $remainder = $Excerpt['text'];

        if (preg_match('/\[((?:[^][]++|(?R))*+)\]/', $remainder, $matches)) {
            $Element['text'] = $matches[1];

            $extent += strlen($matches[0]);

            $remainder = substr($remainder, $extent);
        } else {
            return;
        }

        if (preg_match('/^[(]\s*+((?:[^ ()]++|[(][^ )]+[)])++)(?:[ ]+("[^"]*"|\'[^\']*\'))?\s*[)]/', $remainder, $matches)) {
            $Element['attributes']['href'] = $matches[1];

            if (isset($matches[2])) {
                $Element['attributes']['title'] = substr($matches[2], 1, -1);
            }

            $extent += strlen($matches[0]);
        } else {
            if (preg_match('/^\s*\[(.*?)\]/', $remainder, $matches)) {
                $definition = strlen($matches[1]) ? $matches[1] : $Element['text'];
                $definition = strtolower($definition);

                $extent += strlen($matches[0]);
            } else {
                $definition = strtolower($Element['text']);
            }

            if (!isset($this->DefinitionData['Reference'][$definition])) {
                return;
            }

            $Definition = $this->DefinitionData['Reference'][$definition];

            $Element['attributes']['href'] = $Definition['url'];
            $Element['attributes']['title'] = $Definition['title'];
        }

        return array(
            'extent' => $extent,
            'element' => $Element,
        );
    }
}
