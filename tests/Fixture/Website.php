<?php

use Staticka\Filter\HtmlMinifier;

$website = new Staticka\Siemes\Website;

$website->set('source', __DIR__);

$website->filter(new HtmlMinifier);

return $website;
