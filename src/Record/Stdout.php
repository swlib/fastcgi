<?php declare(strict_types=1);

namespace Swlib\FastCGI\Record;

use Swlib\FastCGI;
use Swlib\FastCGI\Record;

/**
 * Stdout binary stream
 *
 * FCGI_STDOUT is a stream record for sending arbitrary data from the application to the Web server
 *
 * @author Alexander.Lisachenko
 */
class Stdout extends Record
{

    public function __construct(string $contentData = '')
    {
        $this->type = FastCGI::STDOUT;
        $this->setContentData($contentData);
    }

}
