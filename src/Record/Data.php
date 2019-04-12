<?php declare(strict_types=1);

namespace Swlib\FastCGI\Record;

use Swlib\FastCGI;
use Swlib\FastCGI\Record;

/**
 * Data binary stream
 *
 * FCGI_DATA is a second stream record type used to send additional data to the application.
 *
 * @author Alexander.Lisachenko
 */
class Data extends Record
{

    public function __construct(string $contentData = '')
    {
        $this->type = FastCGI::DATA;
        $this->setContentData($contentData);
    }

}
