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
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */

/**
 * @namespace
 */
namespace ZendTest\PDF\InternalType;
use Zend\PDF\InternalType;
use Zend\PDF;

/**
 * \Zend\PDF\InternalType\DictionaryObject
 */

/**
 * PHPUnit Test Case
 */

/**
 * @category   Zend
 * @package    Zend_PDF
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @group      Zend_PDF
 */
class DictionaryTest extends \PHPUnit_Framework_TestCase
{
    public function testPDFDictionary1()
    {
        $dictionaryObj = new InternalType\DictionaryObject();
        $this->assertTrue($dictionaryObj instanceof InternalType\DictionaryObject);
    }

    public function testPDFDictionary2()
    {
        $srcArray = array();
        $srcArray['Bool'] = new InternalType\BooleanObject(false);
        $srcArray['Number'] = new InternalType\NumericObject(100.426);
        $srcArray['Name'] = new InternalType\NameObject('MyName');
        $srcArray['Text'] = new InternalType\StringObject('some text');
        $srcArray['BinaryText'] = new InternalType\BinaryStringObject('some text');

        $dictionaryObj = new InternalType\DictionaryObject($srcArray);
        $this->assertTrue($dictionaryObj instanceof InternalType\DictionaryObject);
    }

    public function testPDFDictionaryBadInput1()
    {
        try {
            $arrayObj = new InternalType\DictionaryObject(346);
        } catch (PDF\Exception $e) {
            $this->assertContains('must be an array', $e->getMessage());
            return;
        }
        $this->fail('Expected \Zend\PDF\Exception to be thrown');
    }

    public function testPDFDictionaryBadInput2()
    {
        try {
            $srcArray = array();
            $srcArray['Bool'] = new InternalType\BooleanObject(false);
            $srcArray['Number'] = new InternalType\NumericObject(100.426);
            $srcArray['Name'] = new InternalType\NameObject('MyName');
            $srcArray['Text'] = new InternalType\StringObject('some text');
            $srcArray['BinaryText'] = new InternalType\BinaryStringObject('some text');
            $srcArray['bad value'] = 24;
            $dictionaryObj = new InternalType\DictionaryObject($srcArray);
        } catch (PDF\Exception $e) {
            $this->assertContains('must be \Zend\PDF\InternalType', $e->getMessage());
            return;
        }
        $this->fail('Expected \Zend\PDF\Exception to be thrown');
    }

    public function testPDFDictionaryBadInput3()
    {
        try {
            $srcArray = array();
            $srcArray['Bool'] = new InternalType\BooleanObject(false);
            $srcArray['Number'] = new InternalType\NumericObject(100.426);
            $srcArray['Name'] = new InternalType\NameObject('MyName');
            $srcArray['Text'] = new InternalType\StringObject('some text');
            $srcArray['BinaryText'] = new InternalType\BinaryStringObject('some text');
            $srcArray[5] = new InternalType\StringObject('bad name');
            $dictionaryObj = new InternalType\DictionaryObject($srcArray);
        } catch (PDF\Exception $e) {
            $this->assertContains('keys must be strings', $e->getMessage());
            return;
        }
        $this->fail('Expected \Zend\PDF\Exception to be thrown');
    }

    public function testGetType()
    {
        $dictionaryObj = new InternalType\DictionaryObject();
        $this->assertEquals($dictionaryObj->getType(), InternalType\AbstractTypeObject::TYPE_DICTIONARY);
    }

    public function testToString()
    {
        $srcArray = array();
        $srcArray['Bool'] = new InternalType\BooleanObject(false);
        $srcArray['Number'] = new InternalType\NumericObject(100.426);
        $srcArray['Name'] = new InternalType\NameObject('MyName');
        $srcArray['Text'] = new InternalType\StringObject('some text');
        $srcArray['BinaryText'] = new InternalType\BinaryStringObject("\x01\x02\x00\xff");
        $dictionaryObj = new InternalType\DictionaryObject($srcArray);
        $this->assertEquals($dictionaryObj->toString(),
                            '<</Bool false /Number 100.426 /Name /MyName /Text (some text) /BinaryText <010200FF> >>');
    }

    public function testAdd()
    {
        $dictionaryObj = new InternalType\DictionaryObject();
        $dictionaryObj->add(new InternalType\NameObject('Var1'), new InternalType\BooleanObject(false));
        $dictionaryObj->add(new InternalType\NameObject('Var2'), new InternalType\NumericObject(100.426));
        $dictionaryObj->add(new InternalType\NameObject('Var3'), new InternalType\NameObject('MyName'));
        $dictionaryObj->add(new InternalType\NameObject('Var4'), new InternalType\StringObject('some text'));
        $this->assertEquals($dictionaryObj->toString(),
                            '<</Var1 false /Var2 100.426 /Var3 /MyName /Var4 (some text) >>');
    }

}
