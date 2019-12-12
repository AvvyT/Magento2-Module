<?php

namespace AvvyTest\FirstAvvysModule\Block\Product;

use Magento\Framework\View\Element\Template;
use Magento\Framework\App\Filesystem\DirectoryList;

/**
 * Grid of Category component
 */
class ProductsList extends Template implements \Magento\Widget\Block\BlockInterface
{
    /**
     * Default value for products count that will be shown
     */
    const DEFAULT_PRODUCTS_COUNT = 10;
    /**
     * Default value for products per page
     */
    const DEFAULT_PRODUCTS_PER_PAGE = 5;
    const DEFAULT_SORT_BY = 'id';
    const DEFAULT_SORT_ORDER = 'asc';
    const PAGE_SIZE = 5;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,        
        \Magento\Catalog\Helper\Category $category,
        \Magento\Catalog\Model\CategoryRepository $categoryRepository,
        \Magento\Widget\Helper\Conditions $conditionsHelper,
        \Magento\Framework\View\Asset\Repository $assetRepos,
        \Magento\Catalog\Helper\ImageFactory $helperImageFactory,
        \Magento\Framework\Image\AdapterFactory $imageFactory,
        \Magento\Framework\Filesystem $filesystem,
        array $data = []
    ) {
        $this->storeManager = $context->getStoreManager();
        $this->_productCollectionFactory = $productCollectionFactory;    
        $this->category = $category;
        $this->categoryRepository = $categoryRepository;
        $this->conditionsHelper = $conditionsHelper;
        $this->assetRepos = $assetRepos;
        $this->helperImageFactory = $helperImageFactory;
        $this->imageFactory = $imageFactory;
        $this->_filesystem = $filesystem;
        $this->_directory = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        parent::__construct($context, $data);
    }

    public function getCategoryProducts($categoryId) 
    {
        $products = $this->getCategoryData($categoryId)->getProductCollection();
        $products->addAttributeToSelect('*')->setPageSize(14);
        $products->getSelect()->order("IF('cat_index_position' = '0',true,false)");

        return $products;
    }

    /**
     * Get small place holder image
     *
     * @return string
     */
    public function getPlaceHolderImage()
    {
        /** @var \Magento\Catalog\Helper\Image $helper */
        $imagePlaceholder = $this->helperImageFactory->create();
        return $this->assetRepos->getUrl($imagePlaceholder->getPlaceholder('image'));
    }

    /**
     * @param array $categoryIds
     * @return \Magento\Framework\Data\Collection
     */
    public function getCategory()
    {
        $categoryIds = $this->getCategoryIds();
        if (!empty($categoryIds)) {
            $ids = array_slice(explode(',', $categoryIds), 0, $this->getCategoryToDisplay());
            $category = [];
            foreach ($ids as $id) {
                $category[] = $this->categoryRepository->get($id);
            }
            return $category;
        }
        return '';
    }

    /**
     * @param $imageName string imagename only
     * @param $width
     * @param $height
     */
    public function getResize($imageName, $width = 258, $height = 200)
    {
        $realPath = $this->_filesystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath('catalog/category/' . $imageName);
        if (!$this->_directory->isFile($realPath) || !$this->_directory->isExist($realPath)) {
            return false;
        }
        $targetDir = $this->_filesystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath('resized/' . $width . 'x' . $height);
        $pathTargetDir = $this->_directory->getRelativePath($targetDir);
        if (!$this->_directory->isExist($pathTargetDir)) {
            $this->_directory->create($pathTargetDir);
        }
        if (!$this->_directory->isExist($pathTargetDir)) {
            return false;
        }
        $image = $this->imageFactory->create();
        $image->open($realPath);
        $image->keepAspectRatio(true);
        $image->resize($width, $height);
        $dest = $targetDir . '/' . pathinfo($realPath, PATHINFO_BASENAME);
        $image->save($dest);
        if ($this->_directory->isFile($this->_directory->getRelativePath($dest))) {
            return $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'resized/' . $width . 'x' . $height . '/' . $imageName;
        }
        return false;
    }

    /**
     * Retrieve how many category should be displayed
     *
     * @return int
     */
    public function getCategoryToDisplay()
    {
        if (!$this->hasData('page_size')) {
            $this->setData('page_size', self::PAGE_SIZE);
        }
        return $this->getData('page_size');
    }

    /**
     * Retrieve category ids from widget
     *
     * @return string
     */
    public function getCategoryIds()
    {
        $conditions = $this->getData('conditions')
            ? $this->getData('conditions')
            : $this->getData('conditions_encoded');
        if ($conditions) {
            $conditions = $this->conditionsHelper->decode($conditions);
        }
        foreach ($conditions as $key => $condition) {
            if (!empty($condition['attribute']) && $condition['attribute'] == 'category_ids') {
                return $condition['value'];
            }
        }
        return '';
    }

    /**
     * Retrieve how many products should be displayed
     *
     * @return int
     */
    public function getProductsCount()
    {
        if ($this->hasData('products_count')) {
            return $this->getData('products_count');
        }

        if (null === $this->getData('products_count')) {
            $this->setData('products_count', self::DEFAULT_PRODUCTS_COUNT);
        }

        return $this->getData('products_count');
    }

    /**
     * Retrieve how many products should be displayed
     *
     * @return int
     */
    public function getProductsPerPage()
    {
        if (!$this->hasData('products_per_page')) {
            $this->setData('products_per_page', self::DEFAULT_PRODUCTS_PER_PAGE);
        }
        return $this->getData('products_per_page');
    }

    public function getSortBy()
    {
        if (!$this->hasData('products_sort_by')) {
            $this->setData('products_sort_by', self::DEFAULT_SORT_BY);
        }
        return $this->getData('products_sort_by');
    }

    public function getSortOrder()
    {
        if (!$this->hasData('products_sort_order')) {
            $this->setData('products_sort_order', self::DEFAULT_SORT_ORDER);
        }
        return $this->getData('products_sort_order');
    }
}
