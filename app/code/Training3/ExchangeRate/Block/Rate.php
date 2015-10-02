<?php
/**
 * Class Rate
 *
 * @author   Facundo Capua <fcapua@summasolutions.net>
 * @license  http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link     http://www.summasolutions.net/
 */

namespace Training3\ExchangeRate\Block;

class Rate
    extends \Magento\Framework\View\Element\AbstractBlock
{

    /**
     * ExchangeService
     *
     * @var \Training3\ExchangeRate\Model\Service
     */
    protected $exchangeService;

    /**
     * CurrencyInterface
     *
     * @var \Magento\Framework\Locale\CurrencyInterface
     */
    protected $currency;

    /**
     * @param \Magento\Framework\View\Element\Context     $context
     * @param \Training3\ExchangeRate\Model\Service       $exchangeService
     * @param \Magento\Framework\Locale\CurrencyInterface $currency
     * @param array                                       $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Context $context,
        \Training3\ExchangeRate\Model\Service $exchangeService,
        \Magento\Framework\Locale\CurrencyInterface $currency,
        array $data = [])
    {
        $this->exchangeService = $exchangeService;
        $this->currency = $currency;

        parent::__construct($context, $data);
    }

    /**
     * Returns the exchange rate of the given currencies (from and to)
     *
     * @return \Magento\Framework\Phrase
     */
    protected function _toHtml()
    {
        $convertFrom = $this->currency->getCurrency($this->getData('convertFrom'));
        $convertTo = $this->currency->getCurrency($this->getData('convertTo'));

        $exchangeRate = $this->exchangeService->getRate($convertFrom->getShortName(), $convertTo->getShortName());


        return __('1 %1 is equal to %2 %3', $convertFrom->getName(), $exchangeRate, $convertTo->getName() );
    }
}