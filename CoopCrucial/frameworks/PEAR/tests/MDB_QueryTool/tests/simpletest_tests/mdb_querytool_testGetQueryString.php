<?php
// $Id: mdb_querytool_testGetQueryString.php,v 1.6 2007/01/10 15:11:01 quipo Exp $

require_once dirname(__FILE__).'/mdb_querytool_test_base.php';


class TestOfMDB_QueryTool_GetQueryString extends TestOfMDB_QueryTool
{
    function TestOfMDB_QueryTool_GetQueryString($name = __CLASS__) {
        $this->UnitTestCase($name);
    }
    function test_selectAll() {
        $this->qt =& new MDB_QT(TABLE_QUESTION);
        if (DB_TYPE == 'ibase') {
            $expected = 'SELECT question.id AS id,question.question AS question FROM question';
        } else {
            $expected = 'SELECT question.'.$this->qt->db->quoteIdentifier('id').' AS '.$this->qt->db->quoteIdentifier('id')
                       .',question.'.$this->qt->db->quoteIdentifier('question').' AS '.$this->qt->db->quoteIdentifier('question')
                       .' FROM question';
        }
        $this->assertEqual($expected, $this->qt->getQueryString());
    }
    function test_selectWithWhere() {
        $this->qt =& new MDB_QT(TABLE_QUESTION);
        $this->qt->setWhere('id=1');
        if (DB_TYPE == 'ibase') {
            $expected = 'SELECT question.id AS id,question.question AS question FROM question WHERE id=1';
        } else {
            $expected = 'SELECT question.'.$this->qt->db->quoteIdentifier('id').' AS '.$this->qt->db->quoteIdentifier('id')
                       .',question.'.$this->qt->db->quoteIdentifier('question').' AS '.$this->qt->db->quoteIdentifier('question')
                       .' FROM question WHERE id=1';
        }
        $this->assertEqual($expected, $this->qt->getQueryString());
    }
    function test_selectWithJoin() {
        $this->qt =& new MDB_QT(TABLE_QUESTION);
        $joinOn = TABLE_QUESTION.'.id='.TABLE_ANSWER.'.question_id';
        $this->qt->setJoin(TABLE_ANSWER, $joinOn, 'left');

        if (DB_TYPE == 'ibase') {
            $expected = 'SELECT answer.id AS t_answer_id,answer.answer AS t_answer_answer,answer.question_id AS t_answer_question_id,question.id AS id,question.question AS question FROM question LEFT JOIN answer ON question.id=answer.question_id';
        } else {
            $expected = 'SELECT answer.'.$this->qt->db->quoteIdentifier('id').' AS '.$this->qt->db->quoteIdentifier('_answer_id')
                       .',answer.'.$this->qt->db->quoteIdentifier('answer').' AS '.$this->qt->db->quoteIdentifier('_answer_answer')
                       .',answer.'.$this->qt->db->quoteIdentifier('question_id').' AS '.$this->qt->db->quoteIdentifier('_answer_question_id')
                       .',question.'.$this->qt->db->quoteIdentifier('id').' AS '.$this->qt->db->quoteIdentifier('id')
                       .',question.'.$this->qt->db->quoteIdentifier('question').' AS '.$this->qt->db->quoteIdentifier('question')
                       .' FROM question LEFT JOIN answer ON question.id=answer.question_id';
        }
        $this->assertEqual($expected, $this->qt->getQueryString());
    }
    function test_selectOneColumn() {
        $this->qt =& new MDB_QT(TABLE_QUESTION);
        $this->qt->setWhere('id=1');
        $this->qt->setSelect('id');
        if (DB_TYPE == 'ibase') {
            $expected = 'SELECT id FROM question WHERE id=1';
        } else {
            $expected = 'SELECT '.$this->qt->db->quoteIdentifier('id').' FROM question WHERE id=1';
        }
        $this->assertEqual($expected, $this->qt->getQueryString());
    }
    function test_selectTwoColumns() {
        $this->qt =& new MDB_QT(TABLE_QUESTION);
        $this->qt->setWhere('id=1');
        $this->qt->setSelect('id,answer');
        if (DB_TYPE == 'ibase') {
            $expected = 'SELECT id,answer FROM question WHERE id=1';
        } else {
            $expected = 'SELECT '.$this->qt->db->quoteIdentifier('id')
                        .','.$this->qt->db->quoteIdentifier('answer')
                        .' FROM question WHERE id=1';
        }
        $this->assertEqual($expected, $this->qt->getQueryString());
    }
    function test_prependTableName() {
        $this->qt =& new MDB_QT(TABLE_QUESTION);
        $table = TABLE_QUESTION;

        $fieldlist = 'question';
        $fieldlist = $this->qt->_prependTableName($fieldlist, TABLE_QUESTION);
        $this->assertEqual($fieldlist, TABLE_QUESTION.'.question');

        $fieldlist = 'fieldname1,question';
        $fieldlist = $this->qt->_prependTableName($fieldlist, TABLE_QUESTION);
        $this->assertEqual($fieldlist, 'fieldname1,'.TABLE_QUESTION.'.question');
        
        $fieldlist = 'fieldname1,'.TABLE_QUESTION.'.question,fieldname2';
        $fieldlist = $this->qt->_prependTableName($fieldlist, TABLE_QUESTION);
        $this->assertEqual($fieldlist, 'fieldname1,'.TABLE_QUESTION.'.question,fieldname2');
    }
    function test_quoteIdentifierWithFunctions() {
        $this->qt =& new MDB_QT(TABLE_QUESTION);
        $table = TABLE_QUESTION;

        $this->qt->setSelect('question, COUNT(DISTINCT id) AS num_questions');
        $this->qt->setGroup('question');
        
        if (DB_TYPE == 'ibase') {
            $expected = 'SELECT question,COUNT(DISTINCT id) AS num_questions FROM question  GROUP BY question.question';
        } else {
            $expected = 'SELECT '.$this->qt->db->quoteIdentifier('question')
                       .',COUNT(DISTINCT id) AS '.$this->qt->db->quoteIdentifier('num_questions')
                       .' FROM question  GROUP BY question.question';
        }
        $this->assertEqual($expected, $this->qt->getQueryString());
    }
}

if (!defined('TEST_RUNNING')) {
    define('TEST_RUNNING', true);
    $test = &new TestOfMDB_QueryTool_GetQueryString();
    $test->run(new HtmlReporter());
}
?>