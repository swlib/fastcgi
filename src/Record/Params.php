<?php declare(strict_types=1);

namespace Swlib\FastCGI\Record;

use Swlib\FastCGI;
use Swlib\FastCGI\Record;

/**
 * Params request record
 *
 * @author Alexander.Lisachenko
 */
class Params extends Record
{

    /**
     * List of params
     *
     * @var array
     */
    protected $values = [];

    /**
     * Constructs a param request
     *
     * @param array $values
     */
    public function __construct(array $values = [])
    {
        $this->type = FastCGI::PARAMS;
        $this->values = $values;
        $this->setContentData($this->packPayload());
    }

    /**
     * Returns an associative list of parameters
     */
    public function getValues(): array
    {
        return $this->values;
    }

    /**
     * {@inheritdoc}
     * @param static $self
     */
    protected static function unpackPayload($self, string $data): void
    {
        $currentOffset = 0;
        do {
            [$nameLengthHigh] = array_values(unpack('CnameLengthHigh', $data));
            $isLongName = ($nameLengthHigh >> 7 == 1);
            $valueOffset = $isLongName ? 4 : 1;

            [$valueLengthHigh] = array_values(unpack('CvalueLengthHigh', substr($data, $valueOffset)));
            $isLongValue = ($valueLengthHigh >> 7 == 1);
            $dataOffset = $valueOffset + ($isLongValue ? 4 : 1);

            $formatParts = [
                $isLongName ? 'NnameLength' : 'CnameLength',
                $isLongValue ? 'NvalueLength' : 'CvalueLength',
            ];
            $format = join('/', $formatParts);
            [$nameLength, $valueLength] = array_values(unpack($format, $data));

            // Clear top bit for long record
            $nameLength &= ($isLongName ? 0x7fffffff : 0x7f);
            $valueLength &= ($isLongValue ? 0x7fffffff : 0x7f);

            [$nameData, $valueData] = array_values(
                unpack(
                    "a{$nameLength}nameData/a{$valueLength}valueData",
                    substr($data, $dataOffset)
                )
            );

            $self->values[$nameData] = $valueData;

            $keyValueLength = $dataOffset + $nameLength + $valueLength;
            $data = substr($data, $keyValueLength);
            $currentOffset += $keyValueLength;
        } while ($currentOffset < $self->getContentLength());
    }

    /** {@inheritdoc} */
    protected function packPayload(): string
    {
        $payload = '';
        foreach ($this->values as $nameData => $valueData) {
            $nameLength = strlen($nameData);
            $valueLength = strlen((string)$valueData);
            $isLongName = $nameLength > 127;
            $isLongValue = $valueLength > 127;
            $formatParts = [
                $isLongName ? 'N' : 'C',
                $isLongValue ? 'N' : 'C',
                "a{$nameLength}",
                "a{$valueLength}",
            ];
            $format = join('', $formatParts);

            $payload .= pack(
                $format,
                $isLongName ? ($nameLength | 0x80000000) : $nameLength,
                $isLongValue ? ($valueLength | 0x80000000) : $valueLength,
                $nameData,
                $valueData
            );
        }

        return $payload;
    }

}
