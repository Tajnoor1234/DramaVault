<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Session;

class SessionHelper
{
    /**
     * Get session data with default value
     */
    public static function get($key, $default = null)
    {
        return Session::get($key, $default);
    }
    
    /**
     * Store data in session
     */
    public static function put($key, $value)
    {
        Session::put($key, $value);
    }
    
    /**
     * Check if key exists in session
     */
    public static function has($key)
    {
        return Session::has($key);
    }
    
    /**
     * Remove item from session
     */
    public static function forget($key)
    {
        Session::forget($key);
    }
    
    /**
     * Clear all session data
     */
    public static function flush()
    {
        Session::flush();
    }
    
    /**
     * Regenerate session ID
     */
    public static function regenerate()
    {
        Session::regenerate();
    }
    
    /**
     * Get all session data
     */
    public static function all()
    {
        return Session::all();
    }
    
    /**
     * Flash data for next request
     */
    public static function flash($key, $value)
    {
        Session::flash($key, $value);
    }
    
    /**
     * Get page views count
     */
    public static function getPageViews()
    {
        return Session::get('page_views', 0);
    }
    
    /**
     * Get last activity timestamp
     */
    public static function getLastActivity()
    {
        return Session::get('last_activity');
    }
    
    /**
     * Get last visited page
     */
    public static function getLastPage()
    {
        return Session::get('last_page');
    }
    
    /**
     * Check if user session is active
     */
    public static function isActive()
    {
        $lastActivity = self::getLastActivity();
        if (!$lastActivity) {
            return false;
        }
        
        $timeout = config('session.lifetime') * 60; // Convert to seconds
        return (time() - $lastActivity) < $timeout;
    }
    
    /**
     * Get session info for debugging
     */
    public static function getInfo()
    {
        return [
            'id' => Session::getId(),
            'page_views' => self::getPageViews(),
            'last_activity' => self::getLastActivity(),
            'last_page' => self::getLastPage(),
            'is_active' => self::isActive(),
            'data' => Session::all()
        ];
    }
}
