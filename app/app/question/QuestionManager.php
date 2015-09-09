<?php

namespace app\question;

use app\config\Config;
use app\database\Database;
use Exception;
use PDO;

// Prevent direct requests to this file due to security reasons
defined('APP_INIT') or die('Access denied!');

class QuestionManager {

    /** The database table name. */
    const DB_TABLE_NAME = 'question';

    /**
     * Get the database table name of the questions.
     *
     * @return string The database table name.
     */
    public static function getDatabaseTableName() {
        return Config::getValue('database', 'table_prefix', '') . static::DB_TABLE_NAME;
    }

    /**
     * Get a list of all questions.
     *
     * @return array All questions.
     *
     * @throws Exception Throws an exception on failure.
     */
    public static function getQuestions() {
        // Build a query to select the questions
        $query = 'SELECT question_id FROM ' . static::getDatabaseTableName();

        // Execute the query
        $statement = Database::getPDO()->query($query);

        // Make sure the query succeed
        if($statement === false)
            throw new Exception('Failed to query the database.');

        // The list of questions
        $questions = Array();

        // Return the number of rows
        foreach($statement->fetchAll(PDO::FETCH_ASSOC) as $data)
            $questions[] = new Question($data['question_id']);

        // Return the list of questions
        return $questions;
    }

    /**
     * Get the number of questions.
     *
     * @return int Number of questions.
     *
     * @throws Exception Throws if an error occurred.
     */
    public static function getQuestionCount() {
        // Create a row count query on the database instance
        $statement = Database::getPDO()->query('SELECT question_id FROM ' . static::getDatabaseTableName());

        // Make sure the query succeed
        if($statement === false)
            throw new Exception('Failed to query the database.');

        // Return the number of rows
        return $statement->rowCount();
    }

    /**
     * Check if there's any question with the specified ID.
     *
     * @param int $id The ID of the question to check for.
     *
     * @return bool True if any question exists with this ID.
     *
     * @throws Exception Throws if an error occurred.
     */
    public static function isQuestionWithId($id) {
        // Make sure the ID isn't null
        if($id === null)
            throw new Exception('Invalid question ID.');

        // Prepare a query for the database to list questions with this ID
        $statement = Database::getPDO()->prepare('SELECT question_id FROM ' . static::getDatabaseTableName() . ' WHERE question_id=:id');
        $statement->bindParam(':id', $id, PDO::PARAM_INT);

        // Execute the prepared query
        if(!$statement->execute())
            throw new Exception('Failed to query the database.');

        // Return true if there's any question found with this ID
        return $statement->rowCount() > 0;
    }
}