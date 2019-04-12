<?php declare(strict_types=1);

namespace Swlib\FastCGI\Record;

use Swlib\FastCGI;
use Swlib\FastCGI\Record;

/**
 * Stderr binary stream
 *
 * FCGI_STDERR is a stream record for sending arbitrary data from the application to the Web server
 *
 * @author Alexander.Lisachenko
 */
class Stderr extends Record
{

    public function __construct(string $contentData = '')
    {
        $this->type = FastCGI::STDERR;
        $this->setContentData($contentData);
    }

}
