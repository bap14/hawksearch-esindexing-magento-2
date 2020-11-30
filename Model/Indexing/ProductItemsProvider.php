<?php
/**
 * Copyright (c) 2020 Hawksearch (www.hawksearch.com) - All Rights Reserved
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS
 * IN THE SOFTWARE.
 */

declare(strict_types=1);

namespace HawkSearch\EsIndexing\Model\Indexing;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Event\ManagerInterface;

class ProductItemsProvider implements ItemsProviderInterface
{
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var Visibility
     */
    private $visibility;

    /**
     * @var FilterBuilder
     */
    private $filterBuilder;

    /**
     * @var ManagerInterface
     */
    private $eventManager;

    /**
     * ProductItemsProvider constructor.
     * @param ProductRepositoryInterface $productRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param Visibility $visibility
     * @param FilterBuilder $filterBuilder
     * @param ManagerInterface $eventManager
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        Visibility $visibility,
        FilterBuilder $filterBuilder,
        ManagerInterface $eventManager
    ) {
        $this->productRepository = $productRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->visibility = $visibility;
        $this->filterBuilder = $filterBuilder;
        $this->eventManager = $eventManager;
    }

    /**
     * @inheritDoc
     * @return ProductInterface[]
     */
    public function getItems($storeId, $entityIds = null, $currentPage = 1, $pageSize = 0)
    {
        $items = $this->getProductCollection($storeId, $entityIds, $currentPage, $pageSize);

        return $items;
    }

    /**
     * @param int $storeId
     * @param array|null $productIds
     * @param int $currentPage
     * @param int $pageSize
     * @return ProductInterface[]
     */
    protected function getProductCollection($storeId, $productIds = null, $currentPage = 1, $pageSize = 0): array
    {
        $this->searchCriteriaBuilder->addFilter('store_id', $storeId);

        if ($productIds && count($productIds) > 0) {
            $this->searchCriteriaBuilder->addFilter('entity_id', $productIds, 'in');
        }

        //@TODO Define $excludeNotVisibleProducts in system configuration
        $excludeNotVisibleProducts = false;

        if ($excludeNotVisibleProducts) {
            $this->searchCriteriaBuilder->addFilter(
                'visibility',
                $this->visibility->getVisibleInSiteIds(),
                'in'
            );
        }
        $searchCriteria = $this->searchCriteriaBuilder->create();
        $searchCriteria->setCurrentPage($currentPage)
            ->setPageSize($pageSize);

        $items =  $this->productRepository->getList($searchCriteria)->getItems();

        $this->eventManager->dispatch(
            'hawksearch_esindexing_after_products_collection_build',
            [
                'store' => $storeId,
                'products' => $items
            ]
        );

        return $items;
    }
}
