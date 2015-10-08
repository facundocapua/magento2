<?php
/**
 * Class Create
 *
 * @author   Facundo Capua <fcapua@summasolutions.net>
 * @license  http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link     http://www.summasolutions.net/
 */

namespace Training5\VendorRepository\Controller\Vendor;

class Update
    extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Training5\VendorRepository\Api\VendorRepositoryInterface
     */
    private $vendorRepository;

    /**
     * @var \Training5\VendorRepository\Model\Data\VendorFactory
     */
    private $vendorFactory;

    /**
     * @param \Magento\Framework\App\Action\Context                     $context
     * @param \Training5\VendorRepository\Api\VendorRepositoryInterface $vendorRepository
     * @param \Training5\VendorRepository\Model\Data\VendorFactory      $vendorFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Training5\VendorRepository\Api\VendorRepositoryInterface $vendorRepository,
        \Training5\VendorRepository\Model\Data\VendorFactory $vendorFactory
    )
    {
        parent::__construct($context);

        $this->vendorRepository = $vendorRepository;
        $this->vendorFactory = $vendorFactory;
    }

    public function execute()
    {
        $this->getResponse()->setHeader('Content-Type', 'text/plain');

        $mockedData = [
            'vendor_id' => 1,
            'name' => 'Updated Vendor'
        ];
        $vendor = $this->vendorFactory->create(['data' => $mockedData]);

        $this->vendorRepository->save($vendor);

        $this->getResponse()->appendBody(sprintf(
            "Updated Vendor %s %d",
            $vendor->getName(),
            $vendor->getId()
            ));
    }
}