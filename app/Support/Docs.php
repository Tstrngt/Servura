<?php

namespace App\Support;

use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\Extension\HeadingPermalink\HeadingPermalinkExtension;
use League\CommonMark\MarkdownConverter;
use Symfony\Component\Yaml\Yaml;
use Illuminate\Support\Str;
use Throwable;

/**
 * Markdown-gebaseerd documentatieplatform.
 *
 * Structuur:
 *   resources/docs/{categorie}/_category.json   {"title","description","order","icon"}
 *   resources/docs/{categorie}/{pagina}.md      YAML front matter: title, description, order
 */
class Docs
{
    public static function basePath(): string
    {
        return resource_path('docs');
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function categories(): array
    {
        $categories = [];

        foreach (glob(self::basePath().'/*', GLOB_ONLYDIR) ?: [] as $dir) {
            $slug = basename($dir);
            $meta = [];
            $metaFile = $dir.DIRECTORY_SEPARATOR.'_category.json';
            if (is_file($metaFile)) {
                $meta = json_decode((string) file_get_contents($metaFile), true) ?: [];
            }

            $categories[] = [
                'slug' => $slug,
                'title' => $meta['title'] ?? Str::headline(str_replace('-', ' ', $slug)),
                'description' => $meta['description'] ?? '',
                'order' => (int) ($meta['order'] ?? 100),
                'pages' => self::pages($slug),
            ];
        }

        usort($categories, fn ($a, $b) => [$a['order'], $a['title']] <=> [$b['order'], $b['title']]);

        return $categories;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function pages(string $category): array
    {
        $pages = [];

        foreach (glob(self::basePath().'/'.$category.'/*.md') ?: [] as $file) {
            [$meta] = self::parse((string) file_get_contents($file));

            $pages[] = [
                'slug' => basename($file, '.md'),
                'title' => $meta['title'] ?? Str::headline(basename($file, '.md')),
                'description' => $meta['description'] ?? '',
                'order' => (int) ($meta['order'] ?? 100),
                'draft' => (bool) ($meta['draft'] ?? false),
                'featured' => (bool) ($meta['featured'] ?? false),
            ];
        }

        usort($pages, fn ($a, $b) => [$a['order'], $a['title']] <=> [$b['order'], $b['title']]);

        return $pages;
    }

    public static function page(string $category, string $page): ?array
    {
        if (! preg_match('/^[a-z0-9-]+$/', $category) || ! preg_match('/^[a-z0-9-]+$/', $page)) {
            return null;
        }

        $file = self::basePath().'/'.$category.'/'.$page.'.md';
        if (! is_file($file)) {
            return null;
        }

        [$meta, $body] = self::parse((string) file_get_contents($file));
        $html = self::converter()->convert($body)->getContent();

        return [
            'slug' => $page,
            'category' => $category,
            'title' => $meta['title'] ?? Str::headline($page),
            'description' => $meta['description'] ?? '',
            'draft' => (bool) ($meta['draft'] ?? false),
            'last_reviewed' => $meta['last_reviewed'] ?? null,
            'html' => $html,
            'toc' => self::toc($html),
            'related' => self::related($category, $meta['related'] ?? []),
        ];
    }

    /**
     * Zoek in titels, omschrijvingen, zoekwoorden en artikelinhoud.
     * Hoofdletterongevoelig; geeft lege lijst bij een lege query.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function search(string $query): array
    {
        $query = mb_strtolower(trim($query));
        if ($query === '') {
            return [];
        }

        $terms = array_filter(preg_split('/\s+/u', $query));
        $results = [];

        foreach (self::categories() as $category) {
            foreach (glob(self::basePath().'/'.$category['slug'].'/*.md') ?: [] as $file) {
                [$meta, $body] = self::parse((string) file_get_contents($file));

                $haystack = mb_strtolower(implode("\n", [
                    $meta['title'] ?? '',
                    $meta['description'] ?? '',
                    implode(' ', (array) ($meta['keywords'] ?? [])),
                    $body,
                ]));

                $matched = array_filter($terms, fn ($term) => str_contains($haystack, $term));
                if (empty($matched)) {
                    continue;
                }

                $results[] = [
                    'category' => $category['slug'],
                    'category_title' => $category['title'],
                    'slug' => basename($file, '.md'),
                    'title' => $meta['title'] ?? Str::headline(basename($file, '.md')),
                    'description' => $meta['description'] ?? '',
                    'draft' => (bool) ($meta['draft'] ?? false),
                    'score' => count($matched),
                ];
            }
        }

        usort($results, fn ($a, $b) => $b['score'] <=> $a['score']);

        return $results;
    }

    /**
     * @return array<int, array<string, string>>
     */
    private static function related(string $category, array $related): array
    {
        $pages = self::pages($category);
        $resolved = [];

        foreach ($related as $ref) {
            // 'categorie/slug' of 'slug' binnen dezelfde categorie
            if (str_contains((string) $ref, '/')) {
                [$refCategory, $refSlug] = explode('/', (string) $ref, 2);
                $refPages = self::pages($refCategory);
                $match = collect($refPages)->firstWhere('slug', $refSlug);
                if ($match) {
                    $resolved[] = ['category' => $refCategory, 'slug' => $match['slug'], 'title' => $match['title']];
                }
                continue;
            }

            $match = collect($pages)->firstWhere('slug', $ref);
            if ($match) {
                $resolved[] = ['category' => $category, 'slug' => $match['slug'], 'title' => $match['title']];
            }
        }

        return $resolved;
    }

    /**
     * URL naar een documentatiepagina. Op docs.servura.nl is de basis de root,
     * op het hoofddomein valt alles onder /docs.
     */
    public static function url(string $category = '', string $page = ''): string
    {
        $base = str_starts_with((string) request()->getHost(), 'docs.') ? '' : '/docs';

        return $base.($category === '' ? '' : '/'.$category).($page === '' ? '' : '/'.$page);
    }

    /**
     * Absolute URL naar een route op het hoofddomein (nodig omdat links op
     * docs.servura.nl anders naar niet-bestaande docs-paden zouden wijzen).
     */
    public static function siteUrl(string $routeName, array $parameters = []): string
    {
        return rtrim((string) config('app.url'), '/').route($routeName, $parameters, false);
    }

    /**
     * Splits YAML front matter van de markdown body.
     *
     * @return array{0: array<string, mixed>, 1: string}
     */
    private static function parse(string $raw): array
    {
        $meta = [];
        $body = $raw;

        if (preg_match('/\A---\s*\r?\n(.*?)\r?\n---\s*\r?\n?/s', $raw, $m)) {
            try {
                $meta = Yaml::parse($m[1]) ?: [];
            } catch (Throwable $e) {
                $meta = [];
            }
            $body = substr($raw, strlen($m[0]));
        }

        if (empty($meta['title']) && preg_match('/^#\s+(.+)$/m', $body, $m)) {
            $meta['title'] = trim($m[1]);
        }

        return [$meta, $body];
    }

    private static function converter(): MarkdownConverter
    {
        $environment = new Environment([
            'heading_permalink' => [
                'html_class' => 'heading-anchor',
                'insert' => 'before',
                'id_prefix' => '',
                'fragment_prefix' => '',
                'symbol' => '',
            ],
        ]);
        $environment->addExtension(new CommonMarkCoreExtension());
        $environment->addExtension(new GithubFlavoredMarkdownExtension());
        $environment->addExtension(new HeadingPermalinkExtension());

        return new MarkdownConverter($environment);
    }

    /**
     * Bouw een inhoudsopgave op uit de h2/h3-koppen in de gerenderde HTML,
     * zodat de ankers exact overeenkomen met de door CommonMark gezette id's.
     *
     * @return array<int, array{level: int, id: string, title: string}>
     */
    private static function toc(string $html): array
    {
        preg_match_all('/<h([23])[^>]*>(.*?)<\/h\1>/is', $html, $m, PREG_SET_ORDER);

        $toc = [];
        foreach ($m as $h) {
            // Het anker-id zit op een inner <a id="..."> van de heading-permalink.
            if (! preg_match('/id="([^"]+)"/', $h[2], $idMatch)) {
                continue;
            }
            $toc[] = [
                'level' => (int) $h[1],
                'id' => $idMatch[1],
                'title' => trim(strip_tags($h[2])),
            ];
        }

        return $toc;
    }
}
