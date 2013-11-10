<?php

namespace G\RestFull\Resource;

use Symfony\Component\HttpFoundation\Request;

interface AcceptRequestIface
{
    public function setRequest(Request $request);
}