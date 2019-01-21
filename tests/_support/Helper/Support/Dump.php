<?php

namespace Helper\Support;


class Dump
{
    /**
     * @return bool
     */
    public function lastPostIdFromDump()
    {
        if (preg_match('/KEY `post_author` \(`post_author`\)
\) ENGINE=InnoDB AUTO_INCREMENT=(.*?) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;/',
            file_get_contents(__DIR__ . '/../../../_data/dump.sql'), $match)) {
            return $match[1];
        }
        return false;
    }
}