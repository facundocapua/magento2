<?php
/**
 * Class GeoIp
 *
 * @author   Facundo Capua <fcapua@summasolutions.net>
 * @license  http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link     http://www.summasolutions.net/
 */

namespace Training1\FreeGeoIp\Model;

class Service
{

    /**
     * GeoIp Service Url
     */
    const SERVICE_URL = 'http://freegeoip.net/json/';

    /**
     * Default Country Code
     */
    const DEFAULT_COUNTRY_CODE = 'XX';

    /**
     * Http Client
     *
     * @var \Magento\Framework\HTTP\Client\Curl
     */
    protected $httpClient;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    public function __construct(
        \Magento\Framework\HTTP\Client\Curl $httpClient,
        \Magento\Framework\App\RequestInterface $request
    )
    {
        $this->httpClient = $httpClient;
        $this->request = $request;
    }

    /**
     * Get the country code by ip, if no ip is provided
     * the remote address is used
     *
     * @param null|string $ip
     *
     * @return string
     */
    public function getCountryByIp($ip = null)
    {
        if(null == $ip){
            $ip = $this->request->getServer('REMOTE_ADDR');
        }

        $url = self::SERVICE_URL.$ip;
        $this->httpClient->get($url);
        $result = $this->httpClient->getBody();

        $data = json_decode($result);
        $countryCode = self::DEFAULT_COUNTRY_CODE;
        if(null !== $data && !empty($data->country_code)){
            $countryCode = $data->country_code;
        }

        return $countryCode;
    }
}