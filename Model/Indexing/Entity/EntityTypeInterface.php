<?php
/**
 * Copyright (c) 2022 Hawksearch (www.hawksearch.com) - All Rights Reserved
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

namespace HawkSearch\EsIndexing\Model\Indexing\Entity;

use HawkSearch\EsIndexing\Model\Indexing\EntityIndexerInterface;
use HawkSearch\EsIndexing\Model\Indexing\ItemsProviderInterface;

interface EntityTypeInterface
{
    /**
     * @return string
     */
    public function getTypeName();

    /**
     * @param string $type
     * @return $this
     */
    public function setTypeName($type);

    /**
     * @return EntityIndexerInterface
     */
    public function getEntityIndexer();

    /**
     * @return ItemsProviderInterface
     */
    public function getItemsProvider();

    /**
     * @return AttributeHandlerInterface
     */
    public function getAttributeHandler();
}
