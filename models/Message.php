<?php

namespace models;

use classes\{DB};

class Message {
    private $db,
    $id,
    $message_sender,
    $message_receiver,
    $message,
    $message_date = '',
    $recipient_id,
    $is_read = false,
    $is_reply=null,
    $reply_to=null;
    
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
        $this->message_sender = $data["sender"];
        $this->message_receiver = $data["receiver"];
        $this->message = $data["message"];
        $this->message_date = $data["message_date"];
    }

    public static function exists($message_id) {
        DB::getInstance()->query("SELECT * FROM message WHERE id = ?", array($message_id));

        return DB::getInstance()->count();
    }

    public function get_message($property, $value) {
        $this->db->query("SELECT * FROM `message` WHERE `$property` = ?", array($value));

        if($this->db->count() > 0) {
            $fetched_message = $this->db->results()[0];

            $this->id = $fetched_message->id;
            $this->message_sender = $fetched_message->message_creator;
            $this->message = $fetched_message->message;
            $this->message_date = $fetched_message->create_date;
            $this->is_reply = $fetched_message->is_reply;
            $this->reply_to = $fetched_message->reply_to;

            return true;
        }

        return false;
    }

    public static function get_creator_by_id($message_id) {
        DB::getInstance()->query("SELECT message_creator FROM `message` WHERE `id` = ?", array($message_id));

        if(DB::getInstance()->count() > 0) {
            $fetched_message = DB::getInstance()->results()[0];
            return $fetched_message;
        }

        return false;
    }

    public static function get_message_obj($property, $value) {
        DB::getInstance()->query("SELECT * FROM `message` WHERE `$property` = ?", array($value));

        if(DB::getInstance()->count() > 0) {
            $fetched_message = DB::getInstance()->results()[0];
            return $fetched_message;
        }

        return false;
    }

    public function add() {
        /*
            When we add a message to message table we need also to insert a row to recipient table to specify the receiver
            of that message
        */

        $this->db->query("INSERT INTO `message` 
        (`message_creator`, `message`, `create_date`, `is_reply`, `reply_to`) 
        VALUES (?, ?, ?, ?, ?)", array(
            $this->message_sender,
            $this->message,
            $this->message_date,
            $this->is_reply,
            $this->reply_to,
        ));

        $message_row_inserted = $this->db->error();

        // GET LAST MESSAGE ID TO GIVE IT TO RECIPIENT TABLE
        $last_inserted_message_id = $this->db->pdo()->lastInsertId();

        /* 
            Here notice that we need to check if the receiver user is subscribed to the channel, and If so then we pass the message
            to the channel, otherwise we normally store it in the database.
        */
        $this->db->query("INSERT INTO `channel` 
        (`sender`, `receiver`, `group_recipient_id`, `message_id`) 
        VALUES (?, ?, ?, ?)", array(
            $this->message_sender,
            $this->message_receiver,
            null,
            $last_inserted_message_id
        ));

        $channel_row_inserted = $this->db->error();

        $this->db->query("INSERT INTO `message_recipient` 
        (`receiver_id`, `message_id`, `is_read`) 
        VALUES (?, ?, ?)", array(
            $this->message_receiver,
            $last_inserted_message_id,
            $this->is_read
        ));

        $message_recipient_row_inserted = $this->db->error();

        return $this->db->error() == false ? $last_inserted_message_id : false;
    }

    public function update_property($property) {
        $this->db->query("UPDATE `message` SET $property=? WHERE id=?",
        array(
            $this->$property,
            $this->id
        ));

        return ($this->db->error()) ? false : true;
    }

    public function delete_sended_message() {
        /*
            For now this function delete the message for every one, later try to add the posibility to only deleted the
            message for the sender
        */
        // First we delete the message from recipient table
        $this->delete_received_message();
        // Then we detach or actually delete the whole message from message table
        $this->db->query("DELETE FROM `message` WHERE id = ?", array(
            $this->id
        ));

        return ($this->db->error()) ? false : true;
    }

    public function delete_received_message() {
        /*
            In this case we just need to delete the message from recipient table because we want to be there in message
            for the sender to see it. The message will be deleted just from the reeiver of the message
        */

        $this->db->query("DELETE FROM message_recipient WHERE message_id = ?", array(
            $this->id
        ));

        return ($this->db->error()) ? false : true;
    }

    public static function dump_channel($sender, $receiver) {
        DB::getInstance()->query("DELETE FROM channel WHERE sender = ? AND receiver = ?", array($sender, $receiver));
    }

    public static function getMessages($sender, $receiver) {
        //$this->db->query("SELECT * FROM `message_recipient` WHERE receiver_id = ? AND message.message_creator = ? ");
        DB::getInstance()->query("SELECT * FROM `message_recipient`
        INNER JOIN `message` 
        ON message.id = message_recipient.message_id 
        WHERE message_recipient.receiver_id = ? AND message.message_creator = ?", array(
            $receiver,
            $sender
        ));

        return DB::getInstance()->results();
    }

    public function get_message_recipient_data() {
        DB::getInstance()->query("SELECT * FROM `message_recipient`
        WHERE message_recipient.message_id = ?", array(
            $this->id
        ));

        return DB::getInstance()->results()[0];
    }

    public function add_writing_message_notifier() {
        $this->db->query("INSERT INTO `writing_message_notifier` 
        (`message_writer`, `message_waiter`) 
        VALUES (?, ?)", array(
            $this->message_sender,
            $this->message_receiver,
        ));

        return $this->db->error() == false ? true : false;
    }

    public function delete_writing_message_notifier() {
        $this->db->query("DELETE FROM `writing_message_notifier` WHERE `message_writer` = ? AND `message_waiter` = ?"
        , array(
            $this->message_sender,
            $this->message_receiver,
        ));

        return $this->db->error() == false ? true : false;
    }

    public static function get_discussions($user_id) {
        DB::getInstance()->query("CALL sp_get_discussions(?)", array($user_id));

        return DB::getInstance()->results();
    }

    public function jsonSerialize()
    {
        $vars = get_object_vars($this);
        return $vars;
    }
}
