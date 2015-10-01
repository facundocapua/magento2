<?php
/**
 * Class Observer
 *
 * @author   Facundo Capua <fcapua@summasolutions.net>
 * @license  http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link     http://www.summasolutions.net/
 */

namespace Training1\FreeGeoIp\Model;

class Observer
{
    /**
     * CustomerSession
     *
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * GeoIp Service Model
     *
     * @var Service
     */
    protected $geoIpService;

    /**
     * @param \Magento\Customer\Model\Session $customerSession
     */
    public function __construct(
        \Magento\Customer\Model\Session $customerSession,
        \Training1\FreeGeoIp\Model\ServiceFactory $geoIpServiceFactory
    )
    {
        $this->customerSession = $customerSession;
        $this->geoIpService = $geoIpServiceFactory->create();
    }


    /**
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function setCountry(\Magento\Framework\Event\Observer $observer)
    {
        $currentValue = $this->customerSession->getData('visitor_country');
        if (null === $currentValue) {
            $country = $this->geoIpService->getCountryByIp();
            $this->customerSession->setData('visitor_country', $country);
        }
    }
}