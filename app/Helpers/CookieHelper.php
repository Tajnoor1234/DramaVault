<?php

namespace App\Helpers;

class CookieHelper
{
    /**
     * Create a cookie
     */
    public static function make($name, $value, $minutes = 60)
    {
        return cookie($name, $value, $minutes);
    }
    
    /**
     * Get a cookie value
     */
    public static function get($name, $default = null)
    {
        return request()->cookie($name, $default);
    }
    
    /**
     * Check if cookie exists
     */
    public static function has($name)
    {
        return request()->hasCookie($name);
    }
    
    /**
     * Delete a cookie
     */
    public static function forget($name)
    {
        return cookie()->forget($name);
    }
    
    /**
     * Create a cookie that lasts forever
     */
    public static function forever($name, $value)
    {
        return cookie()->forever($name, $value);
    }
    
    /**
     * Queue a cookie for the next response
     */
    public static function queue($name, $value, $minutes = 60)
    {
        return cookie()->queue($name, $value, $minutes);
    }
    
    /**
     * Check cookie consent status
     */
    public static function hasConsent()
    {
        return self::get('cookies_consent') === 'true';
    }
    
    /**
     * Get cookie preferences
     */
    public static function getPreferences()
    {
        return [
            'necessary' => true, // Always true
            'analytics' => self::get('cookies_analytics') === 'true',
            'marketing' => self::get('cookies_marketing') === 'true',
        ];
    }
    
    /**
     * Set cookie consent
     */
    public static function setConsent($necessary = true, $analytics = false, $marketing = false)
    {
        self::queue('cookies_consent', 'true', 525600); // 1 year
        self::queue('cookies_necessary', $necessary ? 'true' : 'false', 525600);
        self::queue('cookies_analytics', $analytics ? 'true' : 'false', 525600);
        self::queue('cookies_marketing', $marketing ? 'true' : 'false', 525600);
    }
}
