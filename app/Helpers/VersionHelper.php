<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;

class VersionHelper
{
    /**
     * Get current application version
     */
    public static function getVersion()
    {
        return config('version.version', '1.0.0');
    }
    
    /**
     * Get version with 'v' prefix
     */
    public static function getVersionWithPrefix()
    {
        return 'v' . self::getVersion();
    }
    
    /**
     * Get release date
     */
    public static function getReleaseDate()
    {
        return config('version.release_date', date('Y-m-d'));
    }
    
    /**
     * Get version codename
     */
    public static function getCodename()
    {
        return config('version.codename', '');
    }
    
    /**
     * Get version description
     */
    public static function getDescription()
    {
        return config('version.description', '');
    }
    
    /**
     * Get formatted version info
     */
    public static function getFormattedVersion()
    {
        $version = self::getVersion();
        $codename = self::getCodename();
        $date = self::getReleaseDate();
        
        $formatted = "v{$version}";
        
        if ($codename) {
            $formatted .= " ({$codename})";
        }
        
        $formatted .= " - {$date}";
        
        return $formatted;
    }
    
    /**
     * Check if version is development
     */
    public static function isDevelopment()
    {
        $version = self::getVersion();
        return str_contains($version, 'dev') || str_contains($version, 'alpha') || str_contains($version, 'beta');
    }
    
    /**
     * Check if version is stable
     */
    public static function isStable()
    {
        return !self::isDevelopment();
    }
    
    /**
     * Get version features
     */
    public static function getFeatures()
    {
        return config('version.features', []);
    }
    
    /**
     * Get version improvements
     */
    public static function getImprovements()
    {
        return config('version.improvements', []);
    }
    
    /**
     * Get version bug fixes
     */
    public static function getBugFixes()
    {
        return config('version.bug_fixes', []);
    }
    
    /**
     * Get breaking changes
     */
    public static function getBreakingChanges()
    {
        return config('version.breaking_changes', []);
    }
    
    /**
     * Get upgrade notes
     */
    public static function getUpgradeNotes()
    {
        return config('version.upgrade_notes', []);
    }
    
    /**
     * Get changelog content
     */
    public static function getChangelogContent()
    {
        $cacheKey = 'changelog_content';
        
        return Cache::remember($cacheKey, 3600, function () {
            $changelogPath = base_path('CHANGELOG.md');
            
            if (!file_exists($changelogPath)) {
                return 'Changelog dosyası bulunamadı.';
            }
            
            return file_get_contents($changelogPath);
        });
    }
    
    /**
     * Get recent changelog entries
     */
    public static function getRecentChangelogEntries($limit = 5)
    {
        $content = self::getChangelogContent();
        
        // Parse markdown and extract version entries
        preg_match_all('/## \[([^\]]+)\] - ([^\n]+)\n\n(.*?)(?=\n## |$)/s', $content, $matches, PREG_SET_ORDER);
        
        $entries = [];
        foreach (array_slice($matches, 0, $limit) as $match) {
            $entries[] = [
                'version' => $match[1],
                'date' => $match[2],
                'content' => trim($match[3])
            ];
        }
        
        return $entries;
    }
    
    /**
     * Get version comparison info
     */
    public static function compareVersions($version1, $version2)
    {
        $v1 = self::parseVersion($version1);
        $v2 = self::parseVersion($version2);
        
        if ($v1['major'] > $v2['major']) return 1;
        if ($v1['major'] < $v2['major']) return -1;
        
        if ($v1['minor'] > $v2['minor']) return 1;
        if ($v1['minor'] < $v2['minor']) return -1;
        
        if ($v1['patch'] > $v2['patch']) return 1;
        if ($v1['patch'] < $v2['patch']) return -1;
        
        return 0;
    }
    
    /**
     * Parse version string
     */
    protected static function parseVersion($version)
    {
        $parts = explode('.', $version);
        
        return [
            'major' => (int) ($parts[0] ?? 0),
            'minor' => (int) ($parts[1] ?? 0),
            'patch' => (int) ($parts[2] ?? 0)
        ];
    }
    
    /**
     * Get version badge HTML
     */
    public static function getVersionBadge($class = '')
    {
        $version = self::getVersion();
        $codename = self::getCodename();
        
        $badgeClass = $class ?: 'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200';
        
        $html = "<span class=\"{$badgeClass}\">v{$version}";
        
        if ($codename) {
            $html .= " ({$codename})";
        }
        
        $html .= "</span>";
        
        return $html;
    }
}
