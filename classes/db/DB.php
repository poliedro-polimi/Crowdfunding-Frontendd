<?php
namespace nigiri\db;

use nigiri\Site;

/**
 * Database Abstraction
 */
abstract class DB
{
    const RESULT_ARRAY = 0;
    const RESULT_ASSOC = 1;
    const RESULT_OBJECT = 2;

    private static $requestsLog = [];

    /**
     * DB constructor.
     * @param array $data the initialization data to start a DB connection
     */
    abstract public function __construct($data);

    /**
     * Gives informations about all the database requests made in this http request
     * Useful for debugging. Works only in DEBUG mode!
     * @return array
     */
    public static function getRequests()
    {
        return self::$requestsLog;
    }

    /**
     * Execute a query
     * @param $sql
     * @param bool $oneshot
     * @param int $mode
     */
    public function query($sql,$oneshot=false,$mode=DB::RESULT_ASSOC){
        if (Site::getParam('debug')) {
            self::$requestsLog[] = array($sql);
        }

        self::doQuery($sql, $oneshot, $mode);
    }

    /**
     * Executes a query
     * @param $sql: the SQL query to Execute
     * @param $oneshot: boolean, if true the method returns immediately only the first result row of the query. Default False
     * @param $mode: Only useful when $oneshot is true. Tells which type of output should be used for the result.
     * @see DbResult::fetch
     * @return DBResult|array returns an object of class DbResult. If $oneshot is true return an array or an object
     * containing the first row of the result set in the format specified by $mode
     * @throws DBException if the query doesn't execute correctly
     */
    abstract protected function doQuery($sql,$oneshot=false,$mode=DB::RESULT_ASSOC);

    /**
     * Starts a transaction if the DBMS supports it
     * @param null|array $options a set of options to customize the transaction, such as isolation level, etc
     * @throws DBException if the DB doesn't support Transactions
     */
    abstract public function startTransaction($options = null);

    /**
     * Commits the transaction
     * @throws DBException if there is no transaction active
     */
    abstract public function commitTransaction();

    /**
     * Rolls back the transaction
     * @throws DBException if there is no transaction active
     */
    abstract public function rollbackTransaction();

    /**
     * Tells whether we are in a transaction or not
     * @return bool true if we are in a transaction. False otherwise
     */
    abstract public function isTransactionActive();

    /**
     * Escapes data to make it usable in a with SQL statement as data
     * @param $data: the data to escape. It can be numeric, string or bool
     * @return string $data ready to be used as data in a SQL statement
     */
    abstract public function escape($data);

    /**
     * @param $numeric: boolean. if true returns the number of the error, if false returns the description string. Default False
     * @return string|int the last error generated by the DBMS
     */
    abstract public function getLastError($numeric=false);

    /**
     * Gets the number of affected rows by the last executed query
     * @return int an integer representing the affected rows
     */
    abstract public function getLastAffectedRows();

    /**
     * Gets the ID of record created by the last INSERT INTO query
     * @return int an integer representing the id
     */
    abstract public function getLastInsertId();

    /**
     * Closes the DB connection
     */
    abstract public function close();
}
