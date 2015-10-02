<?php
/**
 * Class HelloWorld
 *
 * @author   Facundo Capua <fcapua@summasolutions.net>
 * @license  http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link     http://www.summasolutions.net/
 */

namespace Training3\BundleBlock\Block;

class HelloWorld
    extends \Magento\Framework\View\Element\AbstractBlock
{
    /**
     * Returns a simple hello world
     *
     * @return string
     */
    protected function _toHtml()
    {
        return '<h1>Hello World</h1>';
    }
}