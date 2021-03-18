<?php

namespace models;

use classes\{DB};
use models\User;

class UserRelation {
    private $db,
    $id,
    $from,
    $to,
    $status,
    $since;
    
    public function __construct() {
        $this->db = DB::getInstance();
    }

    public function get_property($propertyName) {
        return $this->$propertyName;
    }
    
    public function set_property($propertyName, $propertyValue) {
        $this->$propertyName = $propertyValue;
    }

    public function set_data($data = array()) {
        $this->from = $data["from"];
        $this->to = $data["to"];
        $this->status = $data["status"];
        $this->since = (empty($data["since"]) || !strtotime($data["since"])) ? date("Y/m/d H:i:s") : $data["since"];
    }

    public function send_request() {
        // Only send request if there's no existed relation between the two users
        $existed_relation_status = $this->bidirectional_relation_exists();

        if(!$existed_relation_status) {
            $this->db->query("INSERT INTO user_relation (`from`, `to`, `status`, `since`) 
                VALUES (?, ?, ?, ?)", array(
                    $this->from,
                    $this->to,
                    "P",
                    date("Y/m/d h:i:s")
                )
            );

            return true;
        }

        return false;
    }

    public function cancel_request() {
        /*
            First we need to check if there's a pending request
        */

        $pending_request_exists = $this->micro_relation_exists($this->from, $this->to, "P");

        if($pending_request_exists) {
            $this->delete_relation("P");
        }
    }

    public function accept_request() {
        /*
            Only accept request if there's a realtion between the two, AND the returned status is P (pending)
            when the condition is true, we need to update the pending relation record to friend and insert new friend realtion
            in the other direction. e.g. A send friend request to B, if there's a Pending request between the two in one of the direction, we need to update 
            that P entry to A to B (friend) and add record -> B to A (friend)
        */
        // Only send request if there's no existed relation between the two users
        $existed_relation_status = $this->bidirectional_relation_exists();
        
        if($existed_relation_status === "P") {
            /* 
                Here w passed P as argument to update method to tell to update record with:
                                                                                            from=$this->from
                                                                                            to=$this->to
                                                                                            status="P"
                and change it to be like :
                                                                                            from=$this->from
                                                                                            to=$this->to
                                                                                            status="F"
            */

            // Update the current direction record
            $this->status = "F";
            $this->update("P");

            // Add the other direction record
            $other_end = new UserRelation();
            $other_end->set_data(array(
                'from'=>$this->to,
                'to'=>$this->from,
                'status'=>"F",
                'since'=>date("Y/m/d h:i:s")
            ));
            $other_end->add();

            return true;
        }

        return false;
    }

    public function unfriend() {
        // Only unfriend if there's a relation between the two and it shoulf be friend relationship
        $existed_relation_status = $this->get_relation_by_status("F");
        if($existed_relation_status) {
            $this->delete_relation("F");

            $relation = new UserRelation();
            $relation->set_property("from", $this->to);
            $relation->set_property("to", $this->from);

            $relation->delete_relation("F");
            return true;
        }

        return false;
    }

    public function block() {
        /*
            This function will check if there's a BLOCK relation between the two; If so then unblock them; Otherwise 
            from user will block to user.
            Notice users who are not friends to each others cannot block each others
        */
        $friendship_relation_exists = $this->micro_relation_exists($this->from, $this->to, "F");
        $exists = $this->micro_relation_exists($this->from, $this->to, "B");

        if($friendship_relation_exists) {
            // Unblock
            if($exists) {
                $this->db->query("DELETE FROM user_relation WHERE `from` = ? AND `to` = ? AND `status` = ?"
                ,array(
                    $this->from,
                    $this->to,
                    "B"
                ));
            // Block
            } else {
                $this->db->query("INSERT INTO user_relation (`from`, `to`, `status`, `since`) 
                VALUES (?, ?, ?, ?)", array(
                    $this->from,
                    $this->to,
                    "B",
                    date("Y/m/d h:i:s")
                ));
            }

            return true;
        }

        return false;
    }

    public function add() {
        $existed_relation_status = $this->bidirectional_relation_exists();
        
        if($existed_relation_status) {
            $this->db->query("INSERT INTO user_relation (`from`, `to`, `status`, `since`) 
                VALUES (?, ?, ?, ?)", array(
                    $this->from,
                    $this->to,
                    $this->status,
                    date("Y/m/d H:i:s")
                )
            );
        }

        return true;
    }

    public function update($status) {
        /*
            Notice when we need to update a record in user_relation table we need the status to be updated to be passed
            as argument to update; in order to have a unique identifier(from, to, $status) to identify the record to be updated
            Also notice that from and to properties are not meant by update
        */

        $this->db->query("UPDATE user_relation SET `status`=?, `since`=? WHERE `from`=? AND `to`=? AND `status` = ?",
            array(
                $this->status,
                $this->since,
                $this->from,
                $this->to,
                $status
            )
        );

        return true;
    }

    public function delete_relation($status="") {
        /*
            This function delete any relation between the two users in from and to properties
            I added $status parameter; If you don't pass anything to status it will delete every relation between the two;
            but if you specify the status(for exemple "P") it will only delete the pending friend request
            When we want to delete a relation record we only need the relation to be present regardless of the type of status returned by bidirectional_relation_exists()
        */
        $existed_relation_status = $this->bidirectional_relation_exists();
        
        if($existed_relation_status) {
            $query = "DELETE FROM user_relation WHERE `from` = ? AND `to` = ?";
            !empty($status) ? $query .= " AND `status` = '$status'" : $query;
            $this->db->query($query, 
            array(
                $this->from,
                $this->to
            ));

            return true;
        }

        return false;
    }

    public static function get_friends($user_id) {
        /*
            This function will take user_id and find his friends in relations table and get his frinds in form of users
            using User class and fetch each user based on his id and return array of friends
        */

        DB::getInstance()->query("SELECT * FROM user_relation WHERE `from` = ? AND `status` = 'F'",
        array(
            $user_id
        ));

        $relations = DB::getInstance()->results();
        $friends = array();

        foreach($relations as $relation) {
            $friend_id = $relation->to;

            $user = new User();
            $user->fetchUser("id", $friend_id);

            $friends[] = $user;
        }

        return $friends;
    }

    public static function get_friends_number($user_id) {
        DB::getInstance()->query("SELECT * FROM user_relation WHERE `from` = ? AND `status` = 'F'",
        array(
            $user_id
        ));

        return DB::getInstance()->count();
    }

    public function get_relation_by_status($status) {
        $this->db->query("SELECT * FROM user_relation WHERE `from` = ? AND `to` = ? AND `status` = ?",
        array(
            $this->from,
            $this->to,
            $status
        ));

        if($this->db->count() > 0) {
            return $this->db->results()[0]->status;
        } else {
            return false;
        }
    }

    public function micro_relation_exists($from, $to, $status) {
        $this->db->query("SELECT * FROM user_relation WHERE `from` = ? AND `to` = ? AND `status` = ?",
        array(
            $from,
            $to,
            $status
        ));

        if($this->db->count() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function bidirectional_relation_exists() {
        /*
            this function will check if there's a relation between two users
            Note: Notice when we perform a check we check in both direction if(there's a relation created from A to B and also from B to A)
            e.g. user B could send friend request to user A and when we want to check if there's a relation between A and B we need
            also to check if there's a relation between B to A because in this case It exists between B to A
            
            return: returns the type of relation in case a relation exists, otherwise returns false
        */
        $this->db->query("SELECT * FROM user_relation WHERE (`from` = ? AND `to` = ?) OR (`from` = ? AND `to` = ?)",
        array(
            $this->from,
            $this->to,
            $this->to,
            $this->from,
        ));

        if($this->db->count() > 0) {
            return $this->db->results()[0]->status;
        } else {
            return false;
        }
    }

    public static function get_friendship_requests($user_id) {
        DB::getInstance()->query("SELECT * FROM user_relation WHERE `to` = ? AND `status` = ?",
        array(
            $user_id,
            'P'
        ));

        return DB::getInstance()->results();
    }
}
