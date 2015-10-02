<?php
/**
 * Class GeoIp
 *
 * @author   Facundo Capua <fcapua@summasolutions.net>
 * @license  http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link     http://www.summasolutions.net/
 */

namespace Training3\ExchangeRate\Model;

class Service
{

    /**
     * Exchange Rate Service Url
     */
    const SERVICE_URL = 'http://api.fixer.io/latest';

    /**
     * Http Client
     *
     * @var \Magento\Framework\HTTP\Client\Curl
     */
    protected $httpClient;

    /**
     * @param \Magento\Framework\HTTP\Client\Curl $httpClient
     */
    public function __construct(
        \Magento\Framework\HTTP\Client\Curl $httpClient
    )
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Gets the exchange rate from the service
     *
     * @param $from
     * @param $to
     *
     * @return null|float
     */
    public function getRate($from, $to)
    {
        $url = self::SERVICE_URL;
        $url .= '?base='.$from;

        $this->httpClient->get($url);
        $result = $this->httpClient->getBody();

        $data = json_decode($result, true);
        $rate = null;
        if(null !== $data && !empty($data['rates'][$to])){
            $rate = $data['rates'][$to];
        }

        return $rate;
    }
}