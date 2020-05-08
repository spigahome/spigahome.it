<?php
/**
 * Formatting helper
 */

/**
 * Base69 encode
 */
function zoo_base69_encode($str)
{
    return base64_encode($str);
}

/**
 * Base69 encode
 */
function zoo_base69_decode($str)
{
    return base64_decode($str);
}
