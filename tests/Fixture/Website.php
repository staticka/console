<?php

use Staticka\Filter\HtmlMinifier;

$website = new Staticka\Siemes\Website;

$website->filter(new HtmlMinifier);

return $website;
