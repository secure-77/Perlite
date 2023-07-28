<?php

namespace Perlite;

use Parsedown;

/*!
  * Perlite v1.5.5 (https://github.com/secure-77/Perlite)
  * Author: sec77 (https://secure77.de)
  * Licensed under MIT (https://github.com/secure-77/Perlite/blob/main/LICENSE)
*/
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


    # Callout Block
    protected function blockQuote($Line)
    {


        if (preg_match('/^>[ ]?(.*)/', $Line['text'], $matches)) {
            $Block = array(
                'element' => array(
                    'name' => 'blockquote',
                    'handler' => 'lines',
                    'text' => (array) $matches[1],
                ),
            );


            if (preg_match('/^>\s?\[\!(.*?)\](.*?)$/m', $Line['text'], $matches)) {
                $type = strtolower($matches[1]);
                $title = $matches[2];

                $calloutTitle = $title ?: ucfirst($type);

                $Block = array(
                    'element' => array(
                        'name' => 'div',
                        'attributes' => array(
                            'data-callout' => $type,
                            'class' => 'callout'
                        ),
                        'elements' => array(
                            array(
                                'name' => 'div',
                                'attributes' => array('class' => 'callout-title'),
                                'elements' => array(
                                    # callout icon
                                    array(
                                        'name' => 'div',
                                        'attributes' => array('class' => 'callout-icon'),
                                        'elements' => array(
                                            # svg
                                            array(
                                                'name' => 'svg',
                                                'attributes' => array(
                                                    'xmlns' => 'http://www.w3.org/2000/svg',
                                                    'width' => '24',
                                                    'height' => '24',
                                                    'viewBox' => '0 0 24 24',
                                                    'fill' => 'none',
                                                    'stroke' => 'currentColor',
                                                    'stroke-width' => '2',
                                                    'stroke-linecap' => 'round',
                                                    'stroke-linejoin' => 'round',
                                                    'class' => $this->getCalloutIcon($type)[0],
                                                ),
                                                # pathes and lines
                                                'elements' => $this->getCalloutIcon($type)[1]
                                            ),
                                        ),
                                    ),
                                    array(
                                        'name' => 'div',
                                        'attributes' => array('class' => 'callout-title-inner'),
                                        'text' => $calloutTitle,

                                    ),
                                ),

                            ),

                            array(
                                'name' => 'div',
                                'attributes' => array('class' => 'callout-content'),
                                'handler' => 'lines',
                            ),


                        )
                    ),
                );
            }
        }


        return $Block;
    }

    # Callout Icons
    protected function getCalloutIcon($callType)
    {
        // default = info
        $class = 'svg-icon lucide-pencil';
        $pathes = array(
            array('name' => 'line x1="18" y1="2" x2="22" y2="6"'),
            array('name' => 'path d="M7.5 20.5 19 9l-4-4L3.5 16.5 2 22z"')
        );

        $callType = strtolower($callType);
        switch ($callType) {

            case 'abstract':
                $class = 'svg-icon lucide-clipboard-list';
                $pathes = array(
                    array('name' => 'rect x="8" y="2" width="8" height="4" rx="1" ry="1"'),
                    array('name' => 'path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"'),
                    array('name' => 'path d="M12 11h4"'),
                    array('name' => 'path d="M12 16h4"'),
                    array('name' => 'path d="M8 11h.01"'),
                    array('name' => 'path d="M8 16h.01"'),
                );
                break;
            case 'info':
                $class = 'svg-icon lucide-info';
                $pathes = array(
                    array('name' => 'circle cx="12" cy="12" r="10"'),
                    array('name' => 'line x1="12" y1="16" x2="12" y2="12"'),
                    array('name' => 'line x1="12" y1="8" x2="12.01" y2="8"'),
                );
                break;
            case 'todo':
                $class = 'svg-icon lucide-check-circle-2';
                $pathes = array(
                    array('name' => 'path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"'),
                    array('name' => 'path d="m9 12 2 2 4-4"'),
                );
                break;
            case 'tip':
                $class = 'svg-icon lucide-flame';
                $pathes = array(
                    array('name' => 'path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 2.5z"'),
                );
                break;
            case 'success':
                $class = 'svg-icon lucide-check';
                $pathes = array(
                    array('name' => 'polyline points="20 6 9 17 4 12"'),
                );
                break;
            case 'question':
                $class = 'svg-icon lucide-help-circle';
                $pathes = array(
                    array('name' => 'circle cx="12" cy="12" r="10"'),
                    array('name' => 'path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"'),
                    array('name' => 'line x1="12" y1="17" x2="12.01" y2="17"'),
                );
                break;
            case 'warning':
                $class = 'svg-icon lucide-alert-triangle';
                $pathes = array(
                    array('name' => 'path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"'),
                    array('name' => 'line x1="12" y1="9" x2="12" y2="13"'),
                    array('name' => 'line x1="12" y1="17" x2="12.01" y2="17"'),
                );
                break;
            case 'failure':
                $class = 'svg-icon lucide-x';
                $pathes = array(
                    array('name' => 'line x1="18" y1="6" x2="6" y2="18"'),
                    array('name' => 'line x1="6" y1="6" x2="18" y2="18"'),
                );
                break;
            case 'danger':
                $class = 'svg-icon lucide-zap';
                $pathes = array(
                    array('name' => 'polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"'),
                );
                break;
            case 'bug':
                $class = 'svg-icon lucide-bug';
                $pathes = array(
                    array('name' => 'rect x="8" y="6" width="8" height="14" rx="4"'),
                    array('name' => 'path d="m19 7-3 2"'),
                    array('name' => 'path d="m5 7 3 2"'),
                    array('name' => 'path d="m19 19-3-2"'),
                    array('name' => 'path d="m5 19 3-2"'),
                    array('name' => 'path d="M20 13h-4"'),
                    array('name' => 'path d="M4 13h4"'),
                    array('name' => 'path d="m10 4 1 2"'),
                    array('name' => 'path d="m14 4-1 2"'),
                );
                break;
            case 'example':
                $class = 'svg-icon lucide-list';
                $pathes = array(
                    array('name' => 'line x1="8" y1="6" x2="21" y2="6"'),
                    array('name' => 'line x1="8" y1="12" x2="21" y2="12"'),
                    array('name' => 'line x1="8" y1="18" x2="21" y2="18"'),
                    array('name' => 'line x1="3" y1="6" x2="3.01" y2="6"'),
                    array('name' => 'line x1="3" y1="12" x2="3.01" y2="12"'),
                    array('name' => 'line x1="3" y1="18" x2="3.01" y2="18"'),
                );
                break;
            case 'quote':
                $class = 'svg-icon lucide-quote';
                $pathes = array(
                    array('name' => 'path d="M3 21c3 0 7-1 7-8V5c0-1.25-.756-2.017-2-2H4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2 1 0 1 0 1 1v1c0 1-1 2-2 2s-1 .008-1 1.031V20c0 1 0 1 1 1z"'),
                    array('name' => 'path d="M15 21c3 0 7-1 7-8V5c0-1.25-.757-2.017-2-2h-4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2h.75c0 2.25.25 4-2.75 4v3c0 1 0 1 1 1z"'),
                );
                break;
        }

        return array($class, $pathes);
    }

    # Callout Block inner
    protected function blockQuoteContinue($Line, array $Block)
    {

        if ($Line['text'][0] === '>' and preg_match('/^>[ ]?(.*)/', $Line['text'], $matches)) {

            if (isset($Block['interrupted'])) {

                unset($Block['interrupted']);
            }


            $quoteContent = $matches[1];

            if (isset($Block['element']['elements'])) {
                $Block['element']['elements'][1]['text'][] = $quoteContent;
            } else {
                $Block['element']['text'][] = $quoteContent;
            }


            return $Block;
        }


        if (!isset($Block['interrupted'])) {

            if (isset($Block['element']['elements'])) {
                $Block['element']['elements'][1]['text'][] = $Line['text'];
            } else {
                $Block['element']['text'][] = $Line['text'];
            }

            return $Block;
        }
    }

    # blockHeader seperated from Tags
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

    # extend to obsidian tags
    protected $inlineMarkerList = '!"*$_#&[:<>`~\\';
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
        '$' => array('Katex'),
        '_' => array('Emphasis'),
        '`' => array('Code'),
        '~' => array('Strikethrough'),
        '\\' => array('EscapeSequence'),
    );


    # handle katex code
    protected function inlineKatex($Excerpt)
    {
        $katex = $Excerpt['text'];

        if (preg_match("/(\\$\\$[^ ].*?\\$\\$)/", $Excerpt['text'], $matches)) {

            $katex = $matches[0];
            
        } else if (preg_match("/(\\$[^ ].*?\\$)/", $Excerpt['text'], $matches)) {

            $katex = $matches[0];

        } else {
            return;
        }

        return array(
            'extent' => strlen($katex),
            'element' => array(
                'name' => 'katex',
                'text' =>  $katex,
            ),
        );
    }

    # handle obsidian tags
    protected function inlineTag($Excerpt)
    {
        if (!isset($Excerpt['text'][1]) or $Excerpt['text'][0] !== '#') {
            return;
        }

        # ignore tags in links
        $len = strlen($Excerpt['context']);
        if ($len == 0) {
            return;
        }
        if (substr(trim($Excerpt['context']),-1) === ']') {
            return;
        }

        if (preg_match("/(^| )#[\w'-\/]+/ui", $Excerpt['context'], $matches, PREG_OFFSET_CAPTURE)) {
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

    # handle external Urls
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

    # handle external obsidian Urls
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

    # adjusted to support nested elements
    protected function element(array $Element)
    {
        if ($this->safeMode) {
            $Element = $this->sanitiseElement($Element);
        }

        $markup = '<' . $Element['name'];

        if (isset($Element['attributes'])) {
            foreach ($Element['attributes'] as $name => $value) {
                if ($value === null) {
                    continue;
                }

                $markup .= ' ' . $name . '="' . self::escape($value) . '"';
            }
        }

        $permitRawHtml = false;

        # nested element handling
        $closing = false;
        if (isset($Element['elements'])) {
            $markup .= '>';
            $markup .= $this->elements($Element['elements']);
            $closing = true;
        } elseif (isset($Element['text'])) {
            $text = $Element['text'];
        } elseif (isset($Element['rawHtml'])) {
            $text = $Element['rawHtml'];
            $allowRawHtmlInSafeMode = isset($Element['allowRawHtmlInSafeMode']) && $Element['allowRawHtmlInSafeMode'];
            $permitRawHtml = !$this->safeMode || $allowRawHtmlInSafeMode;
        }

        if (isset($text)) {
            $markup .= '>';

            if (!isset($Element['nonNestables'])) {
                $Element['nonNestables'] = array();
            }

            if (isset($Element['handler'])) {
                $markup .= $this->{$Element['handler']}($text, $Element['nonNestables']);
            } elseif (!$permitRawHtml) {
                $markup .= self::escape($text, true);
            } else {
                $markup .= $text;
            }

            $markup .= '</' . $Element['name'] . '>';
        } elseif ($closing) {
            $markup .= '</' . $Element['name'] . '>';
        } else {
            $markup .= ' />';
        }

        return $markup;
    }

    # adjusted to handle interuppted quote blocks
    protected function lines(array $lines)
    {
        $CurrentBlock = null;

        foreach ($lines as $line) {
            if (chop($line) === '') {
                if (isset($CurrentBlock)) {
                    $CurrentBlock['interrupted'] = true;
                }

                continue;
            }

            if (strpos($line, "\t") !== false) {
                $parts = explode("\t", $line);

                $line = $parts[0];

                unset($parts[0]);

                foreach ($parts as $part) {
                    $shortage = 4 - mb_strlen($line, 'utf-8') % 4;

                    $line .= str_repeat(' ', $shortage);
                    $line .= $part;
                }
            }

            $indent = 0;

            while (isset($line[$indent]) and $line[$indent] === ' ') {
                $indent++;
            }

            $text = $indent > 0 ? substr($line, $indent) : $line;

            # ~

            $Line = array('body' => $line, 'indent' => $indent, 'text' => $text);

            # ~

            if (isset($CurrentBlock['continuable'])) {

                if ($CurrentBlock['type'] === 'Quote') {

                    if (!isset($CurrentBlock['interrupted'])) {
                        $Block = $this->{'block' . $CurrentBlock['type'] . 'Continue'}($Line, $CurrentBlock);
                        if (isset($Block)) {
                            $CurrentBlock = $Block;

                            continue;
                        } else {
                            if ($this->isBlockCompletable($CurrentBlock['type'])) {
                                $CurrentBlock = $this->{'block' . $CurrentBlock['type'] . 'Complete'}($CurrentBlock);
                            }
                        }
                    }
                } else {
                    $Block = $this->{'block' . $CurrentBlock['type'] . 'Continue'}($Line, $CurrentBlock);
                    if (isset($Block)) {
                        $CurrentBlock = $Block;

                        continue;
                    } else {
                        if ($this->isBlockCompletable($CurrentBlock['type'])) {
                            $CurrentBlock = $this->{'block' . $CurrentBlock['type'] . 'Complete'}($CurrentBlock);
                        }
                    }
                }
            }


            # ~

            $marker = $text[0];

            # ~

            $blockTypes = $this->unmarkedBlockTypes;

            if (isset($this->BlockTypes[$marker])) {
                foreach ($this->BlockTypes[$marker] as $blockType) {
                    $blockTypes[] = $blockType;
                }
            }

            #
            # ~

            foreach ($blockTypes as $blockType) {
                $Block = $this->{'block' . $blockType}($Line, $CurrentBlock);

                if (isset($Block)) {
                    $Block['type'] = $blockType;

                    if (!isset($Block['identified'])) {
                        $Blocks[] = $CurrentBlock;

                        $Block['identified'] = true;
                    }

                    if ($this->isBlockContinuable($blockType)) {
                        $Block['continuable'] = true;
                    }

                    $CurrentBlock = $Block;

                    continue 2;
                }
            }

            # ~

            if (isset($CurrentBlock) and !isset($CurrentBlock['type']) and !isset($CurrentBlock['interrupted'])) {
                $CurrentBlock['element']['text'] .= "\n" . $text;
            } else {
                $Blocks[] = $CurrentBlock;

                $CurrentBlock = $this->paragraph($Line);

                $CurrentBlock['identified'] = true;
            }
        }

        # ~

        if (isset($CurrentBlock['continuable']) and $this->isBlockCompletable($CurrentBlock['type'])) {
            $CurrentBlock = $this->{'block' . $CurrentBlock['type'] . 'Complete'}($CurrentBlock);
        }

        # ~

        $Blocks[] = $CurrentBlock;

        unset($Blocks[0]);

        # ~

        $markup = '';

        foreach ($Blocks as $Block) {
            if (isset($Block['hidden'])) {
                continue;
            }

            $markup .= "\n";
            $markup .= isset($Block['markup']) ? $Block['markup'] : $this->element($Block['element']);
        }

        $markup .= "\n";

        # ~

        return $markup;
    }
}
