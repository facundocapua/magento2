<?php
/**
 * Class Vendor
 *
 * @author   Facundo Capua <fcapua@summasolutions.net>
 * @license  http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link     http://www.summasolutions.net/
 */

namespace Training5\VendorRepository\Model\Data;

class Vendor
    extends \Magento\Framework\Api\AbstractSimpleObject
    implements \Training5\VendorRepository\Api\Data\VendorInterface
{
    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->_get(self::ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setId($id)
    {
        $this->setData(self::ID, $id);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->_get(self::NAME);
    }

    /**
     * {@inheritdoc}
     */
    public function setName($name)
    {
        $this->setData(self::NAME, $name);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getProductIds()
    {
        return $this->_get(self::PRODUCTS);
    }

    /**
     * {@inheritdoc}
     */
    public function setProductIds(array $products = null)
    {
        $this->setData(self::PRODUCTS, $products);

        return $this;
    }
}