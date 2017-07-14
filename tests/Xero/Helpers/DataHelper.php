<?php

namespace DarrynTen\Xero\Tests\Xero\Helpers;

trait DataHelper
{
    /**
     * @return \Mockery\MockInterface|\DarrynTen\Xero\Request\RequestHandler
     */
    public function getRequestMock()
    {
        return \Mockery::mock('DarrynTen\Xero\Request\RequestHandler');
    }
}
