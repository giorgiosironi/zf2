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
 * @package    Zend_PDF
 * @subpackage Zend_PDF_Action
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */

/**
 * @namespace
 */
namespace Zend\PDF\Action;
use Zend\PDF;
use Zend\PDF\InternalType;

/**
 * PDF 'Resolve a uniform resource identifier' action
 *
 * A URI action causes a URI to be resolved.
 *
 * @uses       SplObjectStorage
 * @uses       \Zend\PDF\Action\AbstractAction
 * @uses       \Zend\PDF\InternalType\BooleanObject
 * @uses       \Zend\PDF\InternalType\DictionaryObject
 * @uses       \Zend\PDF\InternalType\NameObject
 * @uses       \Zend\PDF\InternalType\StringObject
 * @uses       \Zend\PDF\Exception
 * @package    Zend_PDF
 * @subpackage Zend_PDF_Action
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class URI extends AbstractAction
{
    /**
     * Object constructor
     *
     * @param \Zend\PDF\InternalType\DictionaryObject $dictionary
     * @param SplObjectStorage      $processedActions  list of already processed action dictionaries,
     *                                                 used to avoid cyclic references
     * @throws \Zend\PDF\Exception
     */
    public function __construct(InternalType\AbstractTypeObject $dictionary, \SplObjectStorage $processedActions)
    {
        parent::__construct($dictionary, $processedActions);

        if ($dictionary->URI === null) {
            throw new PDF\Exception('URI action dictionary entry is required');
        }
    }

    /**
     * Validate URI
     *
     * @param string $uri
     * @return true
     * @throws \Zend\PDF\Exception
     */
    protected static function _validateUri($uri)
    {
        $scheme = parse_url((string)$uri, PHP_URL_SCHEME);
        if ($scheme === false || $scheme === null) {
            throw new PDF\Exception('Invalid URI');
        }
    }

    /**
     * Create new Zend_PDF_Action_URI object using specified uri
     *
     * @param string  $uri    The URI to resolve, encoded in 7-bit ASCII
     * @param boolean $isMap  A flag specifying whether to track the mouse position when the URI is resolved
     * @return \Zend\PDF\Action\URI
     */
    public static function create($uri, $isMap = false)
    {
        self::_validateUri($uri);

        $dictionary       = new InternalType\DictionaryObject();
        $dictionary->Type = new InternalType\NameObject('Action');
        $dictionary->S    = new InternalType\NameObject('URI');
        $dictionary->Next = null;
        $dictionary->URI  = new InternalType\StringObject($uri);
        if ($isMap) {
            $dictionary->IsMap = new InternalType\BooleanObject(true);
        }

        return new self($dictionary, new \SplObjectStorage());
    }

    /**
     * Set URI to resolve
     *
     * @param string $uri   The uri to resolve, encoded in 7-bit ASCII.
     * @return \Zend\PDF\Action\URI
     */
    public function setUri($uri)
    {
        $this->_validateUri($uri);

        $this->_actionDictionary->touch();
        $this->_actionDictionary->URI = new InternalType\StringObject($uri);

        return $this;
    }

    /**
     * Get URI to resolve
     *
     * @return string
     */
    public function getUri()
    {
        return $this->_actionDictionary->URI->value;
    }

    /**
     * Set IsMap property
     *
     * If the IsMap flag is true and the user has triggered the URI action by clicking
     * an annotation, the coordinates of the mouse position at the time the action is
     * performed should be transformed from device space to user space and then offset
     * relative to the upper-left corner of the annotation rectangle.
     *
     * @param boolean $isMap  A flag specifying whether to track the mouse position when the URI is resolved
     * @return \Zend\PDF\Action\URI
     */
    public function setIsMap($isMap)
    {
        $this->_actionDictionary->touch();

        if ($isMap) {
            $this->_actionDictionary->IsMap = new InternalType\BooleanObject(true);
        } else {
            $this->_actionDictionary->IsMap = null;
        }

        return $this;
    }

    /**
     * Get IsMap property
     *
     * If the IsMap flag is true and the user has triggered the URI action by clicking
     * an annotation, the coordinates of the mouse position at the time the action is
     * performed should be transformed from device space to user space and then offset
     * relative to the upper-left corner of the annotation rectangle.
     *
     * @return boolean
     */
    public function getIsMap()
    {
        return $this->_actionDictionary->IsMap !== null  &&
               $this->_actionDictionary->IsMap->value;
    }
}
