<?php

/**
 * Filter interface which all Filters must implement
 */
interface Filter_Interface
{
    public function filter($value);
}

/**
 * Strip tags filter
 * 
 * Removes whitespace from the given value
 */
class Trim_Filter implements Filter_Interface
{
    public function filter($value)
    {
        return trim($value);
    }
}

/**
 * Strip tags filter
 * 
 * Strips tags from the given value
 */
class StripTags_Filter implements Filter_Interface
{
    public function filter($value)
    {
        return strip_tags($value);
    }
}