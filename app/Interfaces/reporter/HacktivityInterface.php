<?php

namespace App\Interfaces\reporter;

interface HacktivityInterface
{
    public function hacktivity($request);

    public function loadMoreData($request);

}
