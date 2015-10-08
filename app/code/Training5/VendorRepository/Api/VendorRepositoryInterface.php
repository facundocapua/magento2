<?php
/**
 * Interface VendorRepositoryInterface
 *
 * @author   Facundo Capua <fcapua@summasolutions.net>
 * @license  http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link     http://www.summasolutions.net/
 */

namespace Training5\VendorRepository\Api;


interface VendorRepositoryInterface
{

    /**
     * Loads a vendor
     *
     * @api
     * @param int $id
     *
     * @return \Training5\VendorRepository\Api\Data\VendorInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function load($id);

    /**
     * Creates/update vendor information
     *
     * @api
     * @param \Training5\VendorRepository\Api\Data\VendorInterface $vendor
     *
     * @return \Training5\VendorRepository\Api\Data\VendorInterface
     */
    public function save(\Training5\VendorRepository\Api\Data\VendorInterface $vendor);

    /**
     * Get list of vendors according to the search criteria
     *
     * @api
     * @param \Magento\Framework\Api\SearchCriteriaInterface|null $searchCriteria
     *
     * @return \Training5\VendorRepository\Api\Data\VendorInterface[]
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * Get the product list associated to the vendor
     *
     * @param int $vendorId
     *
     * @return \Magento\Catalog\Api\Data\ProductInterface[]
     */
    public function getAssociatedProductIds($vendorId);
}