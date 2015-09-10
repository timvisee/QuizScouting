<?php

namespace app\question;

use app\database\Database;
use app\registry\Registry;
use Exception;
use PDO;

// Prevent direct requests to this file due to security reasons
defined('APP_INIT') or die('Access denied!');

class Question {

    /** @var int The question ID. */
    private $id;

    /**
     * Constructor.
     *
     * @param int $id Question ID.
     */
    public function __construct($id) {
        $this->id = $id;
    }

    /**
     * Get the question ID.
     *
     * @return int The question ID.
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Get a value from the database from this specific question.
     *
     * @param string $columnName The column name.
     *
     * @return mixed The value.
     *
     * @throws Exception Throws if an error occurred.
     */
    private function getDatabaseValue($columnName) {
        // Prepare a query for the database to list questions with this ID
        $statement = Database::getPDO()->prepare('SELECT ' . $columnName . ' FROM ' . QuestionManager::getDatabaseTableName() . ' WHERE q_id=:q_id');
        $statement->bindValue(':q_id', $this->getId(), PDO::PARAM_INT);

        // Execute the prepared query
        if(!$statement->execute())
            throw new Exception('Failed to query the database.');

        // Return the result
        return $statement->fetch(PDO::FETCH_ASSOC)[$columnName];
    }

    /**
     * Get the question.
     *
     * @return string Question.
     *
     * @throws Exception Throws an exception if an error occurred.
     */
    public function getQuestion() {
        return $this->getDatabaseValue('q_q');
    }

    /**
     * Get answer A.
     *
     * @return string Answer A.
     *
     * @throws Exception Throws an exception if an error occurred.
     */
    public function getAnswerA() {
        return $this->getDatabaseValue('q_a');
    }

    /**
     * Get answer B.
     *
     * @return string Answer B.
     *
     * @throws Exception Throws an exception if an error occurred.
     */
    public function getAnswerB() {
        return $this->getDatabaseValue('q_b');
    }

    /**
     * Get answer C.
     *
     * @return string Answer C.
     *
     * @throws Exception Throws an exception if an error occurred.
     */
    public function getAnswerC() {
        return $this->getDatabaseValue('q_c');
    }

    /**
     * Get answer D.
     *
     * @return string Answer D.
     *
     * @throws Exception Throws an exception if an error occurred.
     */
    public function getAnswerD() {
        return $this->getDatabaseValue('q_d');
    }

    /**
     * Get the letter of the correct answer.
     *
     * @return string Correct answer letter.
     *
     * @throws Exception Throws an exception if an error occurred.
     */
    public function getCorrectAnswer() {
        return $this->getDatabaseValue('q_correct');
    }

    /**
     * Get the registry key for a team answer.
     *
     * @param String $team The team letter.
     *
     * @return string
     */
    private function getAnswerRegistryString($team) {
        return 'question.' . $this->getID() . '.' . trim($team) . '.answer';
    }

    public function getTeamAnswer($team) {
        // Get the registry value
        $regValue = Registry::getValue($this->getAnswerRegistryString($team));

        if($regValue === null)
            return '';

        return $regValue->getValue();
    }

    public function hasTeamAnswer($team) {
        return Registry::isValueWithKey($this->getAnswerRegistryString($team));
    }

    public function setTeamAnswer($team, $question) {
        Registry::setValue($this->getAnswerRegistryString($team), trim($question));
    }

    public function haveAllTeamsAnswered() {
        return $this->hasTeamAnswer(1) && $this->hasTeamAnswer(2) && $this->hasTeamAnswer(3);
    }
}
