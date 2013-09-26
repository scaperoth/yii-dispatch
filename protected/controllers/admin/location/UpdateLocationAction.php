<?php

class UpdateLocationAction extends CAction {

    public function run() {
        if (Yii::app()->request->isPostRequest) {
            $vars = $_POST['UpdateLocation'];
            $loc_id = $vars['locationnameupdate'];
            $ip_address = $vars['ipaddressupdate'];
            $comp_name = $vars['computernameupdate'];

            $db_rows = Ips::model()->findAll();
            foreach ($db_rows as $row) {
                if (md5($row['ip_loc_id']) == $loc_id) {
                        $pk = $row['ip_id'];
                        break;
                }
            }
            
            $ip_update_field = Ips::model()->findByPk($pk);
            
            $ip_update_field->ip_address=$ip_address;
            $ip_update_field->ip_compname = $comp_name;
            $ip_update_field->save();
            echo 'updated';
            Yii::app()->user->setFlash('success', 'Location updated.');
        } else {
            Yii::app()->user->setFlash('error', 'Access Denied.');
            $this->redirect(Yii::app()->user->returnUrl);
        }
    }

}

?>