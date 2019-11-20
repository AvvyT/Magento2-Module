<?php
namespace AvvyTest\FirstAvvysModule\Block\Widget;
// Create widget Block class

use Magento\Framework\View\Element\Template;
use Magento\Widget\Block\BlockInterface; 

class ProductsList extends Template implements BlockInterface {

	protected $_template = "widget/grid.phtml";

}


 
