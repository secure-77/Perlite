<?php

/*!
 * Perlite v1.6.2 (https://github.com/secure-77/Perlite)
 * Author: sec77 (https://secure77.de)
 * Licensed under MIT (https://github.com/secure-77/Perlite/blob/main/LICENSE)
 */

namespace Perlite;

use Parsedown;

class PerliteParsedown extends Parsedown
{



    protected $path;
    protected $uriPath;
    protected $niceLinks;
    protected $allowedFileLinkTypes;
    protected $allowedImageTypes;
    protected $footnoteCount = 0;
    protected $inlineFootnoteCount = 0;
    protected $inlineMarkerList = '!"*$_#&[:<>`~\\=%^';

    protected $InlineTypes = array(
        '"' => array('SpecialCharacter'),
        '!' => array('Image', 'InternalEmbed'),
        '&' => array('SpecialCharacter'),
        '*' => array('Emphasis'),
        ':' => array('Url'),
        '<' => array('UrlTag', 'EmailTag', 'Markup', 'SpecialCharacter'),
        '>' => array('SpecialCharacter'),
        '[' => array('FootnoteMarker', 'InternalMarkdownLink', 'Link', 'InternalLink'),
        '#' => array('Tag'),
        '$' => array('Katex'),
        '_' => array('Emphasis'),
        '`' => array('Code'),
        '~' => array('Strikethrough'),
        '\\' => array('EscapeSequence'),
        '=' => array('Highlight'),
        '%' => array('Hidden'),
        '^' => array('InlineFootnote'),
    );


    public function __construct(
        $path = '',
        $uriPath = '/',
        $niceLinks = false,
        array $allowedFileLinkTypes = array('mp4', 'm4a', 'pdf'),
        array $allowedImageTypes = array(
            'png',
            'jpg',
            'jpeg',
            'svg',
            'gif',
            'bmp',
            'tif',
            'tiff',
            'webp'
        )
    ) {

        $this->path = $path;
        $this->uriPath = $uriPath;
        $this->niceLinks = $niceLinks;
        $this->allowedFileLinkTypes = $allowedFileLinkTypes;
        $this->allowedImageTypes = $allowedImageTypes;

        $this->BlockTypes['!'] = array('YouTube');
        $this->BlockTypes['['] = array('Footnote', 'Reference');
    }

    function text($text)
    {
        # make sure no definitions are set
        $this->DefinitionData = array();
        $this->footnoteCount = 0;
        $this->inlineFootnoteCount = 0;

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

        # add footnotes
        $markup .= $this->buildFootnotes();

        # trim line breaks
        $markup = trim($markup, "\n");

        return $markup;
    }

    #
    # YAML Front Matter / Obsidian Properties
    # See: https://help.obsidian.md/Editing+and+formatting/Properties

    protected function yamlFrontmatter(string $yaml): string
    {
        $parsed = $this->parseSimpleYaml($yaml);

        if (empty($parsed)) {
            return '';
        }

        $propertiesHtml = '';
        foreach ($parsed as $key => $value) {
            $propertiesHtml .= $this->renderProperty((string) $key, $value);
        }

        return '
    <div class="mod-header">
        <div class="metadata-properties-heading">
            <div class="collapse-indicator collapse-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="svg-icon right-triangle">
                    <path d="M3 8L12 17L21 8"></path>
                </svg>
            </div>
            <div class="metadata-properties-title">Properties</div>
        </div>
        <div class="metadata-container mod-error" tabindex="-1" data-property-count="' . count($parsed) . '">
            <div class="metadata-content">
                <div class="metadata-properties">'
            . $propertiesHtml . '
                </div>
            </div>
        </div>
    </div>';
    }

    protected function renderProperty(string $key, $value): string
    {
        $type = $this->getPropertyType($key, $value);
        [$iconClass, $iconPaths] = $this->getPropertyIcon($type);

        switch ($type) {
            case 'tags':
                $valueHtml = '<div class="multi-select-container">';
                foreach ($this->normalizeYamlList($value, true) as $tag) {
                    $valueHtml .= $this->element(array(
                        'name' => 'div',
                        'text' => '#' . $tag,
                        'handler' => 'line',
                        'attributes' => array('class' => 'multi-select-pill multi-select-pill-content'),
                    ));
                }
                $valueHtml .= '</div>';
                break;

            case 'aliases':
            case 'multitext':
                $valueHtml = '<div class="multi-select-container">';
                foreach ($this->normalizeYamlList($value) as $item) {
                    $valueHtml .= $this->element(array(
                        'name' => 'div',
                        'text' => $item,
                        'handler' => 'line',
                        'attributes' => array('class' => 'multi-select-pill multi-select-pill-content'),
                    ));
                }
                $valueHtml .= '</div>';
                break;

            case 'checkbox':
                $valueHtml = $this->element(array(
                    'name' => 'input',
                    'attributes' => array(
                        'class' => 'metadata-input-checkbox',
                        'type' => 'checkbox',
                        'checked' => $value ? 'checked' : null,
                        'disabled' => 'disabled',
                    ),
                ));
                break;

            case 'number':
                $valueHtml = $this->element(array(
                    'name' => 'input',
                    'attributes' => array(
                        'class' => 'metadata-input metadata-input-number',
                        'type' => 'number',
                        'value' => (string) $value,
                        'readonly' => 'readonly',
                    ),
                ));
                break;

            case 'date':
                $valueHtml = $this->element(array(
                    'name' => 'input',
                    'attributes' => array(
                        'class' => 'metadata-input metadata-input-text mod-date',
                        'type' => 'date',
                        'value' => $value,
                        'readonly' => 'readonly',
                    ),
                ));
                break;

            case 'datetime':
                preg_match('/^(\d{4}-\d{2}-\d{2})[T ](\d{2}:\d{2}(?::\d{2})?)/', $value, $dm);
                $valueHtml = $this->element(array(
                    'name' => 'input',
                    'attributes' => array(
                        'class' => 'metadata-input metadata-input-text mod-datetime',
                        'type' => 'datetime-local',
                        'value' => $dm[1] . 'T' . $dm[2],
                        'readonly' => 'readonly',
                    ),
                ));
                break;

            default: // text
                $valueHtml = $this->element(array(
                    'name' => 'div',
                    'text' => is_scalar($value) ? (string) $value : '',
                    'handler' => 'line',
                    'attributes' => array('class' => 'metadata-input-longtext mod-truncate'),
                ));
        }

        $keyEscaped = htmlspecialchars($key, ENT_QUOTES, 'UTF-8');

        return '
        <div class="metadata-property" tabindex="0" data-property-key="' . $keyEscaped . '" data-property-type="' . $type . '">
            <div class="metadata-property-key">
                <span class="metadata-property-icon" aria-disabled="false">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="svg-icon ' . $iconClass . '">'
            . $iconPaths . '</svg>
                </span>
                <span class="metadata-text">' . $keyEscaped . '</span>
            </div>
            <div class="metadata-property-value">' . $valueHtml . '</div>
        </div>';
    }

    protected function getPropertyType(string $key, $value): string
    {
        $key = strtolower($key);

        if ($key === 'tags' || $key === 'tag') {
            return 'tags';
        }
        if ($key === 'aliases' || $key === 'alias') {
            return 'aliases';
        }
        if ($key === 'cssclasses' || $key === 'cssclass') {
            return 'multitext';
        }
        if (is_array($value)) {
            return empty($value) ? 'text' : 'multitext';
        }
        if (is_bool($value)) {
            return 'checkbox';
        }
        if (is_int($value) || is_float($value)) {
            return 'number';
        }
        if (is_string($value)) {
            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
                return 'date';
            }
            if (preg_match('/^\d{4}-\d{2}-\d{2}[T ]\d{2}:\d{2}/', $value)) {
                return 'datetime';
            }
        }

        return 'text';
    }

    protected function getPropertyIcon(string $type): array
    {
        return match ($type) {
            'aliases' => ['lucide-forward', '<polyline points="15 17 20 12 15 7"/><path d="M4 18v-2a4 4 0 0 1 4-4h12"/>'],
            'tags' => ['lucide-tags', '<path d="M9 5H2v7l6.29 6.29c.94.94 2.48.94 3.42 0l3.58-3.58c.94-.94.94-2.48 0-3.42L9 5Z"/><path d="M6 9.01V9"/><path d="m15 5 6.3 6.3a2.4 2.4 0 0 1 0 3.4L17 19"/>'],
            'multitext' => ['lucide-list', '<line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/>'],
            'number' => ['lucide-binary', '<rect x="14" y="14" width="4" height="6" rx="2"/><rect x="6" y="4" width="4" height="6" rx="2"/><path d="M6 20h4"/><path d="M14 10h4"/><path d="M6 14h2v6"/><path d="M14 4h2v6"/>'],
            'checkbox' => ['lucide-check-square', '<polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>'],
            'date' => ['lucide-calendar', '<rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>'],
            'datetime' => ['lucide-clock', '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>'],
            default => ['lucide-text', '<path d="M17 6.1H3"/><path d="M21 12.1H3"/><path d="M15.1 18H3"/>'],
        };
    }

    # always returns a flat list of strings
    protected function normalizeYamlList($value, bool $isTags = false): array
    {
        if ($value === null || $value === '') {
            return [];
        }

        if (!is_array($value)) {
            // tags: "a, b" or "a b" / others: single value
            $value = $isTags ? preg_split('/[,\s]+/', (string) $value) : [$value];
        }

        $items = [];
        foreach ($value as $item) {
            if ($item === null || is_array($item)) {
                continue;
            }

            $item = is_bool($item) ? ($item ? 'true' : 'false') : trim((string) $item);

            if ($isTags) {
                $item = ltrim($item, '#');
            }

            if ($item !== '') {
                $items[] = $item;
            }
        }

        return $items;
    }

    protected function parseSimpleYaml(string $yaml): array
    {
        $lines = preg_split('/\R/', $yaml);
        $data = [];
        $currentKey = null;

        foreach ($lines as $line) {
            $line = rtrim($line);
            $trimmed = trim($line);

            // skip empty lines, delimiters and comments
            if ($trimmed === '' || $trimmed === '---' || str_starts_with($trimmed, '#')) {
                continue;
            }

            // list item of the current key: "  - value"
            if ($currentKey !== null && preg_match('/^\s*-(?:\s+(.*))?$/', $line, $matches)) {
                $item = $this->stripYamlComment($matches[1] ?? '');
                if ($item !== '') {
                    $data[$currentKey][] = $this->castYamlValue($item);
                }
                continue;
            }

            // top level key: "key: value" (key may contain spaces, value may contain ":")
            if (preg_match('/^([^\s#\-][^:]*?):(?:\s+(.*))?$/', $line, $matches)) {
                $key = trim($matches[1], " \"'");
                $value = $this->stripYamlComment($matches[2] ?? '');

                if ($value === '') {
                    // list follows (or empty value)
                    $data[$key] = [];
                    $currentKey = $key;
                } else {
                    $data[$key] = $this->parseYamlValue($value);
                    $currentKey = null;
                }

                continue;
            }

            // everything else (nested objects, block scalars) is ignored
        }

        return $data;
    }

    protected function parseYamlValue(string $value): mixed
    {
        $value = trim($value);

        // flow sequence: [a, b, "c, d"] — but not an unquoted wikilink [[...]]
        if (str_starts_with($value, '[') && !str_starts_with($value, '[[') && str_ends_with($value, ']')) {
            $inner = trim(substr($value, 1, -1));

            if ($inner === '') {
                return [];
            }

            $items = str_getcsv($inner, ',', '"', '');

            return array_values(array_filter(
                array_map(fn($item) => $this->castYamlValue(trim($item)), $items),
                fn($item) => $item !== '' && $item !== null
            ));
        }

        return $this->castYamlValue($value);
    }

    protected function stripYamlComment(string $value): string
    {
        $value = trim($value);

        // no comments inside quoted strings
        if ($value === '' || $value[0] === '"' || $value[0] === "'") {
            return $value;
        }

        return trim(preg_replace('/\s+#.*$/', '', $value));
    }

    protected function castYamlValue(string $value): mixed
    {
        $value = trim($value);

        // quoted strings stay strings (no type casting)
        if (strlen($value) >= 2 && ($value[0] === '"' || $value[0] === "'") && substr($value, -1) === $value[0]) {
            $inner = substr($value, 1, -1);

            return $value[0] === "'"
                ? str_replace("''", "'", $inner)
                : str_replace(array('\\"', '\\\\'), array('"', '\\'), $inner);
        }

        return match (strtolower($value)) {
            'true' => true,
            'false' => false,
            'null', '~' => null,
            default => is_numeric($value) ? ($value + 0) : $value,
        };
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

                # Handle collapsible callouts
                $calloutclass = 'callout';
                $calloutStyle = 'unset';
                $collapsibleIcon = array(
                    'name' => 'div',
                    'text' => ''
                );
                $isCollapsed = '';
                $needCollapseIcon = False;
                $isCollapsedIcon = '';
                $calloutTitleClass = 'callout-title-inner';

                if (substr($calloutTitle, 0, 1) == '+') {
                    $calloutTitle = substr($calloutTitle, 1);
                    $calloutclass = 'callout is-collapsible';
                    $calloutTitleClass = 'callout-title-inner is-collapsible';
                    $calloutStyle = 'unset';
                    $needCollapseIcon = True;
                }

                if (substr($calloutTitle, 0, 1) == '-') {
                    $calloutTitle = substr($calloutTitle, 1);
                    $calloutclass = 'callout is-collapsible is-collapsed';
                    $calloutStyle = 'none';
                    $isCollapsed = 'is-collapsed-callout';
                    $isCollapsedIcon = 'is-collapsed';
                    $calloutTitleClass = 'callout-title-inner is-collapsed';
                    $needCollapseIcon = True;
                }

                if ($needCollapseIcon) {
                    $collapsibleIcon = array(
                        'name' => 'div',
                        'attributes' => array('class' => 'callout-fold ' . $isCollapsedIcon),
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
                                    'class' => 'svg-icon lucide-chevron-down',
                                ),
                                # pathes and lines
                                'elements' => array(array('name' => '<path d="m6 9 6 6 6-6"/>')),
                            ),
                        ),
                    );
                }



                $Block = array(
                    'element' => array(
                        'name' => 'div',
                        'attributes' => array(
                            'data-callout' => $type,
                            'class' => $calloutclass
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
                                    # callout title
                                    array(
                                        'name' => 'div',
                                        'attributes' => array('class' => $calloutTitleClass),
                                        'text' => (array) $calloutTitle,
                                        'handler' => 'lines',

                                    ),
                                    # collapsible icon
                                    $collapsibleIcon,
                                ),
                            ),
                            # callout content
                            array(
                                'name' => 'div',
                                'attributes' => array(
                                    'class' => 'callout-content ' . $isCollapsed,
                                ),
                                'handler' => 'lines',
                                'text' => array(),
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

    protected function blockYouTube($Line)
    {

        if (!isset($Line['text'][1]) or $Line['text'][1] !== '[') {
            return;
        }

        $Line['text'] = substr($Line['text'], 1);

        $Link = $this->inlineLink($Line);


        if ($Link === null) {
            return;
        }

        // See: https://stackoverflow.com/a/64320469
        $yt = preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $Link['element']['attributes']['href'], $match);

        if (!$yt) {
            return;
        }

        $youtubeId = $match[1];
        $Block = array(
            'element' => array(
                'name' => 'iframe',
                'text' => $Line['text'],
                'handler' => 'line',

                'attributes' => array(
                    'class' => 'external-embed mod-receives-events',
                    'sandbox' => 'allow-forms allow-presentation allow-same-origin allow-scripts allow-modals allow-popups',
                    'allow' => 'fullscreen',
                    'frameborder' => '0',
                    'src' => 'https://www.youtube.com/embed/' . $youtubeId,
                ),

            ),
        );

        return $Block;
    }

    # Don't treat indented fences (``` / ~~~) as indented code blocks,
    # so blockFencedCode() gets a chance (e.g. code blocks inside lists)
    protected function blockCode($Line, $Block = null)
    {
        if (preg_match('/^(`{3,}|~{3,})/', $Line['text'])) {
            return;
        }

        return parent::blockCode($Line, $Block);
    }

    # remember the indentation of the opening fence
    protected function blockFencedCode($Line)
    {
        $Block = parent::blockFencedCode($Line);

        if ($Block !== null) {
            $Block['fenceIndent'] = $Line['indent'];
        }

        return $Block;
    }

    # strip the fence indentation from the code lines
    protected function blockFencedCodeContinue($Line, $Block)
    {
        if (!empty($Block['fenceIndent'])) {
            $Line['body'] = preg_replace(
                '/^[ ]{0,' . (int) $Block['fenceIndent'] . '}/',
                '',
                $Line['body']
            );
        }

        return parent::blockFencedCodeContinue($Line, $Block);
    }


    # handle highlight code
    protected function inlineHighlight($Excerpt)
    {
        $marker = $Excerpt['text'][1];

        if (preg_match('/^==(.+?)==/s', $Excerpt['text'], $matches)) {
            $content = $matches[1];
            $Inline = array(
                'extent' => strlen($matches[0]),
                'element' => array(
                    'name' => 'span',
                    'text' => $content,
                    'attributes' => array(
                        'class' => 'cm-highlight'
                    ),
                ),
            );

            return $Inline;
        }
    }

    # handle hidden code
    protected function inlineHidden($Excerpt)
    {
        $marker = $Excerpt['text'][1];

        if (preg_match('/^%%(.+?)%%/s', $Excerpt['text'], $matches)) {
            $content = "";
            $Inline = array(
                'extent' => strlen($matches[0]),
                'element' => array(
                    'name' => 'span',
                    'text' => $content,
                ),
            );

            return $Inline;
        }
    }

    # handle katex code
    protected function inlineKatex($Excerpt)
    {
        $marker = $Excerpt['text'][0];
        if (preg_match('/^(\\' . $marker . '+)[ ]*(.+?)[ ]*(?<!\\' . $marker . ')\1(?!\\' . $marker . ')/s', $Excerpt['text'], $matches)) {
            $text = $matches[0];
            $text = preg_replace("/[ ]*\n/", ' ', $text);

            $name = 'katex';
            if ($matches[1] === '$') {
                $name = 'katex-inline';
            }
            return array(
                'extent' => strlen($matches[0]),
                'element' => array(
                    'name' => $name,
                    'text' => $text,
                ),
            );
        }
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
        if (substr(trim($Excerpt['context']), -1) === ']') {
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

    protected function blockList($Line)
    {
        list($name, $pattern) = $Line['text'][0] <= '-' ? array('ul', '[*+-]') : array('ol', '[0-9]+[.]');



        if (preg_match('/(- \[(x| )\])(.*)/', $Line['text'], $matches)) {

            $text = isset($matches[3]) ? $matches[3] : '';
            $isActive = $matches[2];
            $checked = '';
            if ($isActive === 'x') {
                $checked = 'checked';
            }



            $Block = array(
                'element' => array(
                    'name' => 'div',
                    'elements' => array(
                        array(
                            'name' => 'div',
                            'attributes' => array(
                                'class' => 'HyperMD-list-line HyperMD-list-line-1 HyperMD-task-line cm-line',
                                'data-task' => $isActive,
                            ),
                        ),
                        array(
                            'name' => 'label',
                            'attributes' => array('class' => 'task-list-label'),
                            'elements' => array(
                                array(
                                    'name' => 'input',
                                    'attributes' => array(
                                        'class' => 'task-list-item-checkbox',
                                        'type' => 'checkbox',
                                        'data-task' => $isActive,
                                        $checked => '',
                                    ),
                                ),
                                array(
                                    'name' => 'label',
                                    'attributes' => array('class' => 'cm-widgetBuffer'),
                                    'text' => $text,
                                ),
                            ),
                        ),
                    ),
                ),
            );


            return $Block;
        }

        if (preg_match('/^(' . $pattern . '[ ]+)(.*)/', $Line['text'], $matches)) {
            $Block = array(
                'indent' => $Line['indent'],
                'pattern' => $pattern,
                'element' => array(
                    'name' => $name,
                    'handler' => 'elements',
                ),
            );

            if ($name === 'ol') {
                $listStart = stristr($matches[0], '.', true);

                if ($listStart !== '1') {
                    $Block['element']['attributes'] = array('start' => $listStart);
                }
            }

            $Block['li'] = array(
                'name' => 'li',
                'handler' => 'li',
                'text' => array(
                    $matches[2],
                ),
            );

            $Block['element']['text'][] = &$Block['li'];

            return $Block;
        }
    }

    protected function blockListContinue($Line, array $Block)
    {


        if (preg_match('/(- \[(x| )\])(.*)/', $Line['text'], $matches)) {

            $text = isset($matches[3]) ? $matches[3] : '';
            $isActive = $matches[2];

            $checked = '';
            if ($isActive === 'x') {
                $checked = 'checked';
            }





            $conBlock = array(
                'name' => 'div',
                'attributes' => array(
                    'class' => 'HyperMD-list-line HyperMD-list-line-1 HyperMD-task-line cm-line',
                    'data-task' => $isActive,
                ),
                'elements' => array(
                    array(
                        'name' => 'label',
                        'attributes' => array('class' => 'task-list-label'),
                        'elements' => array(
                            array(
                                'name' => 'input',
                                'attributes' => array(
                                    'class' => 'task-list-item-checkbox',
                                    'type' => 'checkbox',
                                    'data-task' => $isActive,
                                    $checked => '',
                                ),
                            ),
                            array(
                                'name' => 'label',
                                'attributes' => array('class' => 'cm-widgetBuffer'),
                                'text' => $text,
                            ),
                        ),
                    ),
                )
            );


            $Block['element']['elements'][] = &$conBlock;

            return $Block;
        }

        $Block['indent'] = isset($Block['indent']) ? $Block['indent'] : '0';

        if ($Block['indent'] === $Line['indent'] and preg_match('/^' . $Block['pattern'] . '(?:[ ]+(.*)|$)/', $Line['text'], $matches)) {

            if (isset($Block['interrupted'])) {
                $Block['li']['text'][] = '';

                $Block['loose'] = true;

                unset($Block['interrupted']);
            }

            unset($Block['li']);

            $text = isset($matches[1]) ? $matches[1] : '';

            $Block['li'] = array(
                'name' => 'li',
                'handler' => 'li',
                'text' => array(
                    $text,
                ),
            );

            $Block['element']['text'][] = &$Block['li'];

            return $Block;
        }

        if ($Line['text'][0] === '[' and $this->blockReference($Line)) {
            return $Block;
        }

        if (!isset($Block['interrupted'])) {
            if (preg_match('/^[`~]{3,}/', $Line['text'])) {
                return null;
            }
            $text = preg_replace('/^[ ]{0,4}/', '', $Line['body']);

            $Block['li']['text'][] = $text;

            return $Block;
        }

        if ($Line['indent'] > 0) {
            $Block['li']['text'][] = '';

            $text = preg_replace('/^[ ]{0,4}/', '', $Line['body']);

            $Block['li']['text'][] = $text;

            unset($Block['interrupted']);

            return $Block;
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
        } elseif ($this->isSelfClosingElement($Element['name'])) {
            $markup .= ' />';
        } else {
            # non-void elements must be closed explicitly (<div /> is an open <div> in HTML)
            $markup .= '></' . $Element['name'] . '>';
        }

        return $markup;
    }

    protected function isSelfClosingElement(string $name): bool
    {
        $tagName = strtolower(strtok($name, " \t"));

        // names like '<path d="..."/>' (used for the callout icons) stay as they are
        if (!preg_match('/^[a-z][a-z0-9-]*$/', $tagName)) {
            return true;
        }

        return in_array($tagName, array(
            // HTML void elements
            'area',
            'base',
            'br',
            'col',
            'embed',
            'hr',
            'img',
            'input',
            'link',
            'meta',
            'source',
            'track',
            'wbr',
            // SVG shapes (self-closing is valid in SVG)
            'path',
            'line',
            'circle',
            'rect',
            'polyline',
            'polygon',
            'ellipse',
        ), true);
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
                    if (function_exists('mb_strlen')) {
                        $shortage = 4 - (mb_strlen($line, 'UTF-8') % 4);
                    } else {
                        $shortage = 4 - (strlen($line) % 4);
                    }

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

    protected function inlineInternalLink($Excerpt)
    {
        if (!preg_match('/^\[\[(.+?)\]\]/', $Excerpt['text'], $matches)) {
            return;
        }

        $raw = $matches[1];

        // Split Obsidian-style: file|label|popup
        // Strip backslash used to escape | in Markdown tables (e.g. [[file.md\|Label]])
        $parts = explode('|', $raw);
        $linkFile = rtrim($parts[0], '\\');

        $ext = pathinfo($linkFile, PATHINFO_EXTENSION);
        $openNewTab = false;

        if (in_array($ext, $this->allowedFileLinkTypes)) {
            $openNewTab = true;
        }

        $linkText = $parts[1] ?? $parts[0];
        $isPopup = isset($parts[2]);

        $popupClass = $isPopup ? ' internal-popup' : '';
        $popupIcon = $isPopup ? $this->popupIconSvg() : '';

        // Determine relative traversal
        $path = $this->path;


        if (str_starts_with($linkFile, '../')) {
            $depth = substr_count($linkFile, '../');
            $segments = explode('/', $this->path);
            $segments = array_slice($segments, 0, count($segments) - $depth);
            $path = implode('/', $segments);
            $linkFile = preg_replace('#^(\.\./)+#', '', $linkFile);
        }

        // use only the file name for nice links
        if ($this->niceLinks == true) {
            $segments = explode('/', $linkText);
            $segments = array_slice($segments, count($segments) - 1, 1);
            $linkText = $segments[0];
        }


        if ($openNewTab == false) {
            $segments = explode('/', $path);
            $segments = array_slice($segments, 1, count($segments));
            $path = implode('/', $segments);
        }


        $urlPath = ltrim($path . '/' . $linkFile, '/');

        // Same-document anchor
        if (str_starts_with($raw, '#')) {
            return array(
                'extent' => strlen($matches[0]),
                'element' => array(
                    'name' => 'a',
                    'text' => $linkText,
                    'attributes' => array(
                        'href' => '#' . ltrim($linkFile, '#'),
                        'class' => 'internal-link' . $popupClass,
                    ),
                ),
            );
        }

        // URL normalization (ported exactly)
        if ($openNewTab == false) {
            $urlPath = str_replace('&amp;', '&', $urlPath);
            $urlPath = str_replace('%23', '#', $urlPath);
            $urlPath = str_replace('~', '%80', $urlPath);
            $urlPath = str_replace('-', '~', $urlPath);
            $urlPath = str_replace(' ', '-', $urlPath);
        }

        return array(
            'extent' => strlen($matches[0]),
            'element' => array(
                'name' => 'a',
                'handler' => 'line',
                'text' => $linkText,
                'attributes' => array(
                    'href' => $this->uriPath . $urlPath,
                    'class' => 'internal-link' . $popupClass,
                    'target' => $openNewTab ? '_blank' : null,
                    'rel' => $openNewTab ? 'noopener noreferrer' : null,
                ),
                'suffix' => $popupIcon,
            ),
        );
    }

    protected function inlineInternalMarkdownLink($Excerpt)
    {
        // Match [label](path) — but NOT external URLs
        if (!preg_match('/^\[([^\]]+)\]\(([^)]+)\)/', $Excerpt['text'], $m)) {
            return;
        }

        $label = $m[1];
        $path = $m[2];

        // Reject external links / any URI scheme (http:, mailto:, tel:, obsidian:, ...)
        if (preg_match('#^[a-z][a-z0-9+.-]*:#i', $path)) {
            return;
        }

        // Reject protocol-relative URLs
        if (str_starts_with($path, '//')) {
            return;
        }

        // Remove .md extension (also before an anchor: note.md#heading -> note#heading)
        $path = preg_replace('/\.md(?=$|#)/i', '', $path);

        // Convert into Obsidian-style payload
        // [[path|label]]
        $synthetic = '[[' . $path . '|' . $label . ']]';

        // Delegate to inlineInternalLink()
        $result = $this->inlineInternalLink([
            'text' => $synthetic,
        ]);

        if ($result === null) {
            return;
        }

        // Adjust extent to original Markdown syntax length
        $result['extent'] = strlen($m[0]);

        return $result;
    }

    # handle standard markdown images: ![alt](path "title")
    # also supports Obsidian sizing: ![alt|300x200](path) / ![alt|300](path)
    protected function inlineImage($Excerpt)
    {
        if (!isset($Excerpt['text'][1]) || $Excerpt['text'][1] !== '[') {
            return;
        }

        // ![[...]] is handled by inlineInternalEmbed()
        if (isset($Excerpt['text'][2]) && $Excerpt['text'][2] === '[') {
            return;
        }

        if (!preg_match(
            '/^!\[((?:[^\[\]]|\\\\.)*)\]\(\s*(<[^>]+>|(?:[^\s()]+|\([^\s()]*\))+)(?:\s+("[^"]*"|\'[^\']*\'))?\s*\)/',
            $Excerpt['text'],
            $m
        )) {
            // fallback: reference style images ![alt][ref]
            $Inline = parent::inlineImage($Excerpt);
            if ($Inline !== null) {
                unset(
                    $Inline['element']['attributes']['class'],
                    $Inline['element']['attributes']['target'],
                    $Inline['element']['attributes']['rel']
                );
            }
            return $Inline;
        }

        $alt = $m[1];
        $url = trim($m[2], '<>');
        $title = isset($m[3]) ? substr($m[3], 1, -1) : null;
        $extent = strlen($m[0]);

        // Obsidian size syntax in alt text: ![alt|300x200](...) or ![alt|300](...)
        $size = null;
        if (preg_match('/^(.*?)\|(\d*x\d*|\d+)$/', $alt, $sm)) {
            $alt = $sm[1];
            $size = ctype_digit($sm[2]) ? $sm[2] . 'x' : $sm[2];
        }

        /* ---------- external image (http, https, data:, //...) ---------- */
        if (preg_match('#^([a-z][a-z0-9+.-]*:|//)#i', $url)) {
            $attributes = array(
                'src' => $url,
                'alt' => $alt,
                'title' => $title,
            );

            if ($size !== null && preg_match('/^(\d*)x(\d*)$/', $size, $dm)) {
                $attributes['width'] = $dm[1] ?: null;
                $attributes['height'] = $dm[2] ?: null;
            }

            return array(
                'extent' => $extent,
                'element' => array(
                    'name' => 'img',
                    'attributes' => $attributes,
                ),
            );
        }

        /* ---------- local image (relative to current note) ---------- */
        // Obsidian encodes spaces etc. in markdown links (%20)
        $file = rawurldecode($url);

        // strip query string / leading "./"
        $file = preg_replace('/\?.*$/', '', $file);
        $file = preg_replace('#^(\./)+#', '', $file);

        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        if (!in_array($ext, $this->allowedImageTypes)) {
            return;
        }

        // "../" segments are resolved by the browser, since
        // buildInternalImage() produces an absolute src (uriPath + path + file)
        $Inline = $this->buildInternalImage(
            $file,
            array(
                'caption' => $alt !== '' ? $alt : null,
                'size' => $size,
                'align' => null,
            ),
            $extent
        );

        if ($title !== null) {
            $Inline['element']['elements'][0]['elements'][0]['attributes']['title'] = $title;
        }

        return $Inline;
    }

    protected function inlineInternalEmbed($Excerpt)
    {
        if (!preg_match('/^!\[\[(.+?)\]\]/', $Excerpt['text'], $m)) {
            return;
        }

        $raw = $m[1];
        $parts = explode('|', $raw);

        $file = $parts[0];
        $mod1 = $parts[1] ?? null;
        $mod2 = $parts[2] ?? null;

        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        $src = rtrim($this->uriPath . $this->path, '/') . '/' . $file;

        if (str_contains($file, '#')) {
            $ext = strtolower(pathinfo(explode('#', $file)[0], PATHINFO_EXTENSION));
        }

        /* ---------- Canvas (not embedded, rendered as internal link) ---------- */
        if ($ext === 'canvas') {
            $Inline = $this->inlineInternalLink(array('text' => substr($m[0], 1)));
            if ($Inline !== null) {
                $Inline['extent'] = strlen($m[0]);
            }
            return $Inline;
        }

        /* ---------- PDF ---------- */
        if ($ext === 'pdf') {
            return array(
                'extent' => strlen($m[0]),
                'element' => array(
                    'name' => 'embed',
                    'attributes' => array(
                        'src' => $src,
                        'type' => 'application/pdf',
                        'style' => 'min-height:100vh;width:100%',
                    ),
                ),
            );
        }

        /* ---------- Video / Audio ---------- */
        if (in_array($ext, array('mp4', 'm4a'))) {
            return array(
                'extent' => strlen($m[0]),
                'element' => array(
                    'name' => 'video',
                    'handler' => 'line',
                    'attributes' => array(
                        'controls' => true,
                        'src' => $src,
                        'type' => $ext === 'mp4' ? 'video/mp4' : 'audio/x-m4a',
                    ),
                    'text' =>
                    '<a class="internal-link" target="_blank" rel="noopener noreferrer" href="' .
                        $src . '">Download ' . basename($file) . '</a>',
                ),
            );
        }

        /* ---------- Image ---------- */
        if (in_array($ext, $this->allowedImageTypes)) {

            // syntax: image.png#caption=...&size=...
            if (str_contains($file, '#')) {
                return $this->buildInternalImageFromFragment(
                    $file,
                    strlen($m[0])
                );
            }

            // syntax: image.png|Caption|300x200|center
            return $this->buildInternalImageFromLegacy(
                $raw,
                strlen($m[0])
            );
        }
    }

    protected function buildInternalImage(string $file, array $attrs, int $extent)
    {
        $src = rtrim($this->uriPath . $this->path, '/') . '/' . $file;

        $class = 'images';
        $alt = $attrs['caption'] ?? 'image';
        $width = null;
        $height = null;

        if (!empty($attrs['align'])) {
            $class .= ' ' . $attrs['align'];
        }

        if (!empty($attrs['size']) && preg_match('/^(\d*)x(\d*)$/', $attrs['size'], $m)) {
            $width = $m[1] ?: null;
            $height = $m[2] ?: null;
        }

        return [
            'extent' => $extent,
            'element' => [
                'name' => 'p',
                'elements' => [
                    [
                        'name' => 'a',
                        'attributes' => [
                            'href' => '#',
                            'class' => 'pop',
                        ],
                        'elements' => [
                            [
                                'name' => 'img',
                                'attributes' => array_filter([
                                    'src' => $src,
                                    'class' => $class,
                                    'alt' => $alt,
                                    'width' => $width,
                                    'height' => $height,
                                ]),
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    protected function buildInternalImageFromFragment(string $file, int $extent)
    {
        [$file, $fragment] = explode('#', $file, 2);

        parse_str($fragment, $attrs);

        return $this->buildInternalImage(
            $file,
            [
                'caption' => $attrs['caption'] ?? null,
                'size' => $attrs['size'] ?? null,
                'align' => $attrs['align'] ?? null,
            ],
            $extent
        );
    }

    protected function buildInternalImageFromLegacy(string $raw, int $extent)
    {
        $parts = explode('|', $raw);
        $file = array_shift($parts);

        $attrs = [
            'caption' => null,
            'size' => null,
            'align' => null,
        ];

        foreach ($parts as $part) {
            if (preg_match('/^\d*x\d*$/', $part)) {
                $attrs['size'] = $part;
            } elseif (in_array($part, ['center', 'right'], true)) {
                $attrs['align'] = $part;
            } elseif ($part !== '') {
                $attrs['caption'] = $part;
            }
        }

        return $this->buildInternalImage($file, $attrs, $extent);
    }

    #
    # Footnotes
    # See: https://help.obsidian.md/Editing+and+formatting/Basic+formatting+syntax#Footnotes

    protected function footnoteId($label)
    {
        return preg_replace('/[^A-Za-z0-9_-]+/', '-', $label);
    }

    # definition: [^label]: text
    protected function blockFootnote($Line)
    {
        if (preg_match('/^\[\^(.+?)\]:[ ]?(.*)$/', $Line['text'], $matches)) {
            return array(
                'label' => $matches[1],
                'text' => $matches[2],
                'hidden' => true,
            );
        }
    }

    protected function blockFootnoteContinue($Line, $Block)
    {
        // next footnote definition starts
        if ($Line['text'][0] === '[' && preg_match('/^\[\^(.+?)\]:/', $Line['text'])) {
            return;
        }

        // remove footnote indentation, keep nested indentation (e.g. code)
        $text = preg_replace('/^[ ]{0,4}/', '', $Line['body']);

        if (isset($Block['interrupted'])) {
            // after an empty line only indented lines belong to the footnote
            if ($Line['indent'] >= 4) {
                $Block['text'] .= "\n\n" . $text;
                unset($Block['interrupted']);

                return $Block;
            }

            return;
        }

        // lazy continuation (line directly below)
        $Block['text'] .= "\n" . $text;

        return $Block;
    }

    protected function blockFootnoteComplete($Block)
    {
        $this->DefinitionData['Footnote'][$Block['label']] = array(
            'text' => $Block['text'],
            'count' => 0,
            'number' => null,
        );

        return $Block;
    }

    # reference in text: [^label]
    protected function inlineFootnoteMarker($Excerpt)
    {
        if (!preg_match('/^\[\^(.+?)\]/', $Excerpt['text'], $matches)) {
            return;
        }

        $label = $matches[1];

        if (!isset($this->DefinitionData['Footnote'][$label])) {
            return;
        }

        $Footnote = $this->DefinitionData['Footnote'][$label];
        $Footnote['count']++;

        if ($Footnote['number'] === null) {
            $Footnote['number'] = ++$this->footnoteCount;
        }

        $this->DefinitionData['Footnote'][$label] = $Footnote;

        $id = $this->footnoteId($label);

        return array(
            'extent' => strlen($matches[0]),
            'element' => array(
                'name' => 'sup',
                'attributes' => array(
                    'id' => 'fnref-' . $Footnote['count'] . '-' . $id,
                    'class' => 'footnote-ref',
                ),
                'elements' => array(
                    array(
                        'name' => 'a',
                        'text' => '[' . $Footnote['number'] . ']',
                        'attributes' => array(
                            'href' => '#fn-' . $id,
                            'class' => 'footnote-link',
                        ),
                    ),
                ),
            ),
        );
    }

    # inline footnote: ^[text]
    protected function inlineInlineFootnote($Excerpt)
    {
        if (!isset($Excerpt['text'][1]) || $Excerpt['text'][1] !== '[') {
            return;
        }

        // supports nested brackets, e.g. ^[see [link](note.md)]
        if (!preg_match('/^\^(\[((?:[^\[\]]++|(?1))*)\])/', $Excerpt['text'], $matches)) {
            return;
        }

        $text = trim($matches[2]);

        if ($text === '') {
            return;
        }

        // register as regular footnote with generated label
        $label = 'inline-fn-' . (++$this->inlineFootnoteCount);

        $this->DefinitionData['Footnote'][$label] = array(
            'text' => $text,
            'count' => 0,
            'number' => null,
        );

        // delegate to regular footnote marker
        $Inline = $this->inlineFootnoteMarker(array(
            'text' => '[^' . $label . ']',
            'context' => '[^' . $label . ']',
        ));

        if ($Inline === null) {
            return;
        }

        // adjust extent to original syntax length
        $Inline['extent'] = strlen($matches[0]);

        return $Inline;
    }

    # footnote list at the end of the document
    protected function buildFootnotes()
    {
        if (empty($this->DefinitionData['Footnote'])) {
            return '';
        }

        // only footnotes that are referenced in the text
        $footnotes = array_filter(
            $this->DefinitionData['Footnote'],
            fn($footnote) => $footnote['number'] !== null
        );

        if (empty($footnotes)) {
            return '';
        }

        uasort($footnotes, fn($a, $b) => $a['number'] <=> $b['number']);

        $items = array();

        foreach ($footnotes as $label => $footnote) {
            $id = $this->footnoteId($label);

            // use lines() instead of text(), text() would reset DefinitionData
            $content = trim($this->lines(explode("\n", $footnote['text'])), "\n");

            $backLinks = array();
            for ($i = 1; $i <= $footnote['count']; $i++) {
                $backLinks[] = '<a href="#fnref-' . $i . '-' . $id . '" class="footnote-backref footnote-link">&#8617;&#65038;</a>';
            }
            $backLinks = implode(' ', $backLinks);

            // place back link(s) inside the last paragraph
            if (substr($content, -4) === '</p>') {
                $content = substr_replace($content, '&#160;' . $backLinks . '</p>', -4);
            } else {
                $content .= "\n<p>" . $backLinks . '</p>';
            }

            $items[] = array(
                'name' => 'li',
                'attributes' => array(
                    'id' => 'fn-' . $id,
                    'class' => 'footnote-item',
                ),
                'rawHtml' => "\n" . $content . "\n",
                'allowRawHtmlInSafeMode' => true,
            );
        }

        return "\n" . $this->element(array(
            'name' => 'section',
            'attributes' => array('class' => 'footnotes'),
            'elements' => array(
                array(
                    'name' => 'hr',
                    'attributes' => array('class' => 'footnotes-sep'),
                ),
                array(
                    'name' => 'ol',
                    'attributes' => array('class' => 'footnotes-list'),
                    'elements' => $items,
                ),
            ),
        ));
    }

    protected function popupIconSvg()
    {
        return '<svg class="popup-icon" xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M8 3H5a2 2 0 0 0-2 2v3"></path>
        <path d="M21 8V5a2 2 0 0 0-2-2h-3"></path>
        <path d="M3 16v3a2 2 0 0 0 2 2h3"></path>
        <path d="M16 21h3a2 2 0 0 0 2-2v-3"></path>
    </svg>';
    }
}
