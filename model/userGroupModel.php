<?php
class userGroupModel extends Model {
	
    public static $use = 'user_group';
    
    public function delete($creator_id, $id) {
    	
    	$this->prepare_execute('UPDATE `'.self::$use.'` SET `deleted`=NOW() WHERE `id`=:id AND `creator_id`=:creator_id LIMIT 1', array(':id' => $id, ':creator_id' => $creator_id));
    	
    }
    
    public function get_list($creator_id = false) {
			
			$result = array();
			
			$query = 'SELECT * FROM `'.self::$use.'` WHERE `deleted` IS NULL '.($creator_id ? ' AND `creator_id`=:creator_id ' : '').' ORDER BY `id`';
			if($result = functionsModel::array_fill_key($this->prepare_execute($query, array(':creator_id' => $creator_id))->fetchAll(), 'id')) {
				$user_ids = array();
				foreach($result as $k => $v) {
					$ids = $v['users'] ? explode(',', $v['users']) : array();
					$user_ids = array_unique(array_merge($user_ids, $ids));
					$result[$k]['users'] = $ids;
				}
				
				if($user_ids) {
					$query = 'SELECT * FROM `user` WHERE `id` IN ('.implode(',', $user_ids).') AND `level` IN('.userModel::USER_LEVEL_REGIONAL_DIRECTOR.','.userModel::USER_LEVEL_CUSTOMER.') AND `deleted` IS NULL ORDER BY `level`, `id`';
					if($users = functionsModel::array_fill_key($this->prepare_execute($query)->fetchAll(), 'id')) {
						/*foreach($users as $user) {
							$result[$user['group_id']]['user_list'][] = $user;
						}*/
						
						foreach($result as $k => $v) {
							foreach($result[$k]['users'] as $kk => $vv) {
								$result[$k]['user_list'][$vv] = $users[$vv];
							}
						}
					}
				}
			}
			
			return $result;
		}
	
}