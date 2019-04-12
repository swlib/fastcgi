<?php declare(strict_types=1);

namespace Swlib\FastCGI\Record;

use Swlib\FastCGI;
use Swlib\FastCGI\Record;

/**
 * Stdin binary stream
 *
 * FCGI_STDIN is a stream record type used in sending arbitrary data from the Web server to the application
 *
 * @author Alexander.Lisachenko
 */
class Stdin extends Record
{

    public function __construct(string $contentData = '')
    {
        $this->type = FastCGI::STDIN;
        $this->setContentData($contentData);
    }

}
