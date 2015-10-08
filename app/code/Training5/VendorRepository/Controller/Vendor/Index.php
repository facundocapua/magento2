<?php
/**
 * Class Index
 *
 * @author   Facundo Capua <fcapua@summasolutions.net>
 * @license  http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link     http://www.summasolutions.net/
 */

namespace Training5\VendorRepository\Controller\Vendor;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Training5\VendorRepository\Model\Data\Vendor;

class Index
    extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Training5\VendorRepository\Model\Resource\VendorRepository
     */
    private $vendorRepository;

    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var \Magento\Framework\Api\FilterBuilder
     */
    private $filterBuilder;

    /**
     * @var \Magento\Framework\Api\SortOrderBuilder
     */
    private $sortOrderBuilder;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Training5\VendorRepository\Model\Resource\VendorRepository $vendorRepository,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magento\Framework\Api\FilterBuilder $filterBuilder,
        \Magento\Framework\Api\SortOrderBuilder $sortOrderBuilder
    )
    {
        parent::__construct($context);
        $this->vendorRepository = $vendorRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->filterBuilder = $filterBuilder;
        $this->sortOrderBuilder = $sortOrderBuilder;
    }

    public function execute()
    {
        $this->getResponse()->setHeader('Content-Type', 'text/plain');

        $vendors = $this->getVendorsFromRepository();

        foreach($vendors as $vendor){
            $this->outputVendor($vendor);
        }
    }

    public function getVendorsFromRepository()
    {
        $nameFilter1 = $this->filterBuilder
                ->setField('name')
                ->setValue('%a%')
                ->setConditionType('like')
                ->create();

        $nameFilter2 = $this->filterBuilder
                ->setField('name')
                ->setValue('Brooklyn Curtis')
                ->setConditionType('eq')
                ->create();

        $idFilter = $this->filterBuilder
                ->setField('vendor_id')
                ->setValue(2)
                ->setConditionType('gt')
                ->create();

        $sortOrder = $this->sortOrderBuilder
                ->setField('name')
                ->setDirection(SearchCriteriaInterface::SORT_ASC)
                ->create();

        $this->searchCriteriaBuilder->addFilters([$nameFilter1, $nameFilter2]);
        $this->searchCriteriaBuilder->addFilters([$idFilter]);

        $this->searchCriteriaBuilder->addSortOrder($sortOrder);
        $this->searchCriteriaBuilder->setPageSize(6);
        $this->searchCriteriaBuilder->setCurrentPage(1);

        $criteria = $this->searchCriteriaBuilder->create();
        $products = $this->vendorRepository->getList($criteria);
        return $products;
    }

    public function outputVendor(Vendor $vendor)
    {
        $this->getResponse()->appendBody(sprintf(
            "%s (%d)\n",
            $vendor->getName(),
            $vendor->getId()
        ));
    }
}