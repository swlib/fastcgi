<?php declare(strict_types=1);

namespace Swlib\FastCGI\Record;

use Swlib\FastCGI;
use Swlib\FastCGI\Record;

/**
 * The Web server sends a FCGI_ABORT_REQUEST record to abort a request
 *
 * @author Alexander.Lisachenko
 */
class AbortRequest extends Record
{

    public function __construct(int $requestId = 0)
    {
        $this->type = FastCGI::ABORT_REQUEST;
        $this->setRequestId($requestId);
    }

}
