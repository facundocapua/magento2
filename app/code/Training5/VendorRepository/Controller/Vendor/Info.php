<?php
/**
 * Class Create
 *
 * @author   Facundo Capua <fcapua@summasolutions.net>
 * @license  http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link     http://www.summasolutions.net/
 */

namespace Training5\VendorRepository\Controller\Vendor;

class Info
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

        $vendorId = $this->getRequest()->getParam('vendor_id');

        $vendor = $this->vendorRepository->load($vendorId);

        $this->getResponse()->appendBody(sprintf(
            "Vendor Information\nName: %s\nID: %d\n",
            $vendor->getName(),
            $vendor->getId()
            ));

        /** @var \Magento\Catalog\Api\Data\ProductInterface $associatedProduct */
        foreach($this->vendorRepository->getAssociatedProductIds($vendor->getId()) as $associatedProduct){
            $this->getResponse()->appendBody(sprintf(
                "Product: %s - %s (%d)\n",
                $associatedProduct->getName(),
                $associatedProduct->getSku(),
                $associatedProduct->getId()
            ));
        }
    }
}