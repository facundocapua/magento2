<?php
/**
 * Interface VendorInterface
 *
 * @author   Facundo Capua <fcapua@summasolutions.net>
 * @license  http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link     http://www.summasolutions.net/
 */

namespace Training5\VendorRepository\Api\Data;

interface VendorInterface
{
    const ID = 'vendor_id';
    const NAME = 'name';
    const PRODUCTS = 'product_ids';

    /**
     * @return int
     */
    public function getId();

    /**
     * @param $id
     *
     * @return $this
     */
    public function setId($id);

    /**
     * @return string
     */
    public function getName();

    /**
     * @param $name
     *
     * @return $this
     */
    public function setName($name);

    /**
     * @return int[]
     */
    public function getProductIds();

    /**
     * @param int[]|null $products
     *
     * @return $this
     */
    public function setProductIds(array $products = null);
}