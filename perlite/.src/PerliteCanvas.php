<?php

/*!
 * Perlite v1.6.2 (https://github.com/secure-77/Perlite)
 * Author: sec77 (https://secure77.de)
 * Licensed under MIT (https://github.com/secure-77/Perlite/blob/main/LICENSE)
 */

namespace Perlite;

# read-only renderer for Obsidian canvas files (JSON Canvas, see https://jsoncanvas.org)
# the generated markup follows the Obsidian canvas DOM, so the canvas styles of app.css / themes apply
class PerliteCanvas
{
    const ARROW_LENGTH = 12;
    const ARROW_WIDTH = 7;

    protected $parserFactory;
    protected $rootDir;
    protected $startDir;
    protected $uriPath;
    protected $avFiles;
    protected $hideFolders;
    protected $iframeEmbeds;
    protected $imageTypes = array('png', 'jpg', 'jpeg', 'svg', 'gif', 'bmp', 'tif', 'tiff', 'webp', 'avif');
    protected $videoTypes = array('mp4', 'webm', 'ogv', 'mov', 'm4v');
    protected $audioTypes = array('mp3', 'm4a', 'wav', 'ogg', 'oga', 'flac', 'opus');

    protected $nodes = array();
    protected $wordCount = 0;
    protected $charCount = 0;

    /**
     * @param callable $parserFactory function (string $dir): PerliteParsedown, $dir is the vault relative folder ("/folder" or "")
     * @param string $iframeEmbeds websites of link cards: "off" (link only), "click" (load on click) or "auto"
     */
    public function __construct(callable $parserFactory, $rootDir, $startDir, $uriPath, array $avFiles, array $hideFolders = array(), $iframeEmbeds = 'click')
    {
        $this->parserFactory = $parserFactory;
        $this->rootDir = $rootDir;
        $this->startDir = $startDir;
        $this->uriPath = $uriPath;
        $this->avFiles = $avFiles;
        $this->hideFolders = $hideFolders;
        $this->iframeEmbeds = $iframeEmbeds;
    }

    public function getWordCount()
    {
        return $this->wordCount;
    }

    public function getCharCount()
    {
        return $this->charCount;
    }

    /**
     * render the canvas json to html
     * @param string $json content of the .canvas file
     * @param string $canvasDir vault relative folder of the canvas file ("/folder" or "")
     */
    public function render($json, $canvasDir = '')
    {
        $data = json_decode($json, true);
        if (!is_array($data)) {
            return '<div class="perlite-canvas-error">This canvas file could not be read.</div>';
        }

        $this->nodes = array();
        $this->wordCount = 0;
        $this->charCount = 0;

        foreach ($data['nodes'] ?? array() as $node) {
            $node = $this->normalizeNode($node);
            if ($node !== null) {
                $this->nodes[$node['id']] = $node;
            }
        }

        if (empty($this->nodes)) {
            return '<div class="perlite-canvas-error">This canvas is empty.</div>';
        }

        // bounding box, used by the frontend to fit the canvas into the view
        $minX = $minY = PHP_INT_MAX;
        $maxX = $maxY = PHP_INT_MIN;
        foreach ($this->nodes as $node) {
            $minX = min($minX, $node['x']);
            $minY = min($minY, $node['y']);
            $maxX = max($maxX, $node['x'] + $node['width']);
            $maxY = max($maxY, $node['y'] + $node['height']);
        }

        // groups are rendered first, so they stay in the background
        $groups = '';
        $cards = '';
        foreach ($this->nodes as $node) {
            if ($node['type'] === 'group') {
                $groups .= $this->renderNode($node, $canvasDir);
            } else {
                $cards .= $this->renderNode($node, $canvasDir);
            }
        }

        $edges = '';
        $labels = '';
        foreach ($data['edges'] ?? array() as $edge) {
            [$edgeSvg, $edgeLabel] = $this->renderEdge($edge);
            $edges .= $edgeSvg;
            $labels .= $edgeLabel;
        }

        $bbox = implode(',', array_map(array($this, 'num'), array($minX, $minY, $maxX, $maxY)));

        // unique pattern id, the same canvas can be shown in the page and in a popover
        $patternId = 'perlite-canvas-dots-' . bin2hex(random_bytes(4));

        return '
        <div class="perlite-canvas" data-bbox="' . $bbox . '">
            <div class="canvas-wrapper mod-readonly">
                <svg class="canvas-background"><pattern id="' . $patternId . '" class="perlite-canvas-dots" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="0.7" cy="0.7" r="0.7"></circle></pattern><rect x="0" y="0" width="100%" height="100%" fill="url(#' . $patternId . ')"></rect></svg>
                <div class="canvas">'
            . $groups
            . '<svg class="canvas-edges">' . $edges . '</svg>'
            . $cards
            . $labels . '
                </div>
                <div class="canvas-controls">
                    <div class="canvas-control-group">
                        <div class="canvas-control-item perlite-canvas-zoom-in" aria-label="Zoom in">' . $this->icon('M5 12h14M12 5v14') . '</div>
                        <div class="canvas-control-item perlite-canvas-zoom-out" aria-label="Zoom out">' . $this->icon('M5 12h14') . '</div>
                        <div class="canvas-control-item perlite-canvas-fit" aria-label="Zoom to fit">' . $this->icon('M8 3H5a2 2 0 0 0-2 2v3M21 8V5a2 2 0 0 0-2-2h-3M3 16v3a2 2 0 0 0 2 2h3M16 21h3a2 2 0 0 0 2-2v-3') . '</div>
                    </div>
                </div>
            </div>
        </div>';
    }

    protected function normalizeNode($node)
    {
        if (!is_array($node) || !isset($node['id']) || !is_scalar($node['id'])) {
            return null;
        }

        foreach (array('x', 'y', 'width', 'height') as $key) {
            if (!isset($node[$key]) || !is_numeric($node[$key])) {
                return null;
            }
            $node[$key] = (float) $node[$key];
        }

        $node['id'] = (string) $node['id'];
        $node['type'] = isset($node['type']) && is_string($node['type']) ? $node['type'] : 'text';
        $node['width'] = max($node['width'], 1);
        $node['height'] = max($node['height'], 1);

        return $node;
    }

    //
    // nodes
    //

    protected function renderNode(array $node, $canvasDir)
    {
        [$colorClass, $colorStyle] = $this->colorAttributes($node['color'] ?? null);

        $class = 'canvas-node' . ($node['type'] === 'group' ? ' canvas-node-group' : '') . $colorClass;
        $style = 'left:' . $this->num($node['x']) . 'px;top:' . $this->num($node['y']) . 'px;'
            . 'width:' . $this->num($node['width']) . 'px;height:' . $this->num($node['height']) . 'px;'
            . '--canvas-node-width:' . $this->num($node['width']) . 'px;--canvas-node-height:' . $this->num($node['height']) . 'px;'
            . $colorStyle;

        $label = '';
        switch ($node['type']) {
            case 'text':
                $text = is_string($node['text'] ?? null) ? $node['text'] : '';
                $this->wordCount += str_word_count($text);
                $this->charCount += strlen($text);
                $content = $this->markdownContent($this->parse($text, $canvasDir));
                break;
            case 'file':
                [$label, $content] = $this->renderFileNode($node);
                break;
            case 'link':
                [$label, $content] = $this->renderLinkNode($node);
                break;
            case 'group':
                $content = '<div class="canvas-node-content"></div>';
                if (is_string($node['label'] ?? null) && $node['label'] !== '') {
                    $label = '<div class="canvas-group-label">' . $this->e($node['label']) . '</div>';
                }
                break;
            default:
                $content = $this->placeholder('Unsupported card type: ' . $node['type']);
        }

        return '<div class="' . $class . '" style="' . $this->e($style) . '">' . $label
            . '<div class="canvas-node-container">' . $content . '</div></div>';
    }

    protected function renderFileNode(array $node)
    {
        $file = $this->cleanVaultPath($node['file'] ?? null);
        if ($file === null) {
            return array('', $this->placeholder('File not found'));
        }

        $fileName = basename($file);
        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

        // markdown note, embed the rendered content
        if ($ext === 'md') {
            $notePath = '/' . substr($file, 0, -3);
            if (!in_array($notePath, $this->avFiles, true)) {
                return array('', $this->placeholder('File not found: ' . $fileName));
            }

            $content = file_get_contents($this->rootDir . $notePath . '.md');
            $anchor = '';
            if (is_string($node['subpath'] ?? null) && str_starts_with($node['subpath'], '#')) {
                $anchor = $node['subpath'];
            }

            $label = $this->nodeLabel(
                '<a class="internal-link" href="' . $this->e($this->noteUrl($notePath) . $anchor) . '">' . $this->e(substr($fileName, 0, -3)) . '</a>'
            );
            return array($label, $this->markdownContent($this->parse((string) $content, $this->dirOf($notePath))));
        }

        // other canvas, only link it
        if ($ext === 'canvas') {
            $canvasPath = '/' . $file;
            if (!in_array($canvasPath, $this->avFiles, true)) {
                return array('', $this->placeholder('File not found: ' . $fileName));
            }
            $link = '<a class="internal-link" href="' . $this->e($this->noteUrl($canvasPath)) . '">' . $this->e($fileName) . '</a>';
            return array($this->nodeLabel($link), '<div class="canvas-node-content"><div class="canvas-node-placeholder">' . $link . '</div></div>');
        }

        if (!$this->isVaultFile($file)) {
            return array('', $this->placeholder('File not found: ' . $fileName));
        }

        $url = $this->fileUrl($file);
        $label = $this->nodeLabel('<a href="' . $this->e($url) . '" target="_blank" rel="noopener noreferrer">' . $this->e($fileName) . '</a>');

        if (in_array($ext, $this->imageTypes, true)) {
            return array($label, '<div class="canvas-node-content media-embed image-embed"><img src="' . $this->e($url) . '" alt="' . $this->e($fileName) . '" draggable="false"></div>');
        }

        if (in_array($ext, $this->videoTypes, true)) {
            return array($label, '<div class="canvas-node-content media-embed video-embed"><video src="' . $this->e($url) . '" controls preload="metadata"></video></div>');
        }

        if (in_array($ext, $this->audioTypes, true)) {
            return array($label, '<div class="canvas-node-content media-embed audio-embed"><audio src="' . $this->e($url) . '" controls preload="metadata"></audio></div>');
        }

        return array($label, '<div class="canvas-node-content"><div class="canvas-node-placeholder"><a href="' . $this->e($url) . '" target="_blank" rel="noopener noreferrer">' . $this->e($fileName) . '</a></div></div>');
    }

    protected function renderLinkNode(array $node)
    {
        $url = $node['url'] ?? null;
        if (!is_string($url) || !preg_match('#^https?://#i', $url) || filter_var($url, FILTER_VALIDATE_URL) === false) {
            return array('', $this->placeholder('Invalid link'));
        }

        $host = parse_url($url, PHP_URL_HOST) ?: $url;
        $link = '<a href="' . $this->e($url) . '" target="_blank" rel="noopener noreferrer">';
        $label = $this->nodeLabel($link . $this->e($url) . '</a>');

        // video platforms allow embedding, always show the player
        $player = $this->playerUrl($url);
        if ($player !== null) {
            return array($label, $this->embedContent($this->iframe($player, 'strict-origin-when-cross-origin')));
        }

        if ($this->iframeEmbeds === 'auto') {
            return array($label, $this->embedContent($this->iframe($url, 'no-referrer')));
        }

        $content = '<div class="canvas-node-content perlite-canvas-link">' . $link
            . '<span class="perlite-canvas-link-host">' . $this->e($host) . '</span>'
            . '<span class="perlite-canvas-link-url">' . $this->e($url) . '</span></a>';

        // load the website on click, the iframe markup is kept in a template until then
        if ($this->iframeEmbeds === 'click') {
            $content .= '<button class="mod-cta perlite-canvas-load">Load website</button>'
                . '<template>' . $this->iframe($url, 'no-referrer') . '</template>';
        }

        return array($label, $content . '</div>');
    }

    // embed url for youtube and vimeo links
    protected function playerUrl($url)
    {
        // See: https://stackoverflow.com/a/64320469 (same as PerliteParsedown::blockYouTube)
        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $m)) {
            return 'https://www.youtube-nocookie.com/embed/' . $m[1];
        }

        if (preg_match('%^https?://(?:www\.|player\.)?vimeo\.com/(?:video/)?(\d+)%i', $url, $m)) {
            return 'https://player.vimeo.com/video/' . $m[1];
        }

        return null;
    }

    // sandboxed iframe, the blocker keeps pan & zoom working until the card is focused
    protected function iframe($src, $referrerPolicy)
    {
        return '<iframe src="' . $this->e($src) . '" sandbox="allow-scripts allow-same-origin allow-presentation allow-popups allow-forms"'
            . ' allow="fullscreen; picture-in-picture; encrypted-media" allowfullscreen loading="lazy" referrerpolicy="' . $referrerPolicy . '"></iframe>'
            . '<div class="canvas-node-content-blocker"></div>';
    }

    protected function embedContent($iframe)
    {
        return '<div class="canvas-node-content perlite-canvas-embed">' . $iframe . '</div>';
    }

    protected function markdownContent($html)
    {
        return '<div class="canvas-node-content markdown-embed"><div class="markdown-embed-content">'
            . '<div class="markdown-preview-view markdown-rendered"><div class="markdown-preview-sizer markdown-preview-section">'
            . $html . '</div></div></div></div>';
    }

    protected function placeholder($text)
    {
        return '<div class="canvas-node-content"><div class="canvas-node-placeholder perlite-canvas-missing">' . $this->e($text) . '</div></div>';
    }

    protected function nodeLabel($html)
    {
        return '<div class="canvas-node-label">' . $html . '</div>';
    }

    protected function parse($markdown, $dir)
    {
        $parser = call_user_func($this->parserFactory, $dir);
        return $parser->text($markdown);
    }

    //
    // edges
    //

    protected function renderEdge($edge)
    {
        if (!is_array($edge)) {
            return array('', '');
        }

        $from = $this->nodes[(string) ($edge['fromNode'] ?? '')] ?? null;
        $to = $this->nodes[(string) ($edge['toNode'] ?? '')] ?? null;
        if ($from === null || $to === null) {
            return array('', '');
        }

        $fromSide = $this->side($edge['fromSide'] ?? null, $from, $to);
        $toSide = $this->side($edge['toSide'] ?? null, $to, $from);

        [$x1, $y1, $nx1, $ny1] = $this->anchor($from, $fromSide);
        [$x2, $y2, $nx2, $ny2] = $this->anchor($to, $toSide);

        $fromArrow = ($edge['fromEnd'] ?? 'none') === 'arrow';
        $toArrow = ($edge['toEnd'] ?? 'arrow') === 'arrow';

        // the line ends at the base of the arrow head
        $sx = $x1 + ($fromArrow ? $nx1 * self::ARROW_LENGTH : 0);
        $sy = $y1 + ($fromArrow ? $ny1 * self::ARROW_LENGTH : 0);
        $ex = $x2 + ($toArrow ? $nx2 * self::ARROW_LENGTH : 0);
        $ey = $y2 + ($toArrow ? $ny2 * self::ARROW_LENGTH : 0);

        $curve = max(40, min(250, hypot($ex - $sx, $ey - $sy) / 2));
        $c1x = $sx + $nx1 * $curve;
        $c1y = $sy + $ny1 * $curve;
        $c2x = $ex + $nx2 * $curve;
        $c2y = $ey + $ny2 * $curve;

        $path = 'M' . $this->num($sx) . ',' . $this->num($sy)
            . ' C' . $this->num($c1x) . ',' . $this->num($c1y)
            . ' ' . $this->num($c2x) . ',' . $this->num($c2y)
            . ' ' . $this->num($ex) . ',' . $this->num($ey);

        [$colorClass, $colorStyle] = $this->colorAttributes($edge['color'] ?? null);

        $svg = '<g class="' . trim($colorClass) . '"' . ($colorStyle !== '' ? ' style="' . $this->e($colorStyle) . '"' : '') . '>'
            . '<path class="canvas-display-path" d="' . $path . '"></path>'
            . '<path class="canvas-interaction-path" d="' . $path . '"></path>'
            . ($fromArrow ? $this->arrow($x1, $y1, $nx1, $ny1) : '')
            . ($toArrow ? $this->arrow($x2, $y2, $nx2, $ny2) : '')
            . '</g>';

        $label = '';
        if (is_string($edge['label'] ?? null) && $edge['label'] !== '') {
            // point of the bezier curve at t = 0.5
            $mx = ($sx + 3 * $c1x + 3 * $c2x + $ex) / 8;
            $my = ($sy + 3 * $c1y + 3 * $c2y + $ey) / 8;
            $label = '<div class="canvas-path-label-wrapper" style="left:' . $this->num($mx) . 'px;top:' . $this->num($my) . 'px">'
                . '<div class="canvas-path-label">' . $this->e($edge['label']) . '</div></div>';
        }

        return array($svg, $label);
    }

    // use the given side or pick the side facing the other node
    protected function side($side, array $node, array $other)
    {
        if (in_array($side, array('top', 'right', 'bottom', 'left'), true)) {
            return $side;
        }

        $dx = ($other['x'] + $other['width'] / 2) - ($node['x'] + $node['width'] / 2);
        $dy = ($other['y'] + $other['height'] / 2) - ($node['y'] + $node['height'] / 2);

        if (abs($dx) > abs($dy)) {
            return $dx > 0 ? 'right' : 'left';
        }
        return $dy > 0 ? 'bottom' : 'top';
    }

    // connection point of a node side and its outward normal
    protected function anchor(array $node, $side)
    {
        switch ($side) {
            case 'top':
                return array($node['x'] + $node['width'] / 2, $node['y'], 0, -1);
            case 'bottom':
                return array($node['x'] + $node['width'] / 2, $node['y'] + $node['height'], 0, 1);
            case 'left':
                return array($node['x'], $node['y'] + $node['height'] / 2, -1, 0);
            default:
                return array($node['x'] + $node['width'], $node['y'] + $node['height'] / 2, 1, 0);
        }
    }

    // arrow head with the tip on the node border, pointing into the node
    protected function arrow($x, $y, $nx, $ny)
    {
        $bx = $x + $nx * self::ARROW_LENGTH;
        $by = $y + $ny * self::ARROW_LENGTH;
        $px = -$ny * self::ARROW_WIDTH;
        $py = $nx * self::ARROW_WIDTH;

        $points = $this->num($x) . ',' . $this->num($y) . ' '
            . $this->num($bx + $px) . ',' . $this->num($by + $py) . ' '
            . $this->num($bx - $px) . ',' . $this->num($by - $py);

        return '<polygon class="perlite-canvas-path-end" points="' . $points . '"></polygon>';
    }

    //
    // helpers
    //

    // preset colors "1" - "6" or a hex color
    protected function colorAttributes($color)
    {
        if (!is_string($color) && !is_int($color)) {
            return array('', '');
        }

        $color = (string) $color;
        if (preg_match('/^[1-6]$/', $color)) {
            return array(' is-themed mod-canvas-color-' . $color, '');
        }

        if (preg_match('/^#([0-9a-f]{3}|[0-9a-f]{6})$/i', $color, $m)) {
            $hex = strlen($m[1]) === 3 ? preg_replace('/(.)/', '$1$1', $m[1]) : $m[1];
            $rgb = implode(', ', array_map('hexdec', str_split($hex, 2)));
            return array(' is-themed', '--canvas-color:' . $rgb . ';');
        }

        return array('', '');
    }

    // vault relative path without leading slash, null for traversal or hidden folders
    protected function cleanVaultPath($file)
    {
        if (!is_string($file) || $file === '' || str_contains($file, "\0")) {
            return null;
        }

        $file = ltrim(str_replace('\\', '/', $file), '/');
        $segments = explode('/', $file);

        foreach ($segments as $i => $segment) {
            if ($segment === '' || $segment === '.' || $segment === '..') {
                return null;
            }
            $isFolder = $i < count($segments) - 1;
            if ($isFolder && (str_starts_with($segment, '.') || in_array($segment, $this->hideFolders, true))) {
                return null;
            }
        }

        return $file;
    }

    protected function isVaultFile($file)
    {
        $root = realpath($this->rootDir);
        $real = realpath($this->rootDir . '/' . $file);

        return $root !== false && $real !== false && is_file($real)
            && str_starts_with($real, $root . DIRECTORY_SEPARATOR);
    }

    // folder of a vault path ("/folder/note" -> "/folder")
    protected function dirOf($path)
    {
        $n = strrpos($path, '/');
        return $n === false ? '' : substr($path, 0, $n);
    }

    // perlite url of a note (same slug format as PerliteParsedown internal links)
    protected function noteUrl($path)
    {
        $slug = ltrim($path, '/');
        $slug = str_replace('~', '%80', $slug);
        $slug = str_replace('-', '~', $slug);
        $slug = str_replace(' ', '-', $slug);

        return $this->uriPath . $slug;
    }

    // direct url of a vault file, served by the webserver
    protected function fileUrl($file)
    {
        $urlPath = ($this->startDir !== '' ? $this->startDir . '/' : '') . $file;
        return $this->uriPath . implode('/', array_map('rawurlencode', explode('/', $urlPath)));
    }

    protected function icon($path)
    {
        return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="svg-icon"><path d="' . $path . '"></path></svg>';
    }

    protected function num($value)
    {
        return rtrim(rtrim(number_format((float) $value, 2, '.', ''), '0'), '.');
    }

    protected function e($text)
    {
        return htmlspecialchars((string) $text, ENT_QUOTES, 'UTF-8');
    }
}
