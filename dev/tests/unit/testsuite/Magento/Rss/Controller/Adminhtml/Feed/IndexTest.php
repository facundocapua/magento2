<?php
/**
 * @copyright Copyright (c) 2014 X.commerce, Inc. (http://www.magentocommerce.com)
 */
namespace Magento\Rss\Controller\Adminhtml\Feed;

use Magento\TestFramework\Helper\ObjectManager as ObjectManagerHelper;

/**
 * Class IndexTest
 * @package Magento\Rss\Controller\Feed
 */
class IndexTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Magento\Rss\Controller\Feed\Index
     */
    protected $controller;

    /**
     * @var \Magento\Framework\App\Action\Context|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $context;

    /**
     * @var \Magento\Rss\Model\RssManager|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $rssManager;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $scopeConfigInterface;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $rssFactory;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $response;

    protected function setUp()
    {
        $this->rssManager = $this->getMock('Magento\Rss\Model\RssManager', ['getProvider'], [], '', false);
        $this->scopeConfigInterface = $this->getMock('Magento\Framework\App\Config\ScopeConfigInterface');
        $this->rssFactory = $this->getMock('Magento\Rss\Model\RssFactory', ['create'], [], '', false);

        $request = $this->getMock('Magento\Framework\App\RequestInterface');
        $request->expects($this->once())->method('getParam')->with('type')->will($this->returnValue('rss_feed'));

        $this->response = $this->getMockBuilder('Magento\Framework\App\ResponseInterface')
            ->setMethods(['setHeader', 'setBody', 'sendResponse'])
            ->disableOriginalConstructor()->getMock();

        $objectManagerHelper = new ObjectManagerHelper($this);
        $controllerArguments = $objectManagerHelper->getConstructArguments(
            'Magento\Rss\Controller\Adminhtml\Feed\Index',
            [
                'rssManager' => $this->rssManager,
                'scopeConfig' => $this->scopeConfigInterface,
                'rssFactory' => $this->rssFactory,
                'request' => $request,
                'response' => $this->response
            ]
        );
        $objectManager = $controllerArguments['context']->getObjectManager();
        $urlInterface = $this->getMock('Magento\Backend\Model\UrlInterface');
        $objectManager->expects($this->at(0))->method('get')->with('Magento\Backend\Model\UrlInterface')
            ->will($this->returnValue($urlInterface));
        $this->controller = $objectManagerHelper->getObject(
            'Magento\Rss\Controller\Adminhtml\Feed\Index',
            $controllerArguments
        );
    }

    public function testExecute()
    {
        $this->scopeConfigInterface->expects($this->once())->method('getValue')->will($this->returnValue(true));
        $dataProvider = $this->getMock('Magento\Framework\App\Rss\DataProviderInterface');
        $dataProvider->expects($this->once())->method('isAllowed')->will($this->returnValue(true));

        $rssModel = $this->getMock('Magento\Rss\Model\Rss', ['setDataProvider', 'createRssXml'], [], '', false);
        $rssModel->expects($this->once())->method('setDataProvider')->will($this->returnSelf());
        $rssModel->expects($this->once())->method('createRssXml')->will($this->returnValue(''));

        $this->response->expects($this->once())->method('setHeader')->will($this->returnSelf());
        $this->response->expects($this->once())->method('setBody')->will($this->returnSelf());

        $this->rssFactory->expects($this->once())->method('create')->will($this->returnValue($rssModel));

        $this->rssManager->expects($this->once())->method('getProvider')->will($this->returnValue($dataProvider));
        $this->controller->execute();
    }

    public function testExecuteWithException()
    {
        $this->scopeConfigInterface->expects($this->once())->method('getValue')->will($this->returnValue(true));
        $dataProvider = $this->getMock('Magento\Framework\App\Rss\DataProviderInterface');
        $dataProvider->expects($this->once())->method('isAllowed')->will($this->returnValue(true));

        $rssModel = $this->getMock('Magento\Rss\Model\Rss', ['setDataProvider'], [], '', false);
        $rssModel->expects($this->once())->method('setDataProvider')->will($this->returnSelf());

        $this->response->expects($this->once())->method('setHeader')->will($this->returnSelf());
        $this->rssFactory->expects($this->once())->method('create')->will($this->returnValue($rssModel));
        $this->rssManager->expects($this->once())->method('getProvider')->will($this->returnValue($dataProvider));

        $this->setExpectedException('\Zend_Feed_Builder_Exception');
        $this->controller->execute();
    }
}
