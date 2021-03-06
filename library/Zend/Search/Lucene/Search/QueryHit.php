<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Search_Lucene
 * @subpackage Search
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */

/**
 * @namespace
 */
namespace Zend\Search\Lucene\Search;
use Zend\Search\Lucene;
use Zend\Search\Lucene\Document;

/**
 * @uses       \Zend\Search\Lucene\IndexInterface
 * @uses       \Zend\Search\Lucene\Document\Document
 * @category   Zend
 * @package    Zend_Search_Lucene
 * @subpackage Search
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class QueryHit
{
    /**
     * Object handle of the index
     * @var \Zend\Search\Lucene\IndexInterface
     */
    protected $_index = null;

    /**
     * Object handle of the document associated with this hit
     * @var \Zend\Search\Lucene\Document\Document
     */
    protected $_document = null;

    /**
     * Number of the document in the index
     * @var integer
     */
    public $id;

    /**
     * Score of the hit
     * @var float
     */
    public $score;


    /**
     * Constructor - pass object handle of Zend_Search_Lucene_Interface index that produced
     * the hit so the document can be retrieved easily from the hit.
     *
     * @param \Zend\Search\Lucene\IndexInterface $index
     */

    public function __construct(Lucene\IndexInterface $index)
    {
        $this->_index = $index;
    }


    /**
     * Convenience function for getting fields from the document
     * associated with this hit.
     *
     * @param string $offset
     * @return string
     */
    public function __get($offset)
    {
        return $this->getDocument()->getFieldValue($offset);
    }


    /**
     * Return the document object for this hit
     *
     * @return \Zend\Search\Lucene\Document\Document
     */
    public function getDocument()
    {
        if (!$this->_document instanceof Document\Document) {
            $this->_document = $this->_index->getDocument($this->id);
        }

        return $this->_document;
    }


    /**
     * Return the index object for this hit
     *
     * @return \Zend\Search\Lucene\IndexInterface
     */
    public function getIndex()
    {
        return $this->_index;
    }
}
