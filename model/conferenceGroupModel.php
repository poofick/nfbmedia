<?php
class conferenceGroupModel extends Model {
	
    public static $use = 'conference_group';
    
    public function delete($creator_id, $id) {
    	
    	$this->prepare_execute('UPDATE `'.self::$use.'` SET `deleted`=NOW() WHERE `id`=:id AND `creator_id`=:creator_id LIMIT 1', array(':id' => $id, ':creator_id' => $creator_id));
    	
    }
	
}