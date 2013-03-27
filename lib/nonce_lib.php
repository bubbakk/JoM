<?php
/*
 * Name: NONCE-LIB
 * Created By: Andrea Ferroni (http://bubbakk.ivellezza.it -
 * Created On: March 2013
 * Version: 0.7
 */

/*
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

/*
   Function generate_nonce
   Generate an hash using an algorithm passed as parameter

   Parameters:
     $action    - a string representing the action to be used as part of hash argument
     $itemid    - string or integer representi the id the action applies, to be used as part of hash argument
     $userid    - user ID (or session ID) that represents, uniquely, a user the hash/nonce is to be sent, to be used as part of hash argument
     $timestamp - Unix epoch when the nonce is generated
     $salt      - application salt, unique, useful for singular encoding
     $alg       - hash algortim to use

   Return:
     boolean value false if algorithm passed is not supported, the hash otherwise
*/
function generate_nonce($action, $itemid, $userid, $timestamp, $salt, $alg) {
    if ( !in_array($alg, hash_algos()) ) {
        return false;
    }

    return hash($alg, $action . $itemid . $userid . $timestamp . $salt);
}

function generate_html_input_form_nonces($action, $itemid, $userid, $salt, $alg) {
    // generate timestamp
    $timestamp       = time();
    // generate input hidden fields
    $input_nonce     = '<input type="hidden" name="_nonce" value="'.generate_nonce($action, $itemid, $userid, $timestamp, $salt, $alg).'">';
    $input_timestamp = '<input type="hidden" name="_timestamp" value="'.$timestamp.'">';

    return $input_nonce . "\n" . $input_timestamp;
}

function generate_html_get_params_nonces($action, $itemid, $userid, $salt, $alg) {
    // generate timestamp
    $timestamp  = time();
    // generate link parameters
    $link_get_parms = 'n='.generate_nonce($action, $itemid, $userid, $timestamp, $salt, $alg).'&t='.$timestamp;

    return $link_get_parms;
}

function generate_json_values($action, $itemid, $userid, $salt, $alg) {
    // generate timestamp
    $timestamp  = time();
    // generate nonce
    $nonce      = generate_nonce($action, $itemid, $userid, $timestamp, $salt, $alg);
    return array('timestamp' => $timestamp, 'nonce' => $nonce);
}

function generate_json_javascript_values($action, $itemid, $userid, $salt, $alg) {
    // generate timestamp
    $timestamp  = time();
    // generate nonce
    $nonce      = generate_nonce($action, $itemid, $userid, $timestamp, $salt, $alg);
    return json_encode( array('timestamp' => $timestamp, 'nonce' => $nonce) );
}

/*
   Function check_nonce
   Check nonce correctness. Tests done are:
     - check that is the same
     - check that is not expired
     - check that is not already used
   If is correct, put it in the table of used nonces

   Parameters:
     $action    - a string representing the action to be used as part of hash argument
     $itemid    - string or integer representi the id the action applies, to be used as part of hash argument
     $userid    - user ID (or session ID) that represents, uniquely, a user the hash/nonce is to be sent, to be used as part of hash argument
     $timestamp - Unix epoch when the nonce is generated
     $salt      - application salt, unique, useful for singular encoding
     $alg       - hash algortim to use
     $original_nonce - nonce to compare to
     $expire_time - nonce time validity (in seconds)
     $pdo_dbh   - PDO database handler

   Returns:
     boolean true if original_nonce is a valid one; -1 if expired; boolean false if other checks fail
*/
function check_nonce($action, $itemid, $userid, $timestamp, $salt, $alg, $original_nonce, $expire_time, $pdo_dbh) {
    // regenerate nonce
    //echo "$action . $itemid . $userid . $timestamp . ".htmlentities($salt)." . $alg . $original_nonce<br>\n";
    $check_nonce = generate_nonce($action, $itemid, $userid, $timestamp, $salt, $alg );

    // check if the regenerated nonce is the same as the one sent by the user
    if ( $check_nonce != $original_nonce ) {
        return false;
    }

    // check that is not expired
    $now       = time();
    $diff_time = $now - $timestamp;
    if ( $diff_time > $expire_time ) {
        return -1;
    }

    // check that the nonce is not used before
    try {
        $check_stmt = $pdo_dbh->prepare('SELECT (COUNT(Nonce_nonce) > 0) AS is_used '.
                                        '  FROM Nonces '.
                                        ' WHERE Nonce_nonce     = :nonce '.
                                        '   AND Nonce_timestamp = :timestamp ');
        $check_stmt->bindParam(":nonce",     $check_nonce, PDO::PARAM_STR);
        $check_stmt->bindParam(":timestamp", $timestamp,   PDO::PARAM_INT);
        $check_stmt->execute();
        $is_used = $check_stmt->fetchColumn();        // fetch first column (is_used)

        if ( $is_used ) {
            return false;
        }
    }
    catch (PDOException $e) {
        return false;
    }


    // insert in the database in the user-nonces table
    try {
        $insert_stmt = $pdo_dbh->prepare('INSERT INTO Nonces ( Nonce_nonce, Nonce_timestamp) '.
                                         '      VALUE ( :nonce, :timestamp )');
        $insert_stmt->bindParam(":nonce",     $check_nonce, PDO::PARAM_STR);
        $insert_stmt->bindParam(":timestamp", $timestamp,   PDO::PARAM_INT);
        $insert_stmt->execute();

        if ( $insert_stmt->rowCount() != 1 ) {
            return false;
        }
    }
    catch (PDOException $e) {
        echo $e->getMessage().' line: '. __LINE__;
        return false;
    }

    return true;
}
