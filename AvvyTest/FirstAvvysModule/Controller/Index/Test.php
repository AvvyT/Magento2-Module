<?php
// Our final Url will be STORE_URL/demo/index/test
namespace AvvyTest\FirstAvvysModule\Controller\Index;

class Test extends \Magento\Framework\App\Action\Action
{
    /**
     * Index action
     *
     * @return $this
     */
    public function execute()
    {
        $this->_view->loadLayout();
        $this->_view->getLayout()->getBlock('page.main.title')->setPageTitle('DemoWidget');
        $this->_view->renderLayout();
    }
}
