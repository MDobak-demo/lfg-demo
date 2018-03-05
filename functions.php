<?php

/**
 * I have no idea what this function should do ;)
 *
 * @param string $url
 *
 * @return string
 */
function url(string $url)
{
    return $url;
}

/**
 * @param string $link
 *
 * @todo PSR-7 compliant response object should be used instead of this
 */
function redirect(string $link = "")
{
    if ($link == "") {
        header("Location: ".$_SERVER['REQUEST_URI']);
    } else {
        header("Location: ".url($link));
    }

    exit(); // FIXME: Bad idea to terminate immediately code execution, this can lead to hard to debug errors.
}
