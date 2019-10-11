<?php
//The Block file should contain all the view logic required
namespace AvvyTest\FirstAvvysModule\Block;

class Test extends \Magento\Framework\View\Element\Template
{
    public function __construct(\Magento\Framework\View\Element\Template\Context $context)
    {
        parent::__construct($context);
    }

    public function sayHello()
    {
        return __("<p style='color: white'>Hello Avvy</p>");
    }
}