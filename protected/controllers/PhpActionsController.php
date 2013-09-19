<?php

class PhpActionsController extends Controller {

    public function actionExport() {
        if (Yii::app()->request->isPostRequest) {
            if (isset($_POST['employeeTrackingForm'])) {
                $query = LocationChangeTracking::model()->findAll();
                $attr = array('lct_id' => array('number'),
                    'lct_location' => array('text'),
                    'lct_user' => array('text'),
                    'lct_action' => array('text'),
                    'lct_on_time' => array('number'),
                    'lct_message' => array('text'),
                    'lct_timestamp' => array('time'),
                );
                $filename = 'ShopControlEmployeesTracking-upto--' . date('d-m-Y H-i') . ".csv";
            } elseif (isset($_POST['shopHoursForm'])) {
                $query = LocationHoursTracking::model()->findAll();
                $attr = array('lht_id' => array('number'),
                    'lht_username' => array('text'),
                    'lht_loc_name' => array('text'),
                    'lht_mon_open_hrs' => array('time'),
                    'lht_mon_closed_hrs' => array('time'),
                    'lht_tue_open_hrs' => array('time'),
                    'lht_tue_closed_hrs' => array('time'),
                    'lht_wed_open_hrs' => array('time'),
                    'lht_wed_closed_hrs' => array('time'),
                    'lht_thu_open_hrs' => array('time'),
                    'lht_thu_closed_hrs' => array('time'),
                    'lht_fri_open_hrs' => array('time'),
                    'lht_fri_closed_hrs' => array('time'),
                    'lht_sat_open_hrs' => array('time'),
                    'lht_sat_closed_hrs' => array('time'),
                    'lht_sun_open_hrs' => array('time'),
                    'lht_sun_closed_hrs' => array('time'),
                );
                $filename = 'ShopControlHoursTracking-upto--' . date('d-m-Y H-i') . ".csv";
            }
            CsvExport::export(
                    $query, // a CActiveRecord array OR any CModel array
                    $attr, true, // boolPrintRows
                    $filename
            );
        }
    }

    /**
     * 
     */
    public function actionOpenShop() {

        if (Yii::app()->request->isPostRequest) {
//administrative emails
            $admin_emails = Yii::app()->params['admin_emails'];
            echo $admin_emails . ' : ';
//full username
            $username = Yii::app()->user->getState('username');
//open or closed
            $action = 'open';
//get timestamp
            $timestamp = date('Y-m-d H:i:s');

//is user ontime?
            $on_time = 0;

//whether or not to send an email defaults to no
            $message = NULL;

//get curr location
            $location = $this->location;
//get current day of the week
            $dayofweek = strtolower(date('D'));
            $table_col = 'loc_' . $dayofweek . '_open_hrs';
//check open hours
            $check_query = Yii::app()->db->createCommand()
                    ->select($table_col)
                    ->from('locations l')
                    ->where('l.loc_name=:name', array(':name' => $location))
                    ->queryRow();

//translate databse open hours into php time()
            $openHrsTime = strtotime($check_query[$table_col]);

//get current time
            $currtime = date('d-m-Y H:i:s');

//get upper deviation of time. +- 10 minutes from db shop open time
            $open_upper_bound = date('d-m-Y H:i:s', $openHrsTime + 600);
            $open_lower_bound = date('d-m-Y H:i:s', $openHrsTime - 600);

//if current time is later than the open + 10 minutes
//shop was opened late
            if ($currtime > $open_upper_bound) {
                $currtime = new DateTime($currtime);
                $openDateTime = new DateTime($open_upper_bound);
                $difference = $currtime->diff($openDateTime, True);

                $message = $username . " opened the $location shop late. Latest opening time is " . date('H:ia', $openHrsTime + 600) . '. Late by: ' . $difference->h . ' hours ' . $difference->i . ' minutes and ' . $difference->s . ' seconds';
                Yii::app()->user->setFlash('error', "The shop has been opened late by " . $difference->h . ' hours ' . $difference->i . ' minutes and ' . $difference->s . ' seconds');
            }

//if current time is less than the open - 10 minutes
//shop was opened early
            else if ($currtime < $open_lower_bound) {
                $currtime = new DateTime($currtime);
                $openDateTime = new DateTime($open_lower_bound);
                $difference = $currtime->diff($openDateTime, True);


                $message = $username . " opened the. $location shop early by " . $difference->h . ' hours ' . $difference->i . ' minutes and ' . $difference->s . ' seconds';

                Yii::app()->user->setFlash('error', 'The shop has been opened early by ' . $difference->h . ' hours ' . $difference->i . ' minutes and ' . $difference->s . ' seconds');
            } else {
                $on_time = 1;
                $message = NULL;
            }

//if send messages is set to 1 then send the email. otherwise don't.
            if ($message != NULL && !Yii::app()->user->checkAccess('admin')) {


                $to = $admin_emails;
                $subject = 'Test email using PHP';
                $headers = 'From: acadtech@gwu.edu' . "\r\n" .
                        'Reply-To: scaperoth@gmail.com' . "\r\n" .
                        'X-Mailer: PHP/' . phpversion();

                mail($to, $subject, $message, $headers, '-facadtech.gwu.edu');

                $lct = new LocationChangeTracking();
                $lct->lct_location = $location;
                $lct->lct_user = $username;
                $lct->lct_action = $action;
                $lct->lct_on_time = $on_time;
                $lct->lct_message = $message;
                $lct->lct_timestamp = $timestamp;
                $lct->insert();
            }

            $loc_id = $this->loc_id;
            $new_status = 1;
            $location_model = Locations::model()->findByPk($loc_id);
            $location_model->loc_status = $new_status;
            $location_model->save(); // save the change to database


            echo $message;
        } else {
            Yii::app()->user->setFlash('error', 'Access Denied.');
            $this->redirect(Yii::app()->user->returnUrl);
        }
    }

    /**
     * 
     */
    public function actionCloseShop() {
        if (Yii::app()->request->isPostRequest) {
//administrative emails
            $admin_emails = Yii::app()->params['admin_emails'];
//full username
            $username = Yii::app()->user->getState('username');
//open or closed
            $action = 'close';
//get timestamp
            $timestamp = date('Y-m-d H:i:s');

//is user ontime?
            $on_time = 0;

            $message = NULL;

//get curr location
            $location = $this->location;
//get current day of the week
            $dayofweek = strtolower(date('D'));
            $table_col = 'loc_' . $dayofweek . '_closed_hrs';
//check open hours
            $check_query = Yii::app()->db->createCommand()
                    ->select($table_col)
                    ->from('locations l')
                    ->where('l.loc_name=:name', array(':name' => $location))
                    ->queryRow();

//translate databse open hours into php time()
            $openHrsTime = strtotime($check_query[$table_col]);

//get current time
            $currtime = date('d-m-Y H:i:s');

//get upper deviation of time. +- 10 minutes from db shop open time
            $open_upper_bound = date('d-m-Y H:i:s', $openHrsTime + 600);
            $open_lower_bound = date('d-m-Y H:i:s', $openHrsTime - 600);

//if current time is later than the open + 10 minutes
//shop was opened late
            if ($currtime > $open_upper_bound) {
                $currtime = new DateTime($currtime);
                $openDateTime = new DateTime($open_upper_bound);
                $difference = $currtime->diff($openDateTime, True);

                $message = $username . " closed the $location shop late. Latest closing time is " . date('H:ia', $openHrsTime + 600) . '. Late by: ' . $difference->h . ' hours ' . $difference->i . ' minutes and ' . $difference->s . ' seconds';
                Yii::app()->user->setFlash('error', "The shop has been closed late by " . $difference->h . ' hours ' . $difference->i . ' minutes and ' . $difference->s . ' seconds');
            }

//if current time is less than the open - 10 minutes
//shop was opened early
            else if ($currtime < $open_lower_bound) {
                $currtime = new DateTime($currtime);
                $openDateTime = new DateTime($open_lower_bound);
                $difference = $currtime->diff($openDateTime, True);


                $message = $username . " closed the $location shop early by " . $difference->h . ' hours ' . $difference->i . ' minutes and ' . $difference->s . ' seconds';
                Yii::app()->user->setFlash('error', "The shop has been closed early by " . $difference->h . ' hours ' . $difference->i . ' minutes and ' . $difference->s . ' seconds');
            } else {
                $on_time = 1;
                $message = NULL;
            }


            if ($message != NULL && !Yii::app()->user->checkAccess('admin')) {

                $to = $admin_emails;
                $subject = 'Test email using PHP';
                $headers = 'From: acadtech@gwu.edu' . "\r\n" .
                        'Reply-To: webmaster@example.com' . "\r\n" .
                        'X-Mailer: PHP/' . phpversion();

                mail($to, $subject, $message, $headers, '-facadtech.gwu.edu');

                $lct = new LocationChangeTracking();
                $lct->lct_location = $location;
                $lct->lct_user = $username;
                $lct->lct_action = $action;
                $lct->lct_on_time = $on_time;
                $lct->lct_message = $message;
                $lct->lct_timestamp = $timestamp;
                $lct->insert();
            }
//update status table
            $loc_id = $this->loc_id;
            $new_status = 0;
            $location_model = Locations::model()->findByPk($loc_id);
            $location_model->loc_status = $new_status;
            $location_model->save(); // save the change to database
//update tracking table


            echo $message;
        } else {
            Yii::app()->user->setFlash('error', 'Access Denied.');
            $this->redirect(Yii::app()->user->returnUrl);
        }
    }

    /**
     * 
     */
    public function actionUpdateHours() {
        if (Yii::app()->request->isPostRequest) {
            $db_rows = Locations::model()->findAll();
            $days_of_week = array(
                'monday',
                'tuesday',
                'wednesday',
                'thursday',
                'friday',
                'saturday',
                'sunday'
            );


            $rows_to_update = explode(',', $_POST['rows']);

            foreach ($db_rows as $row) {
                $curr_id = $row['loc_id'];
                foreach ($rows_to_update as $update_row) {
                    if (md5($curr_id) == $update_row) {

//select row to update
                        $update = Locations::model()->findByPk($curr_id);
                        $insert = new LocationHoursTracking();

                        $username = Yii::app()->user->getState('username');

                        $insert->lht_username = $username;
                        $insert->lht_loc_name = $row['loc_name'];

                        foreach ($days_of_week as $day) {

//get short representation of day
                            $short_day = substr($day, 0, 3);

//set string EX: 'mon_open_hrs'
                            $open_hrs = $short_day . '_open_hrs';
                            $closed_hrs = $short_day . '_closed_hrs';

//convert table value to date()
                            $open_hours_string = strtotime($_POST[$row['loc_name'] . '|' . $open_hrs]);
                            $open_hours_to_insert = date('H:i:s', $open_hours_string);

//convert table value to date()
                            $closed_hours_string = strtotime($_POST[$row['loc_name'] . '|' . $closed_hrs]);
                            $closed_hours_to_insert = date('H:i:s', $closed_hours_string);

//set string for column, for example 'loc_mon_open_hrs'
                            $update_open_col = 'loc_' . $open_hrs;
                            $update_closed_col = 'loc_' . $closed_hrs;
                            $insert_open_col = 'lht_' . $open_hrs;
                            $insert_closed_col = 'lht_' . $closed_hrs;
//update column
                            $update->$update_open_col = $open_hours_to_insert;
                            $update->$update_closed_col = $closed_hours_to_insert;

//update rows in tracking table
                            $insert->$insert_open_col = $open_hours_to_insert;
                            $insert->$insert_closed_col = $closed_hours_to_insert;
                        }
//get timestamp and format for db
                        $now = $createdate = date('Y-m-d H:i:s');
                        $insert->lht_timestamp = $now;

//save insert
                        $insert->save();
//save update
                        $update->save();
                        Yii::app()->user->setFlash('time_update', 'Shop times have been updated.');
                    }
                }
            }
        }//end of isset($_post) if statement
        else {
            Yii::app()->user->setFlash('error', 'Access Denied.');
            $this->redirect(Yii::app()->user->returnUrl);
        }
    }

    /**
     * 
     */
    public function actionAddLocation() {
        if (Yii::app()->request->isPostRequest) {
            $vars = $_POST['AddLocation'];
            print_r($vars);
            $loc_name = $vars['locationname'];
            $ip = $vars['ipaddress'];
            $comp_name = $vars['computername'];

            $phys_location = new Locations();
            $phys_location->loc_name = $loc_name;
            $phys_location->save();

            $new_loc_id = Locations::model()->findByAttributes(array('loc_name' => $loc_name), 'loc_id');

            $ip_location = new Ips();
            $ip_location->ip_address = $ip;
            $ip_location->ip_compname = $comp_name;
            $ip_location->ip_loc_id = $new_loc_id['loc_id'];
            $ip_location->save();

            Yii::app()->user->setFlash('success', 'Location added.');
        } else {
            Yii::app()->user->setFlash('error', 'Access Denied.');
            $this->redirect(Yii::app()->user->returnUrl);
        }
    }

    /**
     * 
     */
    public function actionGetLocationInfo() {
        if (Yii::app()->request->isPostRequest) {
            //TODO fill in location info code to auto-populate update location fields
        } else {
            Yii::app()->user->setFlash('error', 'Access Denied.');
            $this->redirect(Yii::app()->user->returnUrl);
        }
    }

    /**
     * 
     */
    public function actionUpdateLocation() {
        if (Yii::app()->request->isPostRequest) {
            $vars = $_POST['UpdateLocation'];
            $loc_name = $vars['locationnameupdate'];
            $ip = $vars['ipaddressupdate'];
            $comp_name = $vars['computernameupdate'];

            $phys_location = Locations::model()->findByAttributes(array('loc_name' => $loc_name), 'loc_id');
            print_r($loc_name);
            /* $phys_location->loc_name = $loc_name;
              $phys_location->save();

              $new_loc_id = Locations::model()->findByAttributes(array('loc_name' => $loc_name), 'loc_id');

              $ip_location = new Ips();
              $ip_location->ip_address = $ip;
              $ip_location->ip_compname = $comp_name;
              $ip_location->ip_loc_id = $new_loc_id['loc_id'];
              $ip_location->save(); */

            Yii::app()->user->setFlash('success', 'Location added.');
        } else {
            Yii::app()->user->setFlash('error', 'Access Denied.');
            $this->redirect(Yii::app()->user->returnUrl);
        }
    }

    /**
     * 
     */
    public function actionDeleteLocation() {
        if (Yii::app()->request->isPostRequest) {
            $vars = ($_POST['DeleteLocation']);
            $loc_id = $vars['locationname'];
            $locations = Locations::model()->findAll();
            foreach ($locations as $location) {
                if (md5($location['loc_id']) == $loc_id) {
                    Locations::model()->deleteByPk($location['loc_id']);
                }
            }
            Yii::app()->user->setFlash('success', 'Location deleted.');
        } else {
            Yii::app()->user->setFlash('error', 'Access Denied.');
            $this->redirect(Yii::app()->user->returnUrl);
        }
    }

    /**
     * 
     */
    public function actionHolidayUpdate() {
        if (Yii::app()->request->isPostRequest) {
            //get array of holiday ids for each holiday selected
            $holiday_ids = $_POST['holidays'];
            //get array of locations for each holiday
            $location_ids = $_POST['locations'];
            //get all records from the holidays table
            $hol_query = Holidays::model()->findAll();
            //get all records from the locations table
            $loc_query = Locations::model()->findAll();

            //remove all shop holidays
            ShopHolidays::model()->deleteAll();

            /**
             * counter for location ids since each holiday may have more than 1
             */
            $counter = 0;
            //select each holiday id from the table: id
            foreach ($holiday_ids as $holiday) {
                //select each holiday from the database
                foreach ($hol_query as $db_hol) {
                    //compare the id from the table with that from the database
                    if (md5($db_hol['hol_id']) == $holiday) {
                        
                        //make sure there is a location in the counter
                        if($location_ids[$counter]){
                            //for each location selected at each holiday: l
                            foreach ($location_ids[$counter] as $loc) {
                                //for each location in the database: db_l
                                foreach ($loc_query as $db_loc) {
                                    //compare l with the db_l and if it's a match, update the database
                                    if (md5($db_loc['loc_id']) == $loc) {
                                        $sh = new ShopHolidays; //create new record
                                        $sh->hol_id = $db_hol['hol_id']; //add holiday id
                                        $sh->loc_id = $db_loc['loc_id']; //add location id
                                        $sh->save(); //save the state and insert the new row
                                    }//end if
                                }//end foreach
                            }//end foreach
                        }
                    }//end if
                }//end foreach
                $counter++; //increment counter
            }//end of opening foreach
            //set flash message
            Yii::app()->user->setFlash('holiday_update', 'The holiday table has been updated.');

            //end of isset($_post) if statement
        } else {
            Yii::app()->user->setFlash('error', 'Access Denied.');
            $this->redirect(Yii::app()->user->returnUrl);
        }
    }

    /**
     * 
     */
    public function actionAddHoliday() {
        if (Yii::app()->request->isPostRequest) {
            
        } else {
            Yii::app()->user->setFlash('error', 'Access Denied.');
            $this->redirect(Yii::app()->user->returnUrl);
        }
    }

    /**
     * 
     */
    public function actionDeleteHoliday() {
        if (Yii::app()->request->isPostRequest) {
            
        } else {
            Yii::app()->user->setFlash('error', 'Access Denied.');
            $this->redirect(Yii::app()->user->returnUrl);
        }
    }

// Uncomment the following methods and override them if needed
    /*
      public function filters()
      {
      // return the filter configuration for this controller, e.g.:
      return array(
      'inlineFilterName',
      array(
      'class'=>'path.to.FilterClass',
      'propertyName'=>'propertyValue',
      ),
      );
      }

      public function actions()
      {
      // return external action classes, e.g.:
      return array(
      'action1'=>'path.to.ActionClass',
      'action2'=>array(
      'class'=>'path.to.AnotherActionClass',
      'propertyName'=>'propertyValue',
      ),
      );
      }
     */
}