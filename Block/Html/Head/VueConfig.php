<?php
/**
 * Copyright (c) 2021 Hawksearch (www.hawksearch.com) - All Rights Reserved
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

namespace HawkSearch\EsIndexing\Block\Html\Head;

use HawkSearch\EsIndexing\Model\Layout\LayoutConfigProcessorInterface;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\View\Element\Template;

class VueConfig extends Template
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var LayoutConfigProcessorInterface
     */
    private $configProcessor;

    /**
     * Config constructor.
     * @param Template\Context $context
     * @param SerializerInterface $serializer
     * @param LayoutConfigProcessorInterface $configProcessor
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        SerializerInterface $serializer,
        LayoutConfigProcessorInterface $configProcessor,
        array $data = []

    ) {
        $this->configProcessor = $configProcessor;
        $this->serializer = $serializer;
        parent::__construct($context, $data);
    }

    /**
     * @inheritdoc
     */
    public function getJsLayout()
    {
        return $this->serializer->serialize($this->configProcessor->process($this->jsLayout));
    }
}
