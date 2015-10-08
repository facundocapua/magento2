<?php
/**
 * Class VendorRepository
 *
 * @author   Facundo Capua <fcapua@summasolutions.net>
 * @license  http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link     http://www.summasolutions.net/
 */

namespace Training5\VendorRepository\Model\Resource;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Api\SearchCriteriaInterface;


class VendorRepository
    implements \Training5\VendorRepository\Api\VendorRepositoryInterface
{

    /**
     * @var \Training4\Vendor\Model\VendorFactory
     */
    private $vendorFactory;

    /**
     * @var \Training5\VendorRepository\Model\Data\VendorFactory
     */
    private $vendorDataFactory;

    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var \Magento\Framework\Api\FilterBuilder
     */
    private $filterBuilder;

    /**
     * @param \Training4\Vendor\Model\VendorFactory                $vendorFactory
     * @param \Training5\VendorRepository\Model\Data\VendorFactory $vendorDataFactory
     * @param \Magento\Catalog\Api\ProductRepositoryInterface      $productRepository
     * @param \Magento\Framework\Api\SearchCriteriaBuilder         $searchCriteriaBuilder
     * @param \Magento\Framework\Api\FilterBuilder                 $filterBuilder
     */
    public function __construct(
        \Training4\Vendor\Model\VendorFactory $vendorFactory,
        \Training5\VendorRepository\Model\Data\VendorFactory $vendorDataFactory,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magento\Framework\Api\FilterBuilder $filterBuilder
    )
    {
        $this->vendorFactory = $vendorFactory;
        $this->vendorDataFactory = $vendorDataFactory;
        $this->productRepository = $productRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->filterBuilder = $filterBuilder;
    }

    /**
     * {@inheritdoc}
     */
    public function load($vendorId)
    {
        $vendor = $this->vendorFactory->create()->load($vendorId);
        if (!$vendor->getId()) {
            throw NoSuchEntityException::singleField('vendorId', $vendorId);
        } else {
            $vendorData = $this->vendorDataFactory->create(['data' => $vendor->toArray()]);
            $vendorData->setProductIds($vendor->getProductIds());

            return $vendorData;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function save(\Training5\VendorRepository\Api\Data\VendorInterface $vendor)
    {
        $vendorModel = $this->vendorFactory->create(['data' => $vendor->__toArray()]);

        $vendorModel->setHasDataChanges(true);
        $vendorModel->save();

        if (!$vendor->getId()) {
            $vendor->setId($vendorModel->getId());
        }

        return $vendor;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria = null)
    {
        /** @var \Training4\Vendor\Model\Resource\Vendor\Collection $collection */
        $collection = $this->vendorFactory->create()->getCollection();

        if($searchCriteria != null) {
            foreach ($searchCriteria->getFilterGroups() as $filterGroup) {
                $this->addFilterGroupToCollection($filterGroup, $collection);
            }

            $sortOrders = $searchCriteria->getSortOrders();
            if ($sortOrders) {
                foreach ($sortOrders as $sortOrder) {
                    $collection->addOrder(
                        $sortOrder->getField(),
                        ($sortOrder->getDirection() == SearchCriteriaInterface::SORT_ASC) ? 'ASC' : 'DESC'
                    );
                }
            }
            $collection->setCurPage($searchCriteria->getCurrentPage());
            $collection->setPageSize($searchCriteria->getPageSize());
        }

        $vendors = [];

        /** @var \Training4\Vendor\Model\Vendor $vendorModel */
        foreach ($collection as $vendorModel) {
            $vendors[] = $this->vendorDataFactory->create(['data' => $vendorModel->getData()]);
        }

        return $vendors;
    }

    /**
     * {@inheritdoc}
     */
    public function getAssociatedProductIds($vendorId)
    {
        $vendor = $this->load($vendorId);

        $vendorProductsFilter = $this->filterBuilder->create()
                ->setField('entity_id')
                ->setConditionType('in')
                ->setValue($vendor->getProductIds());

        $this->searchCriteriaBuilder->addFilters([$vendorProductsFilter]);

        $criteria = $this->searchCriteriaBuilder->create();
        $products = $this->productRepository->getList($criteria);

        return $products->getItems();
    }

    /**
     * Add filter group to a vendors collection
     *
     * @param \Magento\Framework\Api\Search\FilterGroup          $filterGroup
     * @param \Training4\Vendor\Model\Resource\Vendor\Collection $collection
     */
    public function addFilterGroupToCollection(
        \Magento\Framework\Api\Search\FilterGroup $filterGroup,
        \Training4\Vendor\Model\Resource\Vendor\Collection $collection
    )
    {
        $fields = [];
        $conditions = [];
        foreach ($filterGroup->getFilters() as $filter) {
            $condition = $filter->getConditionType() ? $filter->getConditionType() : 'eq';
            $fields[] = ['field' => $filter->getField()];
            $conditions[] = [$condition => $filter->getValue()];
        }

        if ($fields) {
            $collection->addFieldToFilter($fields, $conditions);
        }
    }
}