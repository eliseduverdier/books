<?php

/**
 * Global methods for debug, and other dev needs
 */

function dump(...$var): void
{
    var_dump(...$var);
}

function dd(...$var): void
{
    var_dump(...$var);
    die;
}
