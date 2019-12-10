<?php

/* This file has an empty PHP function for working with a
 * database.
 *
 * Copyright (C) 2000-2019, Darren Provine.  All rights reserved.
 */

/* NOTICE!
 * You cannot let user input define "$table" or "$column"!
 * Those cannot be loaded as parameters, which creates a security
 * hole!
 * You can create parameters for the arguments of "LIKE",
 * and you must always do so!
 */

// FetchArray() - run a query to get all records from a table
// Usage: $variable = FetchArray($dbh, $table)
// $dbh is database handle, $table is name of table
function FetchArray($dbh, $table)
{
    try {
        // set up query
        $query = "SELECT * FROM $table"; // <= this is dodgy

        // prepare to execute (this is part 1 of a security precaution)
        $stmt = $dbh->prepare($query);

        // run query
        $stmt->execute();

        // get all the results from database into array of objects
        $data = $stmt->fetchAll(PDO::FETCH_OBJ);
        // release the statement
        $stmt = null;

        return $data;
    }
    catch(PDOException $e)
    {
        die ('PDO error in FetchArray()": ' . $e->getMessage() );
    }
}


// FetchMatches() - return an array of rows that match a query
// Usage: $variable = FetchMatches($dbh, $table, $column, $string)
// $dbh is database handle, $table is name of table,
// $column is field being match, $string what to search
function FetchMatches($dbh, $table, $column, $string)
{
    try {
        // Note use of placeholders
        $phone_query = "SELECT * FROM $table " .
                       "WHERE $column LIKE :match";

        // prepare to execute
        $stmt = $dbh->prepare($phone_query);

        // bind variable to placeholders
        // note wildcards below
        $match = "%$string%";
        $stmt->bindParam(':match', $match);

        $stmt->execute();

        // debugging help if needed
        // $stmt->debugDumpParams();

        $phonedata = $stmt->fetchAll(PDO::FETCH_OBJ);
        $stmt = null;

        return $phonedata;
    }
    catch(PDOException $e)
    {
        die ('PDO error in ListMatchingPhones(): ' . $e->getMessage() );
    }
}

?>
