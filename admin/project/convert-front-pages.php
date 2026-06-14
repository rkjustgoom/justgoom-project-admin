<?php

$root = __DIR__;
$frontDir = dirname($root) . DIRECTORY_SEPARATOR . 'front';
$pagesDir = $root . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'front' . DIRECTORY_SEPARATOR . 'pages';
$usersDir = $root . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'front' . DIRECTORY_SEPARATOR . 'users';

if (! is_dir($pagesDir)) {
    mkdir($pagesDir, 0755, true);
}
if (! is_dir($usersDir)) {
    mkdir($usersDir, 0755, true);
}

$linkReplacements = [
    'href="index.html"' => 'href="{{ route(\'front.home\') }}"',
    'href="about.html"' => 'href="{{ route(\'front.about\') }}"',
    'href="articles.html"' => 'href="{{ route(\'front.articles\') }}"',
    'href="calculators.html"' => 'href="{{ route(\'front.calculators\') }}"',
    'href="categories.html"' => 'href="{{ route(\'front.categories\') }}"',
    'href="category-details.html"' => 'href="{{ route(\'front.category-details\') }}"',
    'href="contact.html"' => 'href="{{ route(\'front.contact\') }}"',
    'href="all_profiles.html"' => 'href="{{ route(\'front.all-profiles\') }}"',
    'href="profile.html"' => 'href="{{ route(\'front.profile\') }}"',
    'href="login.html"' => 'href="{{ route(\'front.login\') }}"',
    'href="register.html"' => 'href="{{ route(\'front.register\') }}"',
    'action="all_profiles.html"' => 'action="{{ route(\'front.all-profiles\') }}"',
];

$userLinkReplacements = [
    'href="index.html"' => 'href="{{ route(\'front.users.dashboard\') }}"',
    'href="analytics.html"' => 'href="{{ route(\'front.users.analytics\') }}"',
    'href="articles.html"' => 'href="{{ route(\'front.users.articles\') }}"',
    'href="article-form.html"' => 'href="{{ route(\'front.users.article-form\') }}"',
    'href="banners.html"' => 'href="{{ route(\'front.users.banners\') }}"',
    'href="banner-form.html"' => 'href="{{ route(\'front.users.banner-form\') }}"',
    'href="change-password.html"' => 'href="{{ route(\'front.users.change-password\') }}"',
    'href="delete.html"' => 'href="{{ route(\'front.users.delete\') }}"',
    'href="document-form.html"' => 'href="{{ route(\'front.users.document-form\') }}"',
    'href="documents.html"' => 'href="{{ route(\'front.users.documents\') }}"',
    'href="inquiries.html"' => 'href="{{ route(\'front.users.inquiries\') }}"',
    'href="inquiry-reply.html"' => 'href="{{ route(\'front.users.inquiry-reply\') }}"',
    'href="inquiry-view.html"' => 'href="{{ route(\'front.users.inquiry-view\') }}"',
    'href="notifications.html"' => 'href="{{ route(\'front.users.notifications\') }}"',
    'href="notification-view.html"' => 'href="{{ route(\'front.users.notification-view\') }}"',
    'href="profile.html"' => 'href="{{ route(\'front.users.profile\') }}"',
    'href="reviews.html"' => 'href="{{ route(\'front.users.reviews\') }}"',
    'href="review-view.html"' => 'href="{{ route(\'front.users.review-view\') }}"',
    'href="service-add.html"' => 'href="{{ route(\'front.users.service-add\') }}"',
    'href="service-edit.html"' => 'href="{{ route(\'front.users.service-edit\') }}"',
    'href="service-form.html"' => 'href="{{ route(\'front.users.service-form\') }}"',
    'href="services.html"' => 'href="{{ route(\'front.users.services\') }}"',
    'href="subscription.html"' => 'href="{{ route(\'front.users.subscription\') }}"',
    'href="team.html"' => 'href="{{ route(\'front.users.team\') }}"',
    'href="team-add.html"' => 'href="{{ route(\'front.users.team-add\') }}"',
    'href="team-edit.html"' => 'href="{{ route(\'front.users.team-edit\') }}"',
    'href="team-form.html"' => 'href="{{ route(\'front.users.team-form\') }}"',
    'href="videos.html"' => 'href="{{ route(\'front.users.videos\') }}"',
    'href="video-form.html"' => 'href="{{ route(\'front.users.video-form\') }}"',
    'href="../index.html"' => 'href="{{ route(\'front.home\') }}"',
    'href="../login.html"' => 'href="{{ route(\'front.login\') }}"',
];

function convertAssetPaths(string $html): string
{
    $html = preg_replace('/\bsrc="assets\/([^"]+)"/', "src=\"{{ asset('front/assets/$1') }}\"", $html);
    $html = preg_replace('/\bhref="assets\/([^"]+)"/', "href=\"{{ asset('front/assets/$1') }}\"", $html);
    $html = preg_replace('/\bsrc="\.\.\/assets\/([^"]+)"/', "src=\"{{ asset('front/assets/$1') }}\"", $html);
    $html = preg_replace('/\bhref="\.\.\/assets\/([^"]+)"/', "href=\"{{ asset('front/assets/$1') }}\"", $html);

    return $html;
}

function extractBodyInner(string $html): string
{
    if (! preg_match('/<body[^>]*>(.*)<\/body>/is', $html, $matches)) {
        return $html;
    }

    return trim($matches[1]);
}

function stripLayoutParts(string $content): string
{
    $patterns = [
        '/<div[^>]*id="site-header"[^>]*><\/div>\s*/i',
        '/<div[^>]*id="site-header"[^>]*data-include="header\.html"[^>]*><\/div>\s*/i',
        '/<div[^>]*id="site-footer"[^>]*><\/div>\s*/i',
        '/<div[^>]*id="site-footer"[^>]*data-include="footer[^"]*"[^>]*><\/div>\s*/i',
        '/<div[^>]*data-user-layout="header"[^>]*><\/div>\s*/i',
        '/<div[^>]*data-user-layout="footer"[^>]*><\/div>\s*/i',
        '/<script[^>]*src="(\.\.\/)?assets\/js\/includes\.js"[^>]*><\/script>\s*/i',
        '/<script[^>]*src="(\.\.\/)?assets\/js\/users-includes\.js"[^>]*><\/script>\s*/i',
        '/<script[^>]*src="(\.\.\/)?assets\/js\/users-nav\.js"[^>]*><\/script>\s*/i',
        '/<script[^>]*src="(\.\.\/)?assets\/js\/users\.js"[^>]*><\/script>\s*/i',
        '/<script[^>]*src="(\.\.\/)?assets\/js\/main\.js"[^>]*><\/script>\s*/i',
        '/<script[^>]*src="(\.\.\/)?assets\/js\/categories-data\.js"[^>]*><\/script>\s*/i',
        '/<script[^>]*src="(\.\.\/)?assets\/js\/health\.js"[^>]*><\/script>\s*/i',
    ];

    foreach ($patterns as $pattern) {
        $content = preg_replace($pattern, '', $content);
    }

    return trim($content);
}

function parseBodyAttributes(string $html): array
{
    $attrs = [
        'class' => '',
        'data-page' => '',
        'data-title' => '',
    ];

    if (preg_match('/<body([^>]*)>/i', $html, $matches)) {
        $bodyAttrs = $matches[1];
        foreach (['class', 'data-page', 'data-title'] as $name) {
            if (preg_match('/'.$name.'="([^"]*)"/i', $bodyAttrs, $attrMatch)) {
                $attrs[$name] = $attrMatch[1];
            }
        }
    }

    return $attrs;
}

function parseTitle(string $html): string
{
    if (preg_match('/<title>(.*?)<\/title>/is', $html, $matches)) {
        return trim($matches[1]);
    }

    return 'Just Goom LLP';
}

function parseMetaDescription(string $html): ?string
{
    if (preg_match('/<meta\s+name="description"\s+content="([^"]*)"/i', $html, $matches)) {
        return trim($matches[1]);
    }

    return null;
}

function applyLinkReplacements(string $content, array $replacements): string
{
    return str_replace(array_keys($replacements), array_values($replacements), $content);
}

function buildPublicBlade(string $html, string $viewName): string
{
    $title = parseTitle($html);
    $meta = parseMetaDescription($html);
    $body = parseBodyAttributes($html);
    $content = stripLayoutParts(extractBodyInner($html));
    $content = applyLinkReplacements($content, $GLOBALS['linkReplacements'] ?? []);
    $content = convertAssetPaths($content);

    $bodyAttrParts = [];
    if ($body['class'] !== '') {
        $bodyAttrParts[] = 'class="'.$body['class'].'"';
    }
    if ($body['data-page'] !== '') {
        $bodyAttrParts[] = 'data-page="'.$body['data-page'].'"';
    }

    $blade = "@extends('front.layouts.app')\n\n";
    $blade .= "@section('title', ".var_export($title, true).")\n";
    if ($meta) {
        $blade .= "@section('meta_description', ".var_export($meta, true).")\n";
    }
    $blade .= "@section('body_attrs', ".var_export(implode(' ', $bodyAttrParts), true).")\n\n";
    $blade .= "@section('content')\n".$content."\n@endsection\n";

    if (str_contains($content, 'categories-data.js') || str_contains($html, 'categories-data.js')) {
        $blade .= "\n@push('scripts')\n<script src=\"{{ asset('front/assets/js/categories-data.js') }}\"></script>\n@endpush\n";
    }
    if (str_contains($html, 'health.js')) {
        $blade .= "\n@push('scripts')\n<script src=\"{{ asset('front/assets/js/health.js') }}\"></script>\n@endpush\n";
    }

    return $blade;
}

function buildUserBlade(string $html, string $viewName): string
{
    $title = parseTitle($html);
    $body = parseBodyAttributes($html);
    $content = stripLayoutParts(extractBodyInner($html));
    $content = applyLinkReplacements($content, $GLOBALS['userLinkReplacements'] ?? []);
    $content = convertAssetPaths($content);

    if (! str_contains($content, 'user-content')) {
        $content = "<div class=\"user-content\">\n".$content."\n</div>";
    }

    $bodyAttrParts = ['class="user-panel-body"'];
    if ($body['data-page'] !== '') {
        $bodyAttrParts[] = 'data-page="'.$body['data-page'].'"';
    }
    if ($body['data-title'] !== '') {
        $bodyAttrParts[] = 'data-title="'.$body['data-title'].'"';
    }

    $blade = "@extends('front.layouts.user')\n\n";
    $blade .= "@section('title', ".var_export($title, true).")\n";
    $blade .= "@section('page_title', ".var_export($body['data-title'] ?: $title, true).")\n";
    $blade .= "@section('body_attrs', ".var_export(implode(' ', $bodyAttrParts), true).")\n\n";
    $blade .= "@section('content')\n".$content."\n@endsection\n";

    return $blade;
}

$skipPublic = ['login.html', 'register.html', 'header.html', 'footer.html', 'footer-mini.html'];
$publicFiles = glob($frontDir . DIRECTORY_SEPARATOR . '*.html') ?: [];

foreach ($publicFiles as $file) {
    $basename = basename($file);
    if (in_array($basename, $skipPublic, true)) {
        continue;
    }

    $viewName = str_replace('.html', '', $basename);
    $html = file_get_contents($file);
    $blade = buildPublicBlade($html, $viewName);
    file_put_contents($pagesDir . DIRECTORY_SEPARATOR . $viewName . '.blade.php', $blade);
    echo "Converted public page: {$viewName}\n";
}

$userFiles = glob($frontDir . DIRECTORY_SEPARATOR . 'users' . DIRECTORY_SEPARATOR . '*.html') ?: [];
$skipUser = ['header.html', 'sidebar.html', 'footer.html'];

foreach ($userFiles as $file) {
    $basename = basename($file);
    if (in_array($basename, $skipUser, true)) {
        continue;
    }

    $viewName = str_replace('.html', '', $basename);
    $html = file_get_contents($file);
    $blade = buildUserBlade($html, $viewName);
    file_put_contents($usersDir . DIRECTORY_SEPARATOR . $viewName . '.blade.php', $blade);
    echo "Converted user page: {$viewName}\n";
}

echo "Done.\n";
