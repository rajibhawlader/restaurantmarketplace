<?php
/**
 * install/update.php - Update the database from a previous version of xrms
 *
 * When coding this file, it is important that everything only happen after
 * a test.  This file must be non-destructive and only make the changes that
 * must be made.
 *
 * @author Beth Macknik
 * $Id: update.php,v 1.48 2005/01/11 17:08:39 maulani Exp $
 */

// where do we include from
require_once('../include-locations.inc');

// get required common files
require_once($include_directory . 'utils-interface.php');
require_once($include_directory . 'utils-misc.php');
require_once($include_directory . 'adodb/adodb.inc.php');
require_once($include_directory . 'adodb-params.php');

// vars.php sets all of the installation-specific variables
require_once($include_directory . 'vars.php');

$session_user_id = session_check( 'Admin' );

// make a database connection
$con = &adonewconnection($xrms_db_dbtype);
$con->connect($xrms_db_server, $xrms_db_username, $xrms_db_password, $xrms_db_dbname);

$msg = '';

// Make sure that there is an admin record in roles
$sql = "select count(*) as recCount from roles where role_short_name='Admin'";
$rst = $con->execute($sql);
$recCount = $rst->fields['recCount'];
if ($recCount == 0) {
    $msg .= 'Added an Admin role.<BR><BR>';
    $sql = "SELECT * FROM roles WHERE 1 = 2"; //select empty record as placeholder
    $rst = $con->execute($sql);

    $rec = array();
    $rec['role_short_name'] = 'Admin';
    $rec['role_pretty_name'] = 'Admin';
    $rec['role_pretty_plural'] = 'Admin';
    $rec['role_display_html'] = 'Admin';

    $ins = $con->GetInsertSQL($rst, $rec, get_magic_quotes_gpc());
    $con->execute($ins);
}


// Make sure that there is a user with admin privileges
$sql = "select role_id from roles where role_short_name='Admin'";
$rst = $con->execute($sql);
$role_id = $rst->fields['role_id'];
$sql = "select count(*) as recCount from users where role_id=$role_id";
$rst = $con->execute($sql);
$recCount = $rst->fields['recCount'];
if ($recCount == 0) {
    // none of the users have Admin access, so give the user with the lowest user_id Admin access
    $sql = "select min(user_id) as user_id from users";
    $rst = $con->execute($sql);
    if (!$rst) {
        // Oops.  The real problem is that we have no users.  Create one with admin access
        $msg .= 'Add user1 with Admin access.<BR><BR>';
        $sql = "SELECT * FROM roles WHERE 1 = 2"; //select empty record as placeholder
        $rst = $con->execute($sql);

        $rec = array();
        $rec['role_id'] = $role_id;
        $rec['username'] = 'user1';
        $rec['password'] = '24c9e15e52afc47c225b757e7bee1f9d';
        $rec['last_name'] = 'One';
        $rec['first_names'] = 'User';
        $rec['email'] = 'user1@somecompany.com';
        $rec['language'] = 'english';

        $ins = $con->GetInsertSQL($rst, $rec, get_magic_quotes_gpc());
        $con->execute($ins);
    } else {
        $user_id = $rst->fields['user_id'];
        $msg .= "Give Admin access to $user_id.<BR><BR>";
        $sql = "SELECT * FROM users WHERE user_id = $user_id";
        $rst = $con->execute($sql);

        $rec = array();
        $rec['role_id'] = $role_id;

        $upd = $con->GetUpdateSQL($rst, $rec, false, get_magic_quotes_gpc());
        $con->execute($upd);
    }
}

//make sure that there is a case_priority_score_adjustment column
//should put a test here, but alter table is non-destructive
$sql = "alter table case_priorities add case_priority_score_adjustment int not null after case_priority_display_html";
$rst = $con->execute($sql);
// end case_priority_display_html

//make sure that there is a recent_action column
$sql = "alter table recent_items add recent_action varchar(100) not null after on_what_table";
$rst = $con->execute($sql);
// end recent_action

//make sure that there is a status_open_indicator column in campaigns
//should put a test here, but alter table is non-destructive
//This is used for reports/open-items.php and reports/completed-items.php reports
//Similiar to opportunity_statuses, 'o' means open, anything else means "completed" for the completed-item report
$sql = "alter table campaign_statuses add status_open_indicator char(1) not null default \"o\" after campaign_status_id";
$rst = $con->execute($sql);
// end

//set "CLOSED" campaign status_open_indicator to "c"
//should put a test here, but alter table is non-destructive
//This is used for reports/open-items.php and reports/completed-items.php reports
//This sets the default "Closed" campaign status with a status_open_indicator of "c" for "Closed"
$sql = "SELECT * FROM campaign_statuses WHERE campaign_status_short_name = 'CLO'";
$rst = $con->execute($sql);

$rec = array();
$rec['status_open_indicator'] = 'c';

$upd = $con->GetUpdateSQL($rst, $rec, false, get_magic_quotes_gpc());
$con->execute($upd);
// end

// add sort order to activity types
// should put a test here, but alter table is non-destructive
// adding a test here would allow us to use UPDATE to set rational default values
$sql = "ALTER TABLE activity_types ADD sort_order TINYINT NOT NULL DEFAULT '1' AFTER activity_type_record_status";
$rst = $con->execute($sql);

// add sort order to opportunity statuses
// adding a test here would allow us to use UPDATE to set rational default values
$sql = "ALTER TABLE opportunity_statuses ADD sort_order TINYINT NOT NULL DEFAULT '1' AFTER opportunity_status_id";
$rst = $con->execute($sql);

//make sure that there is a status_open_indicator column in opportunity statuses
//should put a test here, but alter table is non-destructive
//This is used for reports/open-items.php and reports/completed-items.php reports
//'o' means open, anything else means "completed" for the completed-item report
$sql = "alter table opportunity_statuses add status_open_indicator char(1) not null default 'o' after sort_order";
$rst = $con->execute($sql);
// end

//set "CLOSED" opportunity status_open_indicator to "c"
//This is used for reports/open-items.php and reports/completed-items.php reports
//This sets the default "Closed" campaign status with a status_open_indicator of "c" for "Closed"
$sql = "SELECT * FROM opportunity_statuses WHERE opportunity_status_short_name like 'CL%'";
$rst = $con->execute($sql);

$rec = array();
$rec['status_open_indicator'] = 'c';

$upd = $con->GetUpdateSQL($rst, $rec, false, get_magic_quotes_gpc());
$con->execute($upd);
// end

//make sure that there is a status_open_indicator column in case statuses
//should put a test here, but alter table is non-destructive
//This is used for reports/open-items.php and reports/completed-items.php reports
//'o' means open, anything else means "completed" for the completed-item report
$sql = "alter table case_statuses add status_open_indicator char(1) not null default 'o' after case_status_id";
$rst = $con->execute($sql);
//add a long description for consistency
$sql = "ALTER TABLE `case_statuses` ADD `case_status_long_desc` VARCHAR( 200 ) NOT NULL";
$rst = $con->execute($sql);
//link case statuses to a case type for workflow
$sql = "ALTER TABLE `case_statuses` ADD `case_type_id` INT DEFAULT '1' NOT NULL AFTER `case_status_display_html`";
$rst = $con->execute($sql);
//add an index
$sql = "ALTER TABLE `case_statuses` ADD INDEX ( `case_type_id` ) ";
$rst = $con->execute($sql);
// end

//set "CLOSED" case status_open_indicator to "c"
//This is used for reports/open-items.php and reports/completed-items.php reports
//This sets the default "Closed" campaign status with a status_open_indicator of "c" for "Closed"
$sql = "SELECT * FROM case_statuses WHERE case_status_short_name='CLO'";
$rst = $con->execute($sql);

$rec = array();
$rec['status_open_indicator'] = 'c';

$upd = $con->GetUpdateSQL($rst, $rec, false, get_magic_quotes_gpc());
$con->execute($upd);
// end

//add sort order to case_statuses
//should put a test here, but alter table is non-destructive
$sql = "ALTER TABLE case_statuses ADD sort_order TINYINT NOT
NULL DEFAULT '1' AFTER case_status_id";
$rst = $con->execute($sql);

//add phone format to countries
$sql = "ALTER TABLE countries ADD phone_format VARCHAR(25) NOT NULL DEFAULT '' AFTER country_record_status";
$rst = $con->execute($sql);

//add currency code to countries
$sql = "ALTER TABLE `countries` ADD `currency_code` VARCHAR( 3 )";
$rst = $con->execute($sql);

//add user contact id to users
$sql = "ALTER TABLE users ADD user_contact_id int(11) NOT NULL DEFAULT 0 AFTER user_id";
$rst = $con->execute($sql);

//make sure that there is connection detail columns in the audit_items table
//these are done separately in case one column already exists
//should put a test here, but alter table is non-destructive
//These items are used for "Connection Details" in reports/audit-items.php
//remote_addr is the client's IP address. varchar(40) should be big enough for IPv6 addresses
$sql = "alter table audit_items add remote_addr varchar(40) after audit_item_timestamp";
$rst = $con->execute($sql);
//remote_port is the client's requesting port.r
// This is useful for comparing to network
//packet dumps and tracing connections through firewalls.
$sql = "alter table audit_items add remote_port int(6) after remote_addr";
$rst = $con->execute($sql);
//session_id stores _COOKIE["PHPSESSID"], used for tracking a user's session
$sql = "alter table audit_items add session_id varchar(50) after remote_port";
$rst = $con->execute($sql);
// end

//make sure that there is a status_open_indicator column in campaigns
//should put a test here, but alter table is non-destructive
$sql = "alter table campaign_statuses add status_open_indicator char(1) not null default 'o' after campaign_status_id";
$rst = $con->execute($sql);
// end case_priority_display_html

//make sure that the contacts table has a division_id filed, since folks with a 12Jan install won't have it
//should put a test here, but alter table is non-destructive
$sql = "alter table contacts add division_id int not null after company_id";
$rst = $con->execute($sql);
//end division_id update

// Fix problem introduced by buggy Mar 19, 2004 install code
// This will modify the initial data appropriately
$sql = "SELECT * FROM address_format_strings WHERE address_format_string != '" .
'$lines<br>$city, $province $postal_code<br>$country' . "' AND address_format_string_id=1";
$rst = $con->execute($sql);

$rec = array();
$rec['address_format_string'] = '$lines<br>$city, $province $postal_code<br>$country';

$upd = $con->GetUpdateSQL($rst, $rec, false, get_magic_quotes_gpc());
$con->execute($upd);

// Add indexes so data integrity checks take a reasonable about of time
$sql = "create index company_id on addresses (company_id)";
$rst = $con->execute($sql);
$sql = "create index company_id on contacts (company_id)";
$rst = $con->execute($sql);
$sql = "create index company_record_status on companies (company_record_status)";
$rst = $con->execute($sql);
$sql = "create index contact_record_status on contacts (contact_record_status)";
$rst = $con->execute($sql);
$sql = "create index address_record_status on addresses (address_record_status)";
$rst = $con->execute($sql);

// Make sure that the database has the correct legal_name column
$sql = "alter table companies change company_legal_name legal_name varchar( 100 ) not null";
$rst = $con->execute($sql);

// Add the system_parameters table
$sql = 'create table system_parameters (';
$sql .= 'param_id       varchar(40) not null unique,';
$sql .= 'string_val     varchar(100),';
$sql .= 'int_val        int,';
$sql .= 'float_val      float,';
$sql .= 'datetime_val   datetime';
$sql .= ')';
$rst = $con->execute($sql);

// Make sure that there is an default GST offset in system_parameters
$sql = "select count(*) as recCount from system_parameters where param_id='Default GST Offset'";
$rst = $con->execute($sql);
$recCount = $rst->fields['recCount'];
if ($recCount == 0) {
    $msg .= 'Added a default GST offset.<BR><BR>';
    $sql = "SELECT * FROM system_parameters WHERE 1 = 2"; //select empty record as placeholder
    $rst = $con->execute($sql);

    $rec = array();
    $rec['param_id'] = 'Default GST Offset';
    $rec['int_val'] = -5;

    $ins = $con->GetInsertSQL($rst, $rec, get_magic_quotes_gpc());
    $con->execute($ins);
}

// Make sure that there is an Audit Level in system_parameters
$sql = "select count(*) as recCount from system_parameters where param_id='Audit Level'";
$rst = $con->execute($sql);
$recCount = $rst->fields['recCount'];
if ($recCount == 0) {
    $msg .= _("Added an Audit Level.").'<BR><BR>';
    $sql = "SELECT * FROM system_parameters WHERE 1 = 2"; //select empty record as placeholder
    $rst = $con->execute($sql);

    $rec = array();
    $rec['param_id'] = 'Audit Level';
    $rec['int_val'] = 4;

    $ins = $con->GetInsertSQL($rst, $rec, get_magic_quotes_gpc());
    $con->execute($ins);
}

// Make sure that there is an Activities Default Behavior in system_parameters
$sql = "select count(*) as recCount from system_parameters where param_id='Activities Default Behavior'";
$rst = $con->execute($sql);
$recCount = $rst->fields['recCount'];
if ($recCount == 0) {
    $msg .= _("Added an Activities Default Behavior system parameter.").'<BR><BR>';

    $rec = array();
    $rec['param_id'] = 'Activities Default Behavior';
    $rec['string_val'] = $activities_default_behavior;

    $tbl = 'system_parameters';
    $ins = $con->GetInsertSQL($tbl, $rec, get_magic_quotes_gpc());
    $con->execute($ins);
}

// Make sure that there is an LDAP Version in system_parameters
$sql = "select count(*) as recCount from system_parameters where param_id='LDAP Version'";
$rst = $con->execute($sql);
$recCount = $rst->fields['recCount'];
if ($recCount == 0) {
    $msg .= _("Added an LDAP Version system parameter.").'<BR><BR>';

    $rec = array();
    $rec['param_id'] = 'LDAP Version';
    $rec['int_val'] = 3;

    $tbl = 'system_parameters';
    $ins = $con->GetInsertSQL($tbl, $rec, get_magic_quotes_gpc());
    $con->execute($ins);
}

//add statuses to activities
$sql = "alter table activities add on_what_status int not null default 0 after on_what_id";
$rst = $con->execute($sql);

//make activity_description a nullable field
$sql="ALTER TABLE `activities` CHANGE `activity_description` `activity_description` TEXT";
$con->execute($sql);

//create the activity_templates table if we need it
$sql = "create table activity_templates (
                activity_template_id    int not null primary key auto_increment,
                role_id                 int not null default 0,
                activity_type_id        int not null default 0,
                on_what_table           varchar(100) not null default '',
                on_what_id              int not null default 0,
                activity_title          varchar(100) not null default '',
                activity_description    text not null default '',
                duration                varchar(20) default 1 not null,
                sort_order              tinyint not null default 1,
                activity_template_record_status         char not null default 'a'
                )";
        //execute
        $rst = $con->execute($sql);

// create the relationship_types table if we need it
$sql ="CREATE TABLE relationship_types (
                relationship_type_id int(11) NOT NULL auto_increment,
                relationship_name varchar(48) NOT NULL default '',
                from_what_table varchar(24) NOT NULL default '',
                to_what_table varchar(24) NOT NULL default '',
                from_what_text varchar(32) NOT NULL default '',
                to_what_text varchar(32) NOT NULL default '',
                relationship_status char(1) NOT NULL default 'a',
                pre_formatting varchar(25) default NULL,
                post_formatting varchar(25) default NULL,
                PRIMARY KEY  (relationship_type_id)
                )";
        //execute
        $rst = $con->execute($sql);

//relationship status does not exist in old tables, add it
$sql ="ALTER TABLE relationship_types 
	ADD relationship_status char(1) NOT NULL default 'a',
	ADD pre_formatting varchar(25) default NULL,
	ADD post_formatting varchar(25) default NULL
	";
	$rst = $con->execute($sql);

// create the saved_actions table if we need it
$sql = "CREATE TABLE saved_actions (
                saved_id int(11) NOT NULL auto_increment,
                saved_title varchar(100) NOT NULL default '',
                user_id int(11) NOT NULL default '0',
                on_what_table varchar(100) NOT NULL default '',
                saved_action varchar(100) NOT NULL default '',
                group_item int(1) NOT NULL default '0',
                saved_data text NOT NULL,
                saved_status char(1) NOT NULL default 'a',
                PRIMARY KEY  (saved_id),
                KEY user_id (user_id),
                KEY group_item (group_item)
                )";
        //execute
        $rst = $con->execute($sql);

if (confirm_no_records($con, 'relationship_types')) {
    $sql = "INSERT INTO relationship_types
            (relationship_name,from_what_table,to_what_table,from_what_text,to_what_text,relationship_status,pre_formatting,post_formatting)
            VALUES
            ('company relationships','companies','companies','Acquired','Acquired by','a',NULL,NULL)";
    $rst = $con->execute($sql);
    $sql = "INSERT INTO relationship_types
            (relationship_type_id,relationship_name,from_what_table,to_what_table,from_what_text,to_what_text,relationship_status,pre_formatting,post_formatting)
            VALUES
            ('company relationships','companies','companies','Retains Consultant','Consultant for','a',NULL,NULL)";
    $rst = $con->execute($sql);
    $sql = "INSERT INTO relationship_types
            (relationship_name,from_what_table,to_what_table,from_what_text,to_what_text,relationship_status,pre_formatting,post_formatting)
            VALUES
            ('company relationships','companies','companies','Manufactures for','Uses Manufacturer','a',NULL,NULL)";
    $rst = $con->execute($sql);
    $sql = "INSERT INTO relationship_types
            (relationship_name,from_what_table,to_what_table,from_what_text,to_what_text,relationship_status,pre_formatting,post_formatting)
            VALUES
            ('company relationships','companies','companies','Parent Company of','Subsidiary of','a',NULL,NULL)";
    $rst = $con->execute($sql);
    $sql = "INSERT INTO relationship_types
            (relationship_name,from_what_table,to_what_table,from_what_text,to_what_text,relationship_status,pre_formatting,post_formatting)
            VALUES
            ('company relationships','companies','companies','Uses Supplier','Supplier for','a',NULL,NULL)";
    $rst = $con->execute($sql);
    $sql = "INSERT INTO relationship_types
            (relationship_name,from_what_table,to_what_table,from_what_text,to_what_text,relationship_status,pre_formatting,post_formatting)
            VALUES
            ('company link','contacts','companies','Owns','Owned By','a','<b>','</b>')";
    $rst = $con->execute($sql);
    $sql = "INSERT INTO relationship_types
            (relationship_name,from_what_table,to_what_table,from_what_text,to_what_text,relationship_status,pre_formatting,post_formatting)
            VALUES
            ('company link','contacts','companies','Manages','Managed By','a',NULL,NULL)";
    $rst = $con->execute($sql);
    $sql = "INSERT INTO relationship_types
            (relationship_name,from_what_table,to_what_table,from_what_text,to_what_text,relationship_status,pre_formatting,post_formatting)
            VALUES
            ('company link','contacts','companies','Consultant for','Retains Consultant','a',NULL,NULL)";
    $rst = $con->execute($sql);
}

// create the relationships table if we need it
$sql ="CREATE TABLE relationships (
        relationship_id int(11) NOT NULL auto_increment,
        from_what_id int(11) NOT NULL default '0',
        to_what_id int(11) NOT NULL default '0',
        relationship_type_id int(11) NOT NULL default '0',
        established_at datetime default NULL,
        ended_on datetime default NULL,
        relationship_status char(1) NOT NULL default 'a',
        PRIMARY KEY  (relationship_id),
        KEY from_what_id (from_what_id),
        KEY to_what_id (to_what_id)
        )";
        //execute
        $rst = $con->execute($sql);

// now convert existing relationships
if (confirm_no_records($con, 'relationships')) {
    $sql = "select company_from_id, relationship_type, company_to_id, established_at
            from company_relationship
            order by established_at desc";
    $rst = $con->execute($sql);
    if ($rst) {
        while (!$rst->EOF) {
            $direction = '';

            $to_what_id     = $rst->fields['company_to_id'];
            $from_what_id   = $rst->fields['company_from_id'];
            $established_at =  $con->qstr($rst->fields['established_at'], get_magic_quotes_gpc());

            $relationship_type_id = 0;

            $old_type = $con->qstr($rst->fields['relationship_type'], get_magic_quotes_gpc());
            $match_sql = "select relationship_type_id from relationship_types
                              where from_what_table = 'companies' and to_what_table = 'companies'
                              and to_what_text LIKE $old_type ";

            $relationship_type_id = (int) $con->GetOne($match_sql);
            if ((int) $relationship_type_id == (int) 0) {
                $match_sql = "select relationship_type_id from relationship_types
                              where from_what_table = 'companies' and to_what_table = 'companies'
                              and from_what_text LIKE $old_type ";

                $relationship_type_id = (int) $con->GetOne($match_sql);
                $direction = 'from';
            }
            if ($relationship_type_id) {
                //now insert the row in the new table
                if ($direction=='from'){
                    $new_to_id   = $to_what_id;
                    $new_from_id = $from_what_id;
                } else {
                    $new_from_id = $to_what_id;
                    $new_to_id   = $from_what_id;
                }
                $sql = "insert into relationships
                (from_what_id, to_what_id, relationship_type_id, established_at)
                values (" . $new_from_id . ", " . $new_to_id . ", " . $relationship_type_id . ", $established_at)";
                $ins_rst=$con->execute($sql);
                //skip the next row to avoid dups
                $rst->movenext();
            }
            $rst->movenext();
        }
    }
} //end convert old relationships

// Make sure that the additional address format strings are added
$sql = "select count(*) as recCount from address_format_strings";
$rst = $con->execute($sql);
$recCount = $rst->fields['recCount'];
if ($recCount <16) {
    $msg .= 'Added additional address format strings.<BR><BR>';

    define('ADODB_FORCE_NULLS', 0);
    $sql = "SELECT * FROM address_format_strings WHERE 1 = 2"; //select empty record as placeholder
    $rst = $con->execute($sql);

    $rec = array();
    $rec['address_format_string_id'] = 0;
    $rec['address_format_string'] = '';

    $ins = $con->GetInsertSQL($rst, $rec, get_magic_quotes_gpc());
    $con->execute($ins);
    $sql = "SELECT * FROM address_format_strings WHERE 1 = 2"; //select empty record as placeholder
    $rst = $con->execute($sql);

    $rec = array();
    $rec['address_format_string_id'] = 2;
    $rec['address_format_string'] = '$lines<br>$postal_code $city<br>$province<br>$country';

    $ins = $con->GetInsertSQL($rst, $rec, get_magic_quotes_gpc());
    $con->execute($ins);
    $sql = "SELECT * FROM address_format_strings WHERE 1 = 2"; //select empty record as placeholder
    $rst = $con->execute($sql);

    $rec = array();
    $rec['address_format_string_id'] = 3;
    $rec['address_format_string'] = '$lines<br>$postal_code $city $province<br>$country';

    $ins = $con->GetInsertSQL($rst, $rec, get_magic_quotes_gpc());
    $con->execute($ins);

    $sql = "SELECT * FROM address_format_strings WHERE 1 = 2"; //select empty record as placeholder
    $rst = $con->execute($sql);

    $rec = array();
    $rec['address_format_string_id'] = 4;
    $rec['address_format_string'] = '$lines<br>$city $province $postal_code<br>$country';

    $ins = $con->GetInsertSQL($rst, $rec, get_magic_quotes_gpc());
    $con->execute($ins);

    $sql = "SELECT * FROM address_format_strings WHERE 1 = 2"; //select empty record as placeholder
    $rst = $con->execute($sql);

    $rec = array();
    $rec['address_format_string_id'] = 5;
    $rec['address_format_string'] = '$lines<br>$postal_code $province<br>$city<br>$country';

    $ins = $con->GetInsertSQL($rst, $rec, get_magic_quotes_gpc());
    $con->execute($ins);

    $sql = "SELECT * FROM address_format_strings WHERE 1 = 2"; //select empty record as placeholder
    $rst = $con->execute($sql);

    $rec = array();
    $rec['address_format_string_id'] = 6;
    $rec['address_format_string'] = '$lines<br>$postal_code $city<br>$country';

    $ins = $con->GetInsertSQL($rst, $rec, get_magic_quotes_gpc());
    $con->execute($ins);

    $sql = "SELECT * FROM address_format_strings WHERE 1 = 2"; //select empty record as placeholder
    $rst = $con->execute($sql);

    $rec = array();
    $rec['address_format_string_id'] = 7;
    $rec['address_format_string'] = '$postal_code $city<br>$lines<br>$country';

    $ins = $con->GetInsertSQL($rst, $rec, get_magic_quotes_gpc());
    $con->execute($ins);

    $sql = "SELECT * FROM address_format_strings WHERE 1 = 2"; //select empty record as placeholder
    $rst = $con->execute($sql);

    $rec = array();
    $rec['address_format_string_id'] = 8;
    $rec['address_format_string'] = '$lines<br>$province<br>$city $postal_code<br>$country';

    $ins = $con->GetInsertSQL($rst, $rec, get_magic_quotes_gpc());
    $con->execute($ins);

    $sql = "SELECT * FROM address_format_strings WHERE 1 = 2"; //select empty record as placeholder
    $rst = $con->execute($sql);

    $rec = array();
    $rec['address_format_string_id'] = 9;
    $rec['address_format_string'] = '$lines<br>$city<br>$province $postal_code<br>$country';

    $ins = $con->GetInsertSQL($rst, $rec, get_magic_quotes_gpc());
    $con->execute($ins);

    $sql = "SELECT * FROM address_format_strings WHERE 1 = 2"; //select empty record as placeholder
    $rst = $con->execute($sql);

    $rec = array();
    $rec['address_format_string_id'] = 10;
    $rec['address_format_string'] = '$postal_code<br>$province $city<br>$lines<br>$country';

    $ins = $con->GetInsertSQL($rst, $rec, get_magic_quotes_gpc());
    $con->execute($ins);

    $sql = "SELECT * FROM address_format_strings WHERE 1 = 2"; //select empty record as placeholder
    $rst = $con->execute($sql);

    $rec = array();
    $rec['address_format_string_id'] = 11;
    $rec['address_format_string'] = '$lines<br>$city $province<br>$postal_code<br>$country';

    $ins = $con->GetInsertSQL($rst, $rec, get_magic_quotes_gpc());
    $con->execute($ins);

    $sql = "SELECT * FROM address_format_strings WHERE 1 = 2"; //select empty record as placeholder
    $rst = $con->execute($sql);

    $rec = array();
    $rec['address_format_string_id'] = 12;
    $rec['address_format_string'] = '$country $postal_code<br>$province $city<br>$lines';

    $ins = $con->GetInsertSQL($rst, $rec, get_magic_quotes_gpc());
    $con->execute($ins);

    $sql = "SELECT * FROM address_format_strings WHERE 1 = 2"; //select empty record as placeholder
    $rst = $con->execute($sql);

    $rec = array();
    $rec['address_format_string_id'] = 13;
    $rec['address_format_string'] = '$lines<br>$city<br>$province<br>$postal_code<br>$country';

    $ins = $con->GetInsertSQL($rst, $rec, get_magic_quotes_gpc());
    $con->execute($ins);

    $sql = "SELECT * FROM address_format_strings WHERE 1 = 2"; //select empty record as placeholder
    $rst = $con->execute($sql);

    $rec = array();
    $rec['address_format_string_id'] = 14;
    $rec['address_format_string'] = '$lines<br>$city $postal_code<br>$country';

    $ins = $con->GetInsertSQL($rst, $rec, get_magic_quotes_gpc());
    $con->execute($ins);

    $sql = "SELECT * FROM address_format_strings WHERE 1 = 2"; //select empty record as placeholder
    $rst = $con->execute($sql);

    $rec = array();
    $rec['address_format_string_id'] = 15;
    $rec['address_format_string'] = '$lines<br>$city, $province $postal_code<br>$country';

    $ins = $con->GetInsertSQL($rst, $rec, get_magic_quotes_gpc());
    $con->execute($ins);

    $sql = "SELECT * FROM countries WHERE country_name in ('Argentina', 'Kuwait', 'Oman', 'Poland')";
    $rst = $con->execute($sql);

    $rec = array();
    $rec['address_format_string_id'] = 2;

    $upd = $con->GetUpdateSQL($rst, $rec, false, get_magic_quotes_gpc());
    $con->execute($upd);

    $sql = "SELECT * FROM countries WHERE country_name in ('Brazil', 'China', 'Italy', 'Mexico', 'Portugal', 'Spain')";
    $rst = $con->execute($sql);

    $rec = array();
    $rec['address_format_string_id'] = 3;

    $upd = $con->GetUpdateSQL($rst, $rec, false, get_magic_quotes_gpc());
    $con->execute($upd);

    $sql = "SELECT * FROM countries WHERE country_name in ('Australia', 'Canada', 'Hong Kong Special Administrative Region of China', 'Ireland', 'Taiwan')";
    $rst = $con->execute($sql);

    $rec = array();
    $rec['address_format_string_id'] = 4;

    $upd = $con->GetUpdateSQL($rst, $rec, false, get_magic_quotes_gpc());
    $con->execute($upd);

    $sql = "SELECT * FROM countries WHERE country_name in ('Denmark')";
    $rst = $con->execute($sql);

    $rec = array();
    $rec['address_format_string_id'] = 5;

    $upd = $con->GetUpdateSQL($rst, $rec, false, get_magic_quotes_gpc());
    $con->execute($upd);

    $sql = "SELECT * FROM countries WHERE country_name in ('Austria', 'Bahrain', 'Belgium', 'Bosnia and Herzegovina', 'Bulgaria', 'Croatia', 'Czech Republic', 'Egypt', 'Finland', 'France', 'France, metropolitan', 'Germany', 'Greece', 'Greenland', 'Iceland', 'Israel', 'Jordan', 'Lebanon', 'Luxembourg', 'Netherlands', 'Norway', 'Qatar', 'Romania', 'Saudi Arabia', 'Singapore', 'Slovakia', 'Slovenia', 'Sweden', 'Switzerland', 'Syrian Arab Republic', 'Turkey', 'Yemen')";
    $rst = $con->execute($sql);

    $rec = array();
    $rec['address_format_string_id'] = 6;

    $upd = $con->GetUpdateSQL($rst, $rec, false, get_magic_quotes_gpc());
    $con->execute($upd);

    $sql = "SELECT * FROM countries WHERE country_name in ('Hungary')";
    $rst = $con->execute($sql);

    $rec = array();
    $rec['address_format_string_id'] = 7;

    $upd = $con->GetUpdateSQL($rst, $rec, false, get_magic_quotes_gpc());
    $con->execute($upd);

    $sql = "SELECT * FROM countries WHERE country_name in ('India', 'New Zealand')";
    $rst = $con->execute($sql);

    $rec = array();
    $rec['address_format_string_id'] = 8;

    $upd = $con->GetUpdateSQL($rst, $rec, false, get_magic_quotes_gpc());
    $con->execute($upd);

    $sql = "SELECT * FROM countries WHERE country_name in ('Indonesia')";
    $rst = $con->execute($sql);

    $rec = array();
    $rec['address_format_string_id'] = 9;

    $upd = $con->GetUpdateSQL($rst, $rec, false, get_magic_quotes_gpc());
    $con->execute($upd);

    $sql = "SELECT * FROM countries WHERE country_name in ('Japan')";
    $rst = $con->execute($sql);

    $rec = array();
    $rec['address_format_string_id'] = 10;

    $upd = $con->GetUpdateSQL($rst, $rec, false, get_magic_quotes_gpc());
    $con->execute($upd);

    $sql = "SELECT * FROM countries WHERE country_name in ('Republic of Korea', 'Ukraine')";
    $rst = $con->execute($sql);

    $rec = array();
    $rec['address_format_string_id'] = 11;

    $upd = $con->GetUpdateSQL($rst, $rec, false, get_magic_quotes_gpc());
    $con->execute($upd);

    $sql = "SELECT * FROM countries WHERE country_name in ('Russian Federation')";
    $rst = $con->execute($sql);

    $rec = array();
    $rec['address_format_string_id'] = 12;

    $upd = $con->GetUpdateSQL($rst, $rec, false, get_magic_quotes_gpc());
    $con->execute($upd);

    $sql = "SELECT * FROM countries WHERE country_name in ('South Africa', 'United Kingdom')";
    $rst = $con->execute($sql);

    $rec = array();
    $rec['address_format_string_id'] = 13;

    $upd = $con->GetUpdateSQL($rst, $rec, false, get_magic_quotes_gpc());
    $con->execute($upd);

    $sql = "SELECT * FROM countries WHERE country_name in ('The former Yugoslav Republic of Macedonia')";
    $rst = $con->execute($sql);

    $rec = array();
    $rec['address_format_string_id'] = 14;

    $upd = $con->GetUpdateSQL($rst, $rec, false, get_magic_quotes_gpc());
    $con->execute($upd);

    $sql = "SELECT * FROM countries WHERE country_name in ('United States', 'United States Virgin Islands')";
    $rst = $con->execute($sql);

    $rec = array();
    $rec['address_format_string_id'] = 15;

    $upd = $con->GetUpdateSQL($rst, $rec, false, get_magic_quotes_gpc());
    $con->execute($upd);
}

// create the time_daylight_savings table if we need it
$sql ="CREATE TABLE time_daylight_savings (
  daylight_savings_id int(11) NOT NULL auto_increment,
  start_position varchar(5) NOT NULL default '',
  start_day varchar(10) NOT NULL default '',
  start_month int(2) NOT NULL default '0',
  end_position varchar(5) NOT NULL default '',
  end_day varchar(10) NOT NULL default '',
  end_month int(2) NOT NULL default '0',
  hour_shift float NOT NULL default '0',
  last_update date NOT NULL default '0000-00-00',
  current_hour_shift float NOT NULL default '0',
  PRIMARY KEY  (daylight_savings_id)
)";
        //execute
        $rst = $con->execute($sql);

// Add values if none exist (future values will be added, hence the structure)
$sql = "select count(*) as recCount from time_daylight_savings";
$rst = $con->execute($sql);
$recCount = $rst->fields['recCount'];
if($recCount == 0) {
    $msg .= 'Added daylight savings information.<BR><BR>';
    $sql = "INSERT INTO time_daylight_savings VALUES (1,'','',0,'','',0,0,'2004-08-02',0)";
    $con->execute($sql);
    $sql = "INSERT INTO time_daylight_savings VALUES (2,'','',0,'','',0,1,'2004-08-02',1)";
    $con->execute($sql);
    $sql = "INSERT INTO time_daylight_savings VALUES (3,'first','Sunday',4,'last','Sunday',0,1,'2004-08-02',1)";
    $con->execute($sql);
}

// create the time_zones table if we need it
// Null values are important because a select '' might be performed
$sql ="CREATE TABLE time_zones (
  time_zone_id int(11) NOT NULL auto_increment,
  country_id int(11) NOT NULL default '0',
  province varchar(255) default NULL,
  city varchar(255) default NULL,
  postal_code varchar(24) default NULL,
  daylight_savings_id int(11) NOT NULL default '0',
  offset float NOT NULL default '0',
  confirmed char(1) NOT NULL default '',
  PRIMARY KEY  (time_zone_id),
  KEY country_id (country_id),
  KEY province (province)
)";
        //execute
        $rst = $con->execute($sql);

// Add values if none exist (future values will be added, hence the structure)
$sql = "select count(*) as recCount from time_zones";
$rst = $con->execute($sql);
$recCount = $rst->fields['recCount'];
if($recCount == 0) {
    $msg .= 'Added time zone information.<BR><BR>';
    $sql = "INSERT INTO time_zones VALUES (1,218,'AL',NULL,NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (2,218,'AK',NULL,NULL,3,-9,'n')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (3,218,'AK','Anchorage',NULL,3,-9,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (4,218,'AK','Bethel',NULL,3,-9,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (5,218,'AK','College',NULL,3,-9,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (6,218,'AK','Eielson AFB',NULL,3,-9,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (7,218,'AK','Fairbanks',NULL,3,-9,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (8,218,'AK','Juneau',NULL,3,-9,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (9,218,'AK','Kalifornsky',NULL,3,-9,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (10,218,'AK','Kenai',NULL,3,-9,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (11,218,'AK','Ketchikan',NULL,3,-9,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (12,218,'AK','Knik-Fairview',NULL,3,-9,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (13,218,'AK','Kodiak',NULL,3,-9,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (14,218,'AK','Lakes',NULL,3,-9,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (15,218,'AK','Meadow Lakes',NULL,3,-9,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (16,218,'AK','Sitka',NULL,3,-9,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (17,218,'AK','Tanaina',NULL,3,-9,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (18,218,'AK','Wasilla',NULL,3,-9,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (19,218,'AZ',NULL,NULL,1,-7,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (20,218,'AR',NULL,NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (21,218,'CA',NULL,NULL,3,-8,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (22,218,'CO',NULL,NULL,3,-7,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (23,218,'CT',NULL,NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (24,218,'DE',NULL,NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (25,218,'FL',NULL,NULL,3,-5,'n')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (26,218,'FL','Alamonte Springs',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (27,218,'FL','Boca Raton',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (28,218,'FL','Boynton Beach',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (29,218,'FL','Bradenton',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (30,218,'FL','Cape Coral',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (31,218,'FL','Clearwater',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (32,218,'FL','Coral Gables',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (33,218,'FL','Coral Springs',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (34,218,'FL','Davie',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (35,218,'FL','Daytona Beach',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (36,218,'FL','Deerfield Beach',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (37,218,'FL','Delray Beach',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (38,218,'FL','Deltona',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (39,218,'FL','Fort Lauderdale',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (40,218,'FL','Fort Myers',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (41,218,'FL','Gainesville',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (42,218,'FL','Hialeah',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (43,218,'FL','Hollywood',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (44,218,'FL','Jacksonville',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (45,218,'FL','Kissimmee',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (46,218,'FL','Lakeland',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (47,218,'FL','Largo',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (48,218,'FL','Lauderhill',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (49,218,'FL','Margate',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (50,218,'FL','Melbourne',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (51,218,'FL','Miami Beach',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (52,218,'FL','Miami',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (53,218,'FL','Miramar',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (54,218,'FL','North Miami Beach',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (55,218,'FL','North Miami',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (56,218,'FL','Ocala',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (57,218,'FL','Orlando',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (58,218,'FL','Palm Bay',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (59,218,'FL','Pembroke Pines',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (60,218,'FL','Pensacola',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (61,218,'FL','Plantation',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (62,218,'FL','Pompano Beach',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (63,218,'FL','Port Orange',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (64,218,'FL','Port St. Lucie',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (65,218,'FL','Sarasota',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (66,218,'FL','St. Petersburg',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (67,218,'FL','Sunrise',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (68,218,'FL','Tallahassee',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (69,218,'FL','Tamarac',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (70,218,'FL','Tampa',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (71,218,'FL','Titusville',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (72,218,'FL','West Palm Beach',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (73,218,'FL','Weston',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (74,218,'GA',NULL,NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (75,218,'HI',NULL,NULL,1,-10,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (76,218,'ID',NULL,NULL,3,-7,'n')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (77,218,'ID','Ammon',NULL,3,-7,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (78,218,'ID','Blackfoot',NULL,3,-7,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (79,218,'ID','Boise',NULL,3,-7,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (80,218,'ID','Burley',NULL,3,-7,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (81,218,'ID','Caldwell',NULL,3,-7,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (82,218,'ID','Chubbuck',NULL,3,-7,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (83,218,'ID','Coeur d\'Alene',NULL,3,-8,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (84,218,'ID','Eagle',NULL,3,-7,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (85,218,'ID','Garden',NULL,3,-7,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (86,218,'ID','Hailey',NULL,3,-7,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (87,218,'ID','Hayden',NULL,3,-8,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (88,218,'ID','Idaho Falls',NULL,3,-7,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (89,218,'ID','Jerome',NULL,3,-7,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (90,218,'ID','Lewiston',NULL,3,-8,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (91,218,'ID','Meridian',NULL,3,-7,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (92,218,'ID','Moscow',NULL,3,-8,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (93,218,'ID','Mountain Home',NULL,3,-7,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (94,218,'ID','Nampa',NULL,3,-7,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (95,218,'ID','Payette',NULL,3,-7,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (96,218,'ID','Pocatello',NULL,3,-7,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (97,218,'ID','Post Falls',NULL,3,-7,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (98,218,'ID','Rexburg',NULL,3,-7,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (99,218,'ID','Sandpoint',NULL,3,-8,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (100,218,'ID','Twin Falls',NULL,3,-7,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (101,218,'IL',NULL,NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (102,218,'IN',NULL,NULL,1,-5,'n')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (103,218,'IN','Alexandria',NULL,1,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (104,218,'IN','Anderson',NULL,1,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (105,218,'IN','Angola',NULL,1,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (106,218,'IN','Auburn',NULL,1,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (107,218,'IN','Avon',NULL,1,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (108,218,'IN','Batesville',NULL,1,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (109,218,'IN','Bedford',NULL,1,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (110,218,'IN','Beech Grove',NULL,1,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (111,218,'IN','Bloomington',NULL,1,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (112,218,'IN','Bluffton',NULL,1,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (113,218,'IN','Boonville',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (114,218,'IN','Brazil',NULL,1,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (115,218,'IN','Brownsburg',NULL,1,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (116,218,'IN','Carmel',NULL,1,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (117,218,'IN','Cedar Lake',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (118,218,'IN','Charlestown',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (119,218,'IN','Chesterton',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (120,218,'IN','Clarksville',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (121,218,'IN','Columbia City',NULL,1,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (122,218,'IN','Columbus',NULL,1,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (123,218,'IN','Connersville',NULL,1,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (124,218,'IN','Crawfordsville',NULL,1,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (125,218,'IN','Crown Point',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (126,218,'IN','Danville',NULL,1,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (127,218,'IN','Decatur',NULL,1,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (128,218,'IN','Dyer',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (129,218,'IN','East Chicago',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (130,218,'IN','Elkhart',NULL,1,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (131,218,'IN','Elwood',NULL,1,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (132,218,'IN','Evansville',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (133,218,'IN','Evansville',NULL,1,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (134,218,'IN','Fishers',NULL,1,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (135,218,'IN','Fort Wayne',NULL,1,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (136,218,'IN','Frankfort',NULL,1,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (137,218,'IN','Franklin',NULL,1,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (138,218,'IN','Gary',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (139,218,'IN','Gas City',NULL,1,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (140,218,'IN','Goshen',NULL,1,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (141,218,'IN','Greencastle',NULL,1,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (142,218,'IN','Greenfield',NULL,1,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (143,218,'IN','Greensburg',NULL,1,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (144,218,'IN','Greenwood',NULL,1,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (145,218,'IN','Griffith',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (146,218,'IN','Hammond',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (147,218,'IN','Hartford City',NULL,1,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (148,218,'IN','Highland',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (149,218,'IN','Hobart',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (150,218,'IN','Huntington',NULL,1,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (151,218,'IN','Indianapolis',NULL,1,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (152,218,'IN','Jasper',NULL,1,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (153,218,'IN','Jeffersonville',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (154,218,'IN','Kendallville',NULL,1,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (155,218,'IN','Kokomo',NULL,1,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (156,218,'IN','La Porte',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (157,218,'IN','Lafayette',NULL,1,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (158,218,'IN','Lake Station',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (159,218,'IN','Lawrence',NULL,1,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (160,218,'IN','Lebanon',NULL,1,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (161,218,'IN','Logansport',NULL,1,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (162,218,'IN','Lowell',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (163,218,'IN','Madison',NULL,1,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (164,218,'IN','Marion',NULL,1,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (165,218,'IN','Martinsville',NULL,1,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (166,218,'IN','Merillville',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (167,218,'IN','Michigan City',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (168,218,'IN','Mishawaka',NULL,1,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (169,218,'IN','Mooresville',NULL,1,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (170,218,'IN','Mount Vernon',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (171,218,'IN','Muncie',NULL,1,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (172,218,'IN','Munster',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (173,218,'IN','Nappanee',NULL,1,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (174,218,'IN','New Albany',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (175,218,'IN','New Castle',NULL,1,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (176,218,'IN','New Haven',NULL,1,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (177,218,'IN','Noblesville',NULL,1,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (178,218,'IN','North Manchester',NULL,1,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (179,218,'IN','North Vernon',NULL,1,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (180,218,'IN','Peru',NULL,1,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (181,218,'IN','Plainfield',NULL,1,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (182,218,'IN','Plymouth',NULL,1,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (183,218,'IN','Portage',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (184,218,'IN','Portland',NULL,1,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (185,218,'IN','Princeton',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (186,218,'IN','Richmond',NULL,1,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (187,218,'IN','Rochester',NULL,1,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (188,218,'IN','Rushville',NULL,1,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (189,218,'IN','Salem',NULL,1,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (190,218,'IN','Schererville',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (191,218,'IN','Scottsburg',NULL,1,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (192,218,'IN','Sellersburg',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (193,218,'IN','Seymour',NULL,1,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (194,218,'IN','Shelbyville',NULL,1,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (195,218,'IN','South Bend',NULL,1,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (196,218,'IN','Speedway',NULL,1,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (197,218,'IN','St. John',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (198,218,'IN','Tell City',NULL,1,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (199,218,'IN','Terre Haute',NULL,1,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (200,218,'IN','Valparaiso',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (201,218,'IN','Vincennes',NULL,1,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (202,218,'IN','Wabash',NULL,1,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (203,218,'IN','Warsaw',NULL,1,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (204,218,'IN','Washington',NULL,1,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (205,218,'IN','West Lafayette',NULL,1,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (206,218,'IN','Westfield',NULL,1,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (207,218,'IN','Zionsville',NULL,1,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (208,218,'IA',NULL,NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (209,218,'KS',NULL,NULL,3,-6,'n')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (210,218,'KS','Abilene',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (211,218,'KS','Andover',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (212,218,'KS','Arkansas City',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (213,218,'KS','Atchison',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (214,218,'KS','Augusta',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (215,218,'KS','Bonner Springs',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (216,218,'KS','Chanute',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (217,218,'KS','Coffeyville',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (218,218,'KS','Derby',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (219,218,'KS','Dodge City',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (220,218,'KS','El Dorado',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (221,218,'KS','Emporia',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (222,218,'KS','Fort Scott',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (223,218,'KS','Garden City',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (224,218,'KS','Gardner',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (225,218,'KS','Great Bend',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (226,218,'KS','Hays',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (227,218,'KS','Haysville',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (228,218,'KS','Hutchinson',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (229,218,'KS','Independence',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (230,218,'KS','Iola',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (231,218,'KS','Junction City',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (232,218,'KS','Kansas City',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (233,218,'KS','Lansing',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (234,218,'KS','Lawrence',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (235,218,'KS','Leavenworth',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (236,218,'KS','Leawood',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (237,218,'KS','Lenexa',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (238,218,'KS','Liberal',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (239,218,'KS','Manhattan',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (240,218,'KS','McPherson',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (241,218,'KS','Merriam',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (242,218,'KS','Mission',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (243,218,'KS','Newton',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (244,218,'KS','Olathe',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (245,218,'KS','Ottowa',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (246,218,'KS','Overland Park',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (247,218,'KS','Parsons',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (248,218,'KS','Pittsburg',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (249,218,'KS','Prairie Village',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (250,218,'KS','Pratt',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (251,218,'KS','Roeland Park',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (252,218,'KS','Salina',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (253,218,'KS','Shawnee',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (254,218,'KS','Topeka',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (255,218,'KS','Wellington',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (256,218,'KS','Wichita',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (257,218,'KS','Winfield',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (258,218,'KY',NULL,NULL,3,-5,'n')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (259,218,'KY','Alexandria',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (260,218,'KY','Ashland',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (261,218,'KY','Bardstown',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (262,218,'KY','Berea',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (263,218,'KY','Bowling Green',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (264,218,'KY','Campbellsville',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (265,218,'KY','Covington',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (266,218,'KY','Danville',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (267,218,'KY','Edgewood',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (268,218,'KY','Elizabethtown',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (269,218,'KY','Elsmere',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (270,218,'KY','Erlanger',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (271,218,'KY','Florence',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (272,218,'KY','Fort Knox',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (273,218,'KY','Fort Mitchell',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (274,218,'KY','Fort Thomas',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (275,218,'KY','Frankfort',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (276,218,'KY','Franklin',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (277,218,'KY','Georgetown',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (278,218,'KY','Glasgow',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (279,218,'KY','Harrodsburg',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (280,218,'KY','Henderson',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (281,218,'KY','Hopkinsville',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (282,218,'KY','Independence',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (283,218,'KY','Jeffersontown',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (284,218,'KY','Lawrenceburg',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (285,218,'KY','Lexington-Fayette',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (286,218,'KY','Louisville',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (287,218,'KY','Lyndon',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (288,218,'KY','Madisonville',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (289,218,'KY','Mayfield',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (290,218,'KY','Maysville',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (291,218,'KY','Middlesborough',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (292,218,'KY','Mount Washington',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (293,218,'KY','Murray',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (294,218,'KY','Newport',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (295,218,'KY','Nicholasville',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (296,218,'KY','Owensboro',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (297,218,'KY','Paducah',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (298,218,'KY','Paris',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (299,218,'KY','Radcliff',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (300,218,'KY','Richmond',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (301,218,'KY','Shelbyville',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (302,218,'KY','Shepherdsville',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (303,218,'KY','Shively',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (304,218,'KY','Somerset',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (305,218,'KY','St. Matthews',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (306,218,'KY','Winchester',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (307,218,'LA',NULL,NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (308,218,'ME',NULL,NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (309,218,'MD',NULL,NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (310,218,'MA',NULL,NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (311,218,'MI',NULL,NULL,3,-5,'n')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (312,218,'MI','Adrian',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (313,218,'MI','Allen Park',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (314,218,'MI','Anne Arbor',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (315,218,'MI','Auburn Hills',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (316,218,'MI','Battle Creek',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (317,218,'MI','Bedford',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (318,218,'MI','Birmingham',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (319,218,'MI','Blackman',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (320,218,'MI','Bloomfield',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (321,218,'MI','Brownstown',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (322,218,'MI','Burton',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (323,218,'MI','Canton',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (324,218,'MI','Chesterfield',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (325,218,'MI','Clinton',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (326,218,'MI','Commerce',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (327,218,'MI','Davison',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (328,218,'MI','Dearborn',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (329,218,'MI','Delhi',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (330,218,'MI','Delta',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (331,218,'MI','Detroit',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (332,218,'MI','East Lansing',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (333,218,'MI','Eastpointe',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (334,218,'MI','Farmington Hills',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (335,218,'MI','Ferndale',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (336,218,'MI','Flint',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (337,218,'MI','Forest Hills',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (338,218,'MI','Frenchtown',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (339,218,'MI','Gaines',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (340,218,'MI','Garden City',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (341,218,'MI','Genesee',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (342,218,'MI','Georgetown',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (343,218,'MI','Grand Blanc',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (344,218,'MI','Grand Rapids',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (345,218,'MI','Hamburg',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (346,218,'MI','Hamtramck',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (347,218,'MI','Harrison',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (348,218,'MI','Hazel Park',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (349,218,'MI','Highland',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (350,218,'MI','Holland',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (351,218,'MI','Independence',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (352,218,'MI','Inkster',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (353,218,'MI','Jackson',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (354,218,'MI','Kalamazoo',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (355,218,'MI','Kentwood',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (356,218,'MI','Lansing',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (357,218,'MI','Lincoln Park',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (358,218,'MI','Livonia',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (359,218,'MI','Macomb',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (360,218,'MI','Madison Heights',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (361,218,'MI','Mariquette',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (362,218,'MI','Meridian',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (363,218,'MI','Midland',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (364,218,'MI','Monroe',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (365,218,'MI','Mount Morris',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (366,218,'MI','Mount Pleasant',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (367,218,'MI','Muskegon',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (368,218,'MI','Northville',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (369,218,'MI','Norton Shores',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (370,218,'MI','Novi',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (371,218,'MI','Oak Park',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (372,218,'MI','Okemos',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (373,218,'MI','Orion',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (374,218,'MI','Pittsfield',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (375,218,'MI','Plainfield',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (376,218,'MI','Plymouth',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (377,218,'MI','Pontiac',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (378,218,'MI','Port Huron',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (379,218,'MI','Portage',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (380,218,'MI','Redford',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (381,218,'MI','Rochester Hills',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (382,218,'MI','Romulus',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (383,218,'MI','Roseville',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (384,218,'MI','Royal Oak',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (385,218,'MI','Saginaw',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (386,218,'MI','Shelby',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (387,218,'MI','Southfield',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (388,218,'MI','Southgate',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (389,218,'MI','St. Clair Shores',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (390,218,'MI','Sterling Heights',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (391,218,'MI','Summit',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (392,218,'MI','Taylor',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (393,218,'MI','Trenton',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (394,218,'MI','Troy',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (395,218,'MI','Van Buren',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (396,218,'MI','Walker',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (397,218,'MI','Warren',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (398,218,'MI','Washington',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (399,218,'MI','Waterford',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (400,218,'MI','Wayne',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (401,218,'MI','West Bloomfield',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (402,218,'MI','Westland',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (403,218,'MI','White Lake',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (404,218,'MI','Wyandotte',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (405,218,'MI','Wyoming',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (406,218,'MI','Ypsilanti',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (407,218,'MN',NULL,NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (408,218,'MS',NULL,NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (409,218,'MO',NULL,NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (410,218,'MT',NULL,NULL,3,-7,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (411,218,'NE',NULL,NULL,3,-6,'n')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (412,218,'NE','Alliance',NULL,3,-7,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (413,218,'NE','Beatrice',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (414,218,'NE','Bellevue',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (415,218,'NE','Blair',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (416,218,'NE','Chadron',NULL,3,-7,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (417,218,'NE','Chalco',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (418,218,'NE','Columbus',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (419,218,'NE','Crete',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (420,218,'NE','Elkhorn',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (421,218,'NE','Fremont',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (422,218,'NE','Gering',NULL,3,-7,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (423,218,'NE','Grand Island',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (424,218,'NE','Hastings',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (425,218,'NE','Holdrege',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (426,218,'NE','Kearney',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (427,218,'NE','La Vista',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (428,218,'NE','Lexington',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (429,218,'NE','Lincoln',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (430,218,'NE','McCook',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (431,218,'NE','Nebraska City',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (432,218,'NE','Norfolk',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (433,218,'NE','North Platte',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (434,218,'NE','Offutt AFB',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (435,218,'NE','Omaha',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (436,218,'NE','Papillion',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (437,218,'NE','Plattsmouth',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (438,218,'NE','Ralston',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (439,218,'NE','Scottsbluff',NULL,3,-7,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (440,218,'NE','Seward',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (441,218,'NE','Sidney',NULL,3,-7,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (442,218,'NE','South Sioux City',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (443,218,'NE','York',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (444,218,'NV',NULL,NULL,3,-8,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (445,218,'NH',NULL,NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (446,218,'NJ',NULL,NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (447,218,'NM',NULL,NULL,3,-7,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (448,218,'NY',NULL,NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (449,218,'NC',NULL,NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (450,218,'ND',NULL,NULL,3,-6,'n')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (451,218,'ND','Bismarck',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (452,218,'ND','Devils Lake',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (453,218,'ND','Dickinson',NULL,3,-7,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (454,218,'ND','Fargo',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (455,218,'ND','Grand Forks',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (456,218,'ND','Jamestown',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (457,218,'ND','Mandan',NULL,3,-7,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (458,218,'ND','Minot',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (459,218,'ND','Valley City',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (460,218,'ND','Wahpeton',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (461,218,'ND','West Fargo',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (462,218,'ND','Williston',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (463,218,'OH',NULL,NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (464,218,'OK',NULL,NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (465,218,'OR',NULL,NULL,3,-8,'n')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (466,218,'OR','Albany',NULL,3,-8,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (467,218,'OR','Aloha',NULL,3,-8,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (468,218,'OR','Altamont',NULL,3,-8,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (469,218,'OR','Ashland',NULL,3,-8,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (470,218,'OR','Beaverton',NULL,3,-8,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (471,218,'OR','Bend',NULL,3,-8,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (472,218,'OR','Canby',NULL,3,-8,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (473,218,'OR','Cedar Mill',NULL,3,-8,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (474,218,'OR','Central Point',NULL,3,-8,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (475,218,'OR','City of The Dalles',NULL,3,-8,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (476,218,'OR','Coos Bay',NULL,3,-8,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (477,218,'OR','Corvalis',NULL,3,-8,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (478,218,'OR','Dallas',NULL,3,-8,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (479,218,'OR','Eugene',NULL,3,-8,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (480,218,'OR','Forest Grove',NULL,3,-8,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (481,218,'OR','Four Corners',NULL,3,-8,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (482,218,'OR','Gladstone',NULL,3,-8,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (483,218,'OR','Grants Pass',NULL,3,-8,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (484,218,'OR','Gresham',NULL,3,-8,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (485,218,'OR','Hayesville',NULL,3,-8,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (486,218,'OR','Hermiston',NULL,3,-8,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (487,218,'OR','Hillsboro',NULL,3,-8,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (488,218,'OR','Keizer',NULL,3,-8,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (489,218,'OR','Klamath Falls',NULL,3,-8,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (490,218,'OR','La Grande',NULL,3,-8,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (491,218,'OR','Lake Oswego',NULL,3,-8,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (492,218,'OR','Lebanon',NULL,3,-8,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (493,218,'OR','McMinnville',NULL,3,-8,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (494,218,'OR','Medford',NULL,3,-8,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (495,218,'OR','Milwaukie',NULL,3,-8,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (496,218,'OR','Newberg',NULL,3,-8,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (497,218,'OR','Oak Grove',NULL,3,-8,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (498,218,'OR','Oatfield',NULL,3,-8,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (499,218,'OR','Ontario',NULL,3,-7,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (500,218,'OR','Oregon City',NULL,3,-8,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (501,218,'OR','Pendleton',NULL,3,-8,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (502,218,'OR','Portland',NULL,3,-8,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (503,218,'OR','Redmond',NULL,3,-8,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (504,218,'OR','Roseburg',NULL,3,-8,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (505,218,'OR','Salem',NULL,3,-8,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (506,218,'OR','Sherwood',NULL,3,-8,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (507,218,'OR','Springfield',NULL,3,-8,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (508,218,'OR','Tigard',NULL,3,-8,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (509,218,'OR','Troutdale',NULL,3,-8,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (510,218,'OR','Tualatin',NULL,3,-8,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (511,218,'OR','West Linn',NULL,3,-8,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (512,218,'OR','Wilsonville',NULL,3,-8,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (513,218,'OR','Woddburn',NULL,3,-8,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (514,218,'PA',NULL,NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (515,218,'PR',NULL,NULL,1,-4,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (516,218,'RI',NULL,NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (517,218,'SC',NULL,NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (518,218,'SD',NULL,NULL,3,-6,'n')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (519,218,'SD','Aberdeen',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (520,218,'SD','Brookings',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (521,218,'SD','Huron',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (522,218,'SD','Mitchell',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (523,218,'SD','Pierre',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (524,218,'SD','Rapid City',NULL,3,-7,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (525,218,'SD','Rapid Valley',NULL,3,-7,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (526,218,'SD','Sioux Falls',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (527,218,'SD','Spearfish',NULL,3,-7,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (528,218,'SD','Vermillion',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (529,218,'SD','Watertown',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (530,218,'SD','Yankton',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (531,218,'TN',NULL,NULL,3,-6,'n')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (532,218,'TN','Alcoa',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (533,218,'TN','Athens',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (534,218,'TN','Bartlett',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (535,218,'TN','Bloomingdale',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (536,218,'TN','Brentwood',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (537,218,'TN','Bristol',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (538,218,'TN','Brownsville',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (539,218,'TN','Chattanooga',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (540,218,'TN','Clarksville',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (541,218,'TN','Cleveland',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (542,218,'TN','Clinton',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (543,218,'TN','Collegedale',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (544,218,'TN','Collierville',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (545,218,'TN','Colonial Heights',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (546,218,'TN','Columbia',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (547,218,'TN','Cookeville',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (548,218,'TN','Covington',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (549,218,'TN','Crossville',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (550,218,'TN','Dickson',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (551,218,'TN','Dyersburg',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (552,218,'TN','East Brainerd',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (553,218,'TN','East Ridge',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (554,218,'TN','Elizabethton',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (555,218,'TN','Farragut',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (556,218,'TN','Fayetteville',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (557,218,'TN','Franklin',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (558,218,'TN','Gallatin',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (559,218,'TN','Germantown',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (560,218,'TN','Goodlettsville',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (561,218,'TN','Green Hill',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (562,218,'TN','Greeneville',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (563,218,'TN','Harriman',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (564,218,'TN','Harrison',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (565,218,'TN','Hendersonville',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (566,218,'TN','Humboldt',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (567,218,'TN','Jackson',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (568,218,'TN','Jefferson City',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (569,218,'TN','Johnson City',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (570,218,'TN','Kingsport',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (571,218,'TN','Knoxville',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (572,218,'TN','La Follette',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (573,218,'TN','La Vergne',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (574,218,'TN','Lakeland',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (575,218,'TN','Lawrenceburg',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (576,218,'TN','Lebanon',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (577,218,'TN','Lenoir City',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (578,218,'TN','Lewisburg',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (579,218,'TN','Lexington',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (580,218,'TN','Manchester',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (581,218,'TN','Martin',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (582,218,'TN','Maryville',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (583,218,'TN','McMinnville',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (584,218,'TN','Memphis',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (585,218,'TN','Middle Valley',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (586,218,'TN','Milan',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (587,218,'TN','Millington',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (588,218,'TN','Morristown',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (589,218,'TN','Mount Juliet',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (590,218,'TN','Murfreesboro',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (591,218,'TN','Nashville-Davidson',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (592,218,'TN','Newport',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (593,218,'TN','Oak Ridge',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (594,218,'TN','Paris',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (595,218,'TN','Portland',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (596,218,'TN','Pulaski',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (597,218,'TN','Red Bank',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (598,218,'TN','Ripley',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (599,218,'TN','Savannah',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (600,218,'TN','Sevierville',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (601,218,'TN','Seymour',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (602,218,'TN','Shelbyville',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (603,218,'TN','Signal Mountain',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (604,218,'TN','Smyrna',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (605,218,'TN','Soddy-Daisy',NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (606,218,'TN','Spring Hill',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (607,218,'TN','Springfield',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (608,218,'TN','Tullahoma',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (609,218,'TN','Union',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (610,218,'TN','White House',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (611,218,'TN','Winchester',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (612,218,'TX',NULL,NULL,3,-6,'n')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (613,218,'TX','Abilene',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (614,218,'TX','Allen',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (615,218,'TX','Amarillo',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (616,218,'TX','Arlington',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (617,218,'TX','Atascocita',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (618,218,'TX','Austin',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (619,218,'TX','Baytown',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (620,218,'TX','Beaumont',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (621,218,'TX','Bedford',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (622,218,'TX','Big Spring',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (623,218,'TX','Brownsville',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (624,218,'TX','Bryan',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (625,218,'TX','Carrollton',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (626,218,'TX','Cedar Hill',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (627,218,'TX','Cedar Park',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (628,218,'TX','Channelview',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (629,218,'TX','Cleburne',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (630,218,'TX','College Station',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (631,218,'TX','Conroe',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (632,218,'TX','Coppell',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (633,218,'TX','Copperas Cove',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (634,218,'TX','Corpus Christi',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (635,218,'TX','Corsicana',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (636,218,'TX','Dallas',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (637,218,'TX','Deer Park',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (638,218,'TX','Del Rio',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (639,218,'TX','Denton',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (640,218,'TX','DeSoto',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (641,218,'TX','Duncanville',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (642,218,'TX','Edinburg',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (643,218,'TX','El Paso',NULL,3,-7,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (644,218,'TX','Euless',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (645,218,'TX','Farmers Branch',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (646,218,'TX','Flower Mound',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (647,218,'TX','Fort Hood',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (648,218,'TX','Fort Worth',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (649,218,'TX','Friendswood',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (650,218,'TX','Frisco',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (651,218,'TX','Galveston',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (652,218,'TX','Garland',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (653,218,'TX','Georgetown',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (654,218,'TX','Grand Prairie',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (655,218,'TX','Grapevine',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (656,218,'TX','Haltom City',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (657,218,'TX','Harlingen',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (658,218,'TX','Houston',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (659,218,'TX','Huntsville',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (660,218,'TX','Hurst',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (661,218,'TX','Irving',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (662,218,'TX','Keller',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (663,218,'TX','Killeen',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (664,218,'TX','Kingsville',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (665,218,'TX','La Porte',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (666,218,'TX','Lake Jackson',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (667,218,'TX','Lancaster',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (668,218,'TX','Laredo',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (669,218,'TX','League City',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (670,218,'TX','Lewisville',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (671,218,'TX','Longview',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (672,218,'TX','Lubbock',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (673,218,'TX','Lufkin',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (674,218,'TX','Mansfield',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (675,218,'TX','McAllen',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (676,218,'TX','McKinney',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (677,218,'TX','Mesquite',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (678,218,'TX','Midland',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (679,218,'TX','Mission Bend',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (680,218,'TX','Mission',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (681,218,'TX','Missouri City',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (682,218,'TX','Nacogdoches',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (683,218,'TX','New Braunfels',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (684,218,'TX','North Richland Hills',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (685,218,'TX','Odessa',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (686,218,'TX','Paris',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (687,218,'TX','Pasadena',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (688,218,'TX','Pearland',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (689,218,'TX','Pharr',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (690,218,'TX','Plano',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (691,218,'TX','Port Arthur',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (692,218,'TX','Richardson',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (693,218,'TX','Round Rock',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (694,218,'TX','Rowlett',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (695,218,'TX','San Angelo',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (696,218,'TX','San Antonio',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (697,218,'TX','San Juan',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (698,218,'TX','San Marcos',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (699,218,'TX','Sherman',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (700,218,'TX','Socorro',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (701,218,'TX','Spring',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (702,218,'TX','Sugar Land',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (703,218,'TX','Temple',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (704,218,'TX','Texarkana',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (705,218,'TX','Texas City',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (706,218,'TX','The Colony',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (707,218,'TX','The Woodlands',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (708,218,'TX','Tyler',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (709,218,'TX','Victoria',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (710,218,'TX','Waco',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (711,218,'TX','Weslaco',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (712,218,'TX','Wichita Falls',NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (713,218,'UT',NULL,NULL,3,-7,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (714,218,'VT',NULL,NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (715,218,'VA',NULL,NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (716,218,'WA',NULL,NULL,3,-8,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (717,218,'DC',NULL,NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (718,218,'WV',NULL,NULL,3,-5,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (719,218,'WI',NULL,NULL,3,-6,'y')";
    $con->execute($sql);
    $sql = "INSERT INTO time_zones VALUES (720,218,'WY',NULL,NULL,3,-7,'y')";
    $con->execute($sql);
}

// Add indexes so inserting daylight savings time takes a reasonable about of time
$sql = "create index province on time_zones (province)";
$rst = $con->execute($sql);

//Update address table
$sql = "alter table addresses add offset float";
$con->execute($sql);
$sql = "alter table addresses add daylight_savings_id int unsigned";
$con->execute($sql);

//Go through each address to insert daylight savings
$sql = 'SELECT address_id
        FROM addresses
        WHERE (daylight_savings_id=0 or daylight_savings_id is null)';
$rst = $con->execute($sql);
if(!$rst) {
    db_error_handler($con, $sql);
}
else {
    while(!$rst->EOF) {
        if($time_zone_offset = time_zone_offset($con, $rst->fields['address_id'])) {
            $sql = 'SELECT * FROM addresses where address_id=' . $rst->fields['address_id'];
            $rst2 = $con->execute($sql);

            $rec = array();
            $rec['offset'] = $time_zone_offset['offset'];
            $rec['daylight_savings_id'] = $time_zone_offset['daylight_savings_id'];

            $upd = $con->GetUpdateSQL($rst2, $rec, false, get_magic_quotes_gpc());
            if(!$con->execute($upd)) {
                db_error_handler($con, $sql);
            }
        }
        $rst->movenext();
    }
}

//Add default text to activity templates table
$sql = "alter table activity_templates add default_text text after activity_description";
$con->execute($sql);


//update countries table with currency_code for each country

$sql="SELECT currency_code from countries WHERE currency_code IS NOT NULL";
$rst=$con->execute($sql);

if ($rst->NumRows()==0) {

    $sql="UPDATE countries set currency_code='AFN' WHERE iso_code2='AF'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='ALL' WHERE iso_code2='AL'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='DZD' WHERE iso_code2='DZ'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='USD' WHERE iso_code2='AS'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='EUR' WHERE iso_code2='AD'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='AOA' WHERE iso_code2='AO'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='XCD' WHERE iso_code2='AI'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='XCD' WHERE iso_code2='AG'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='ARS' WHERE iso_code2='AR'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='AMD' WHERE iso_code2='AM'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='AWG' WHERE iso_code2='AW'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='AUD' WHERE iso_code2='AU'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='EUR' WHERE iso_code2='AT'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='AZM' WHERE iso_code2='AZ'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='BSD' WHERE iso_code2='BS'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='BHD' WHERE iso_code2='BH'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='BDT' WHERE iso_code2='BD'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='BBD' WHERE iso_code2='BB'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='BYR' WHERE iso_code2='BY'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='EUR' WHERE iso_code2='BE'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='BZD' WHERE iso_code2='BZ'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='XOF' WHERE iso_code2='BJ'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='BMD' WHERE iso_code2='BM'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='INR' WHERE iso_code2='BT'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='BTN' WHERE iso_code2='BT'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='BOB' WHERE iso_code2='BO'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='BOV' WHERE iso_code2='BO'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='BAM' WHERE iso_code2='BA'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='BWP' WHERE iso_code2='BW'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='NOK' WHERE iso_code2='BV'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='BRL' WHERE iso_code2='BR'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='USD' WHERE iso_code2='IO'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='BND' WHERE iso_code2='BN'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='BGN' WHERE iso_code2='BG'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='XOF' WHERE iso_code2='BF'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='BIF' WHERE iso_code2='BI'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='KHR' WHERE iso_code2='KH'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='XAF' WHERE iso_code2='CM'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='CAD' WHERE iso_code2='CA'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='CVE' WHERE iso_code2='CV'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='KYD' WHERE iso_code2='KY'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='XAF' WHERE iso_code2='CF'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='XAF' WHERE iso_code2='TD'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='CLP' WHERE iso_code2='CL'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='CLF' WHERE iso_code2='CL'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='CNY' WHERE iso_code2='CN'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='AUD' WHERE iso_code2='CX'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='AUD' WHERE iso_code2='CC'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='COP' WHERE iso_code2='CO'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='COU' WHERE iso_code2='CO'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='KMF' WHERE iso_code2='KM'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='XAF' WHERE iso_code2='CG'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='CDF' WHERE iso_code2='CD'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='NZD' WHERE iso_code2='CK'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='CRC' WHERE iso_code2='CR'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='XOF' WHERE iso_code2='CI'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='HRK' WHERE iso_code2='HR'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='CUP' WHERE iso_code2='CU'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='CYP' WHERE iso_code2='CY'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='CZK' WHERE iso_code2='CZ'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='DKK' WHERE iso_code2='DK'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='DJF' WHERE iso_code2='DJ'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='XCD' WHERE iso_code2='DM'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='DOP' WHERE iso_code2='DO'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='USD' WHERE iso_code2='EC'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='EGP' WHERE iso_code2='EG'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='SVC' WHERE iso_code2='SV'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='USD' WHERE iso_code2='SV'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='XAF' WHERE iso_code2='GQ'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='ERN' WHERE iso_code2='ER'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='EEK' WHERE iso_code2='EE'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='ETB' WHERE iso_code2='ET'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='FKP' WHERE iso_code2='FK'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='DKK' WHERE iso_code2='FO'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='FJD' WHERE iso_code2='FJ'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='EUR' WHERE iso_code2='FI'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='EUR' WHERE iso_code2='FR'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='EUR' WHERE iso_code2='GF'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='XPF' WHERE iso_code2='PF'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='EUR' WHERE iso_code2='TF'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='XAF' WHERE iso_code2='GA'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='GMD' WHERE iso_code2='GM'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='GEL' WHERE iso_code2='GE'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='EUR' WHERE iso_code2='DE'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='GHC' WHERE iso_code2='GH'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='GIP' WHERE iso_code2='GI'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='EUR' WHERE iso_code2='GR'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='DKK' WHERE iso_code2='GL'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='XCD' WHERE iso_code2='GD'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='EUR' WHERE iso_code2='GP'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='USD' WHERE iso_code2='GU'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='GTQ' WHERE iso_code2='GT'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='GNF' WHERE iso_code2='GN'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='GWP' WHERE iso_code2='GW'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='XOF' WHERE iso_code2='GW'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='GYD' WHERE iso_code2='GY'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='HTG' WHERE iso_code2='HT'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='USD' WHERE iso_code2='HT'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='AUD' WHERE iso_code2='HM'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='EUR' WHERE iso_code2='VA'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='HNL' WHERE iso_code2='HN'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='HKD' WHERE iso_code2='HK'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='HUF' WHERE iso_code2='HU'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='ISK' WHERE iso_code2='IS'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='INR' WHERE iso_code2='IN'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='IDR' WHERE iso_code2='ID'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='IRR' WHERE iso_code2='IR'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='IQD' WHERE iso_code2='IQ'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='EUR' WHERE iso_code2='IE'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='ILS' WHERE iso_code2='IL'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='EUR' WHERE iso_code2='IT'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='JMD' WHERE iso_code2='JM'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='JPY' WHERE iso_code2='JP'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='JOD' WHERE iso_code2='JO'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='KZT' WHERE iso_code2='KZ'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='KES' WHERE iso_code2='KE'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='AUD' WHERE iso_code2='KI'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='KPW' WHERE iso_code2='KP'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='KRW' WHERE iso_code2='KR'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='KWD' WHERE iso_code2='KW'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='KGS' WHERE iso_code2='KG'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='LAK' WHERE iso_code2='LA'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='LVL' WHERE iso_code2='LV'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='LBP' WHERE iso_code2='LB'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='ZAR' WHERE iso_code2='LS'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='LSL' WHERE iso_code2='LS'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='LRD' WHERE iso_code2='LR'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='LYD' WHERE iso_code2='LY'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='CHF' WHERE iso_code2='LI'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='LTL' WHERE iso_code2='LT'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='EUR' WHERE iso_code2='LU'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='MOP' WHERE iso_code2='MO'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='MKD' WHERE iso_code2='MK'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='MGA' WHERE iso_code2='MG'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='MGF' WHERE iso_code2='MG'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='MWK' WHERE iso_code2='MW'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='MYR' WHERE iso_code2='MY'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='MVR' WHERE iso_code2='MV'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='XOF' WHERE iso_code2='ML'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='MTL' WHERE iso_code2='MT'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='USD' WHERE iso_code2='MH'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='EUR' WHERE iso_code2='MQ'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='MRO' WHERE iso_code2='MR'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='MUR' WHERE iso_code2='MU'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='EUR' WHERE iso_code2='YT'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='MXN' WHERE iso_code2='MX'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='MXV' WHERE iso_code2='MX'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='USD' WHERE iso_code2='FM'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='MDL' WHERE iso_code2='MD'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='EUR' WHERE iso_code2='MC'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='MNT' WHERE iso_code2='MN'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='XCD' WHERE iso_code2='MS'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='MAD' WHERE iso_code2='MA'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='MZM' WHERE iso_code2='MZ'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='MMK' WHERE iso_code2='MM'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='ZAR' WHERE iso_code2='NA'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='NAD' WHERE iso_code2='NA'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='AUD' WHERE iso_code2='NR'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='NPR' WHERE iso_code2='NP'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='EUR' WHERE iso_code2='NL'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='ANG' WHERE iso_code2='AN'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='XPF' WHERE iso_code2='NC'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='NZD' WHERE iso_code2='NZ'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='NIO' WHERE iso_code2='NI'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='XOF' WHERE iso_code2='NE'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='NGN' WHERE iso_code2='NG'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='NZD' WHERE iso_code2='NU'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='AUD' WHERE iso_code2='NF'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='USD' WHERE iso_code2='MP'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='NOK' WHERE iso_code2='NO'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='OMR' WHERE iso_code2='OM'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='PKR' WHERE iso_code2='PK'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='USD' WHERE iso_code2='PW'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='PAB' WHERE iso_code2='PA'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='USD' WHERE iso_code2='PA'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='PGK' WHERE iso_code2='PG'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='PYG' WHERE iso_code2='PY'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='PEN' WHERE iso_code2='PE'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='PHP' WHERE iso_code2='PH'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='NZD' WHERE iso_code2='PN'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='PLN' WHERE iso_code2='PL'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='EUR' WHERE iso_code2='PT'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='USD' WHERE iso_code2='PR'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='QAR' WHERE iso_code2='QA'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='EUR' WHERE iso_code2='RE'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='ROL' WHERE iso_code2='RO'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='RUR' WHERE iso_code2='RU'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='RUB' WHERE iso_code2='RU'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='RWF' WHERE iso_code2='RW'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='SHP' WHERE iso_code2='SH'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='XCD' WHERE iso_code2='KN'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='XCD' WHERE iso_code2='LC'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='EUR' WHERE iso_code2='PM'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='XCD' WHERE iso_code2='VC'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='WST' WHERE iso_code2='WS'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='EUR' WHERE iso_code2='SM'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='STD' WHERE iso_code2='ST'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='SAR' WHERE iso_code2='SA'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='XOF' WHERE iso_code2='SN'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='EUR' WHERE iso_code2='CS'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='CSD' WHERE iso_code2='CS'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='SCR' WHERE iso_code2='SC'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='SLL' WHERE iso_code2='SL'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='SGD' WHERE iso_code2='SG'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='SKK' WHERE iso_code2='SK'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='SIT' WHERE iso_code2='SI'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='SBD' WHERE iso_code2='SB'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='SOS' WHERE iso_code2='SO'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='ZAR' WHERE iso_code2='ZA'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='EUR' WHERE iso_code2='ES'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='LKR' WHERE iso_code2='LK'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='SDD' WHERE iso_code2='SD'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='SRD' WHERE iso_code2='SR'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='NOK' WHERE iso_code2='SJ'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='SZL' WHERE iso_code2='SZ'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='SEK' WHERE iso_code2='SE'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='CHF' WHERE iso_code2='CH'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='SYP' WHERE iso_code2='SY'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='TWD' WHERE iso_code2='TW'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='TJS' WHERE iso_code2='TJ'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='TZS' WHERE iso_code2='TZ'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='THB' WHERE iso_code2='TH'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='USD' WHERE iso_code2='TL'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='XOF' WHERE iso_code2='TG'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='NZD' WHERE iso_code2='TK'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='TOP' WHERE iso_code2='TO'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='TTD' WHERE iso_code2='TT'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='TND' WHERE iso_code2='TN'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='TRL' WHERE iso_code2='TR'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='TMM' WHERE iso_code2='TM'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='USD' WHERE iso_code2='TC'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='AUD' WHERE iso_code2='TV'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='UGX' WHERE iso_code2='UG'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='UAH' WHERE iso_code2='UA'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='AED' WHERE iso_code2='AE'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='GBP' WHERE iso_code2='GB'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='USD' WHERE iso_code2='US'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='USS' WHERE iso_code2='US'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='USN' WHERE iso_code2='US'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='USD' WHERE iso_code2='UM'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='UYU' WHERE iso_code2='UY'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='UZS' WHERE iso_code2='UZ'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='VUV' WHERE iso_code2='VU'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='VEB' WHERE iso_code2='VE'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='VND' WHERE iso_code2='VN'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='USD' WHERE iso_code2='VG'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='USD' WHERE iso_code2='VI'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='XPF' WHERE iso_code2='WF'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='MAD' WHERE iso_code2='EH'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='YER' WHERE iso_code2='YE'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='ZMK' WHERE iso_code2='ZM'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='ZWD' WHERE iso_code2='ZW'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='AFN' WHERE iso_code2='AF'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='ALL' WHERE iso_code2='AL'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='DZD' WHERE iso_code2='DZ'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='USD' WHERE iso_code2='AS'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='EUR' WHERE iso_code2='AD'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='AOA' WHERE iso_code2='AO'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='XCD' WHERE iso_code2='AI'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='XCD' WHERE iso_code2='AG'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='ARS' WHERE iso_code2='AR'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='AMD' WHERE iso_code2='AM'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='AWG' WHERE iso_code2='AW'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='AUD' WHERE iso_code2='AU'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='EUR' WHERE iso_code2='AT'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='AZM' WHERE iso_code2='AZ'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='BSD' WHERE iso_code2='BS'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='BHD' WHERE iso_code2='BH'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='BDT' WHERE iso_code2='BD'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='BBD' WHERE iso_code2='BB'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='BYR' WHERE iso_code2='BY'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='EUR' WHERE iso_code2='BE'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='BZD' WHERE iso_code2='BZ'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='XOF' WHERE iso_code2='BJ'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='BMD' WHERE iso_code2='BM'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='INR' WHERE iso_code2='BT'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='BTN' WHERE iso_code2='BT'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='BOB' WHERE iso_code2='BO'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='BOV' WHERE iso_code2='BO'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='BAM' WHERE iso_code2='BA'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='BWP' WHERE iso_code2='BW'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='NOK' WHERE iso_code2='BV'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='BRL' WHERE iso_code2='BR'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='USD' WHERE iso_code2='IO'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='BND' WHERE iso_code2='BN'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='BGN' WHERE iso_code2='BG'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='XOF' WHERE iso_code2='BF'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='BIF' WHERE iso_code2='BI'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='KHR' WHERE iso_code2='KH'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='XAF' WHERE iso_code2='CM'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='CAD' WHERE iso_code2='CA'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='CVE' WHERE iso_code2='CV'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='KYD' WHERE iso_code2='KY'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='XAF' WHERE iso_code2='CF'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='XAF' WHERE iso_code2='TD'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='CLP' WHERE iso_code2='CL'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='CLF' WHERE iso_code2='CL'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='CNY' WHERE iso_code2='CN'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='AUD' WHERE iso_code2='CX'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='AUD' WHERE iso_code2='CC'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='COP' WHERE iso_code2='CO'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='COU' WHERE iso_code2='CO'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='KMF' WHERE iso_code2='KM'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='XAF' WHERE iso_code2='CG'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='CDF' WHERE iso_code2='CD'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='NZD' WHERE iso_code2='CK'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='CRC' WHERE iso_code2='CR'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='XOF' WHERE iso_code2='CI'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='HRK' WHERE iso_code2='HR'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='CUP' WHERE iso_code2='CU'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='CYP' WHERE iso_code2='CY'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='CZK' WHERE iso_code2='CZ'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='DKK' WHERE iso_code2='DK'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='DJF' WHERE iso_code2='DJ'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='XCD' WHERE iso_code2='DM'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='DOP' WHERE iso_code2='DO'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='USD' WHERE iso_code2='EC'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='EGP' WHERE iso_code2='EG'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='SVC' WHERE iso_code2='SV'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='USD' WHERE iso_code2='SV'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='XAF' WHERE iso_code2='GQ'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='ERN' WHERE iso_code2='ER'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='EEK' WHERE iso_code2='EE'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='ETB' WHERE iso_code2='ET'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='FKP' WHERE iso_code2='FK'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='DKK' WHERE iso_code2='FO'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='FJD' WHERE iso_code2='FJ'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='EUR' WHERE iso_code2='FI'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='EUR' WHERE iso_code2='FR'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='EUR' WHERE iso_code2='GF'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='XPF' WHERE iso_code2='PF'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='EUR' WHERE iso_code2='TF'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='XAF' WHERE iso_code2='GA'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='GMD' WHERE iso_code2='GM'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='GEL' WHERE iso_code2='GE'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='EUR' WHERE iso_code2='DE'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='GHC' WHERE iso_code2='GH'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='GIP' WHERE iso_code2='GI'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='EUR' WHERE iso_code2='GR'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='DKK' WHERE iso_code2='GL'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='XCD' WHERE iso_code2='GD'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='EUR' WHERE iso_code2='GP'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='USD' WHERE iso_code2='GU'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='GTQ' WHERE iso_code2='GT'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='GNF' WHERE iso_code2='GN'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='GWP' WHERE iso_code2='GW'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='XOF' WHERE iso_code2='GW'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='GYD' WHERE iso_code2='GY'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='HTG' WHERE iso_code2='HT'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='USD' WHERE iso_code2='HT'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='AUD' WHERE iso_code2='HM'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='EUR' WHERE iso_code2='VA'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='HNL' WHERE iso_code2='HN'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='HKD' WHERE iso_code2='HK'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='HUF' WHERE iso_code2='HU'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='ISK' WHERE iso_code2='IS'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='INR' WHERE iso_code2='IN'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='IDR' WHERE iso_code2='ID'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='IRR' WHERE iso_code2='IR'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='IQD' WHERE iso_code2='IQ'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='EUR' WHERE iso_code2='IE'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='ILS' WHERE iso_code2='IL'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='EUR' WHERE iso_code2='IT'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='JMD' WHERE iso_code2='JM'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='JPY' WHERE iso_code2='JP'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='JOD' WHERE iso_code2='JO'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='KZT' WHERE iso_code2='KZ'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='KES' WHERE iso_code2='KE'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='AUD' WHERE iso_code2='KI'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='KPW' WHERE iso_code2='KP'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='KRW' WHERE iso_code2='KR'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='KWD' WHERE iso_code2='KW'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='KGS' WHERE iso_code2='KG'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='LAK' WHERE iso_code2='LA'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='LVL' WHERE iso_code2='LV'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='LBP' WHERE iso_code2='LB'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='ZAR' WHERE iso_code2='LS'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='LSL' WHERE iso_code2='LS'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='LRD' WHERE iso_code2='LR'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='LYD' WHERE iso_code2='LY'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='CHF' WHERE iso_code2='LI'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='LTL' WHERE iso_code2='LT'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='EUR' WHERE iso_code2='LU'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='MOP' WHERE iso_code2='MO'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='MKD' WHERE iso_code2='MK'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='MGA' WHERE iso_code2='MG'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='MGF' WHERE iso_code2='MG'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='MWK' WHERE iso_code2='MW'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='MYR' WHERE iso_code2='MY'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='MVR' WHERE iso_code2='MV'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='XOF' WHERE iso_code2='ML'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='MTL' WHERE iso_code2='MT'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='USD' WHERE iso_code2='MH'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='EUR' WHERE iso_code2='MQ'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='MRO' WHERE iso_code2='MR'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='MUR' WHERE iso_code2='MU'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='EUR' WHERE iso_code2='YT'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='MXN' WHERE iso_code2='MX'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='MXV' WHERE iso_code2='MX'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='USD' WHERE iso_code2='FM'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='MDL' WHERE iso_code2='MD'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='EUR' WHERE iso_code2='MC'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='MNT' WHERE iso_code2='MN'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='XCD' WHERE iso_code2='MS'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='MAD' WHERE iso_code2='MA'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='MZM' WHERE iso_code2='MZ'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='MMK' WHERE iso_code2='MM'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='ZAR' WHERE iso_code2='NA'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='NAD' WHERE iso_code2='NA'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='AUD' WHERE iso_code2='NR'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='NPR' WHERE iso_code2='NP'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='EUR' WHERE iso_code2='NL'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='ANG' WHERE iso_code2='AN'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='XPF' WHERE iso_code2='NC'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='NZD' WHERE iso_code2='NZ'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='NIO' WHERE iso_code2='NI'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='XOF' WHERE iso_code2='NE'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='NGN' WHERE iso_code2='NG'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='NZD' WHERE iso_code2='NU'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='AUD' WHERE iso_code2='NF'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='USD' WHERE iso_code2='MP'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='NOK' WHERE iso_code2='NO'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='OMR' WHERE iso_code2='OM'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='PKR' WHERE iso_code2='PK'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='USD' WHERE iso_code2='PW'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='PAB' WHERE iso_code2='PA'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='USD' WHERE iso_code2='PA'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='PGK' WHERE iso_code2='PG'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='PYG' WHERE iso_code2='PY'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='PEN' WHERE iso_code2='PE'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='PHP' WHERE iso_code2='PH'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='NZD' WHERE iso_code2='PN'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='PLN' WHERE iso_code2='PL'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='EUR' WHERE iso_code2='PT'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='USD' WHERE iso_code2='PR'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='QAR' WHERE iso_code2='QA'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='EUR' WHERE iso_code2='RE'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='ROL' WHERE iso_code2='RO'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='RUR' WHERE iso_code2='RU'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='RUB' WHERE iso_code2='RU'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='RWF' WHERE iso_code2='RW'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='SHP' WHERE iso_code2='SH'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='XCD' WHERE iso_code2='KN'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='XCD' WHERE iso_code2='LC'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='EUR' WHERE iso_code2='PM'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='XCD' WHERE iso_code2='VC'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='WST' WHERE iso_code2='WS'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='EUR' WHERE iso_code2='SM'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='STD' WHERE iso_code2='ST'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='SAR' WHERE iso_code2='SA'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='XOF' WHERE iso_code2='SN'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='EUR' WHERE iso_code2='CS'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='CSD' WHERE iso_code2='CS'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='SCR' WHERE iso_code2='SC'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='SLL' WHERE iso_code2='SL'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='SGD' WHERE iso_code2='SG'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='SKK' WHERE iso_code2='SK'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='SIT' WHERE iso_code2='SI'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='SBD' WHERE iso_code2='SB'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='SOS' WHERE iso_code2='SO'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='ZAR' WHERE iso_code2='ZA'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='EUR' WHERE iso_code2='ES'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='LKR' WHERE iso_code2='LK'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='SDD' WHERE iso_code2='SD'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='SRD' WHERE iso_code2='SR'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='NOK' WHERE iso_code2='SJ'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='SZL' WHERE iso_code2='SZ'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='SEK' WHERE iso_code2='SE'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='CHF' WHERE iso_code2='CH'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='SYP' WHERE iso_code2='SY'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='TWD' WHERE iso_code2='TW'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='TJS' WHERE iso_code2='TJ'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='TZS' WHERE iso_code2='TZ'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='THB' WHERE iso_code2='TH'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='USD' WHERE iso_code2='TL'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='XOF' WHERE iso_code2='TG'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='NZD' WHERE iso_code2='TK'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='TOP' WHERE iso_code2='TO'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='TTD' WHERE iso_code2='TT'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='TND' WHERE iso_code2='TN'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='TRL' WHERE iso_code2='TR'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='TMM' WHERE iso_code2='TM'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='USD' WHERE iso_code2='TC'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='AUD' WHERE iso_code2='TV'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='UGX' WHERE iso_code2='UG'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='UAH' WHERE iso_code2='UA'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='AED' WHERE iso_code2='AE'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='GBP' WHERE iso_code2='GB'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='USD' WHERE iso_code2='US'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='USS' WHERE iso_code2='US'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='USN' WHERE iso_code2='US'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='USD' WHERE iso_code2='UM'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='UYU' WHERE iso_code2='UY'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='UZS' WHERE iso_code2='UZ'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='VUV' WHERE iso_code2='VU'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='VEB' WHERE iso_code2='VE'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='VND' WHERE iso_code2='VN'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='USD' WHERE iso_code2='VG'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='USD' WHERE iso_code2='VI'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='XPF' WHERE iso_code2='WF'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='MAD' WHERE iso_code2='EH'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='YER' WHERE iso_code2='YE'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='ZMK' WHERE iso_code2='ZM'";
    $con->execute($sql);
    
    $sql="UPDATE countries set currency_code='ZWD' WHERE iso_code2='ZW'";
    $con->execute($sql);

} //end check for currency codes

//add division_id to opportunities table (for use in scoping opportunities by division)
$sql = "ALTER TABLE `opportunities` ADD `division_id` INT UNSIGNED AFTER `company_id`";
$con->execute($sql);

//add division_id to cases table (for use in scoping cases by division)
$sql="ALTER TABLE `cases` ADD `division_id` INT UNSIGNED AFTER `company_id`";
$con->execute($sql);

//add address_id to the company_division table (for use in assigning addresses to a division)
$sql="ALTER TABLE `company_division` ADD `address_id` INT UNSIGNED AFTER `company_id`";
$con->execute($sql);

//close the database connection, because we don't need it anymore
$con->close();

$page_title = _("Update Complete");
start_page($page_title, true, $msg);

echo $msg;
?>

<BR>
<?php echo _("Your database has been updated."); ?>
<BR><BR>



<?php

end_page();

/**
 * $Log: update.php,v $
 * Revision 1.48  2005/01/11 17:08:39  maulani
 * - Added parameter for LDAP Version.  Some LDAP Version 3 installations
 *   require this option to be set.  Initial parameter setting is version 2
 *   since most current installations probably use v2.
 *
 * Revision 1.47  2005/01/10 21:47:12  braverock
 * - make activity_description a nullable field
 *
 * Revision 1.46  2005/01/06 21:48:19  vanmer
 * - added address_id to company_division table, for use in specifying addresses for divisions
 *
 * Revision 1.45  2005/01/06 20:44:24  vanmer
 * - added optional division_id to cases and opportunities
 *
 * Revision 1.44  2004/12/31 17:57:20  braverock
 * - added description column to case_statuses to match opportunity_statuses
 *
 * Revision 1.43  2004/12/07 22:24:02  vanmer
 * - added missing fields to relationship_types
 *
 * Revision 1.42  2004/12/07 21:27:13  vanmer
 * - added field relationship_status to relationship_type table, since it is missing on older installs
 * - added currency_code field to keep track of currencies for a country
 * - added currencies for known countries
 *
 * Revision 1.41  2004/09/16 21:52:54  vanmer
 * -removed ALTER table to add a key in time_zones, as this is done differently later in the code
 *
 * Revision 1.40  2004/09/16 19:49:23  vanmer
 * -added ALTER sql to add missing KEY for province in time_zones
 *
 * Revision 1.39  2004/09/06 12:23:00  braverock
 * - add sort_order to case statuses
 *
 * Revision 1.38  2004/09/02 22:27:08  maulani
 * - Add status_open_indicator to opportunity_statuses and case_statuses tables
 * - Correct spelling
 *
 * Revision 1.37  2004/09/02 18:29:02  maulani
 * - Add status_open_indicator to opportunity_statuses table.  Field was
 *   in install but never added to update.
 *
 * Revision 1.36  2004/09/02 15:09:53  neildogg
 * - Index added on update
 *
 * Revision 1.35  2004/09/02 14:51:13  neildogg
 * - Removed additional indexes
 *  - as some were unused and some were duplicates
 *  - added province index to time_zones
 *  - as it is utilized in updated misc util
 *
 * Revision 1.34  2004/09/02 14:21:31  maulani
 * - Add indexes to speed up time zone assignment
 * - Reduce scope of selection to speed up time zone assignment
 *
 * Revision 1.33  2004/08/23 13:49:57  neildogg
 * - Properly updates daylight savings
 *  - in addresses
 *  - May take a long time on large systems
 *
 * Revision 1.32  2004/08/19 21:49:53  neildogg
 * - Adds field to activity templates for default text
 *
 * Revision 1.31  2004/08/16 16:08:45  neildogg
 * - Updates addresses to daylight savings
 *  - (will work for future time_zone database additions)
 *
 * Revision 1.30  2004/08/04 20:46:06  introspectshun
 * - Pass table name to GetInsertSQL
 *
 * Revision 1.29  2004/08/03 15:47:06  neildogg
 * - Now changes are actually being executed
 *
 * Revision 1.28  2004/08/03 15:14:45  neildogg
 * - Added initial time zone/daylight savings information
 *
 * Revision 1.27  2004/08/02 08:31:31  maulani
 * - Create Activities Default Behavior system parameter.  Replaces vars.php
 *   variable $activities_default_behavior
 *
 * Revision 1.26  2004/07/28 20:40:45  neildogg
 * - Added field recent_action to recent_items
 *  - Same function works transparently
 *  - Current items have recent_action=''
 *  - update_recent_items has new optional parameter
 *
 * Revision 1.25  2004/07/28 11:50:19  braverock
 * - add sort order to opportunity statuses
 *
 * Revision 1.24  2004/07/21 20:30:18  neildogg
 * - Added saved_actions table
 *
 * Revision 1.23  2004/07/16 18:52:43  cpsource
 * - Add role check inside of session_check
 *
 * Revision 1.22  2004/07/16 13:52:00  braverock
 * - localize strings for i18n translation support
 *   - applies modified patches from Sebastian Becker (hyperpac)
 *
 * Revision 1.21  2004/07/15 21:26:20  maulani
 * - Add Audit Level as a system parameter
 *
 * Revision 1.20  2004/07/13 18:15:44  neildogg
 * - Add database entries to allow a contact to be tied to the user
 *
 * Revision 1.19  2004/07/07 20:48:16  neildogg
 * - Added database structure changes
 *
 * Revision 1.18  2004/07/01 20:14:28  braverock
 * - changed relationship update script to avoid duplicate entries and correct  from/to order
 *
 * Revision 1.17  2004/07/01 19:48:09  braverock
 * - add new configurable relationships code
 *   - adapted from patches submitted by Neil Roberts
 *
 * Revision 1.16  2004/07/01 15:23:06  braverock
 * - update default data for relationship_types table
 * - use NAMES -> VALUES SQL construction to be safe
 *
 * Revision 1.15  2004/07/01 12:56:33  braverock
 * - add relationships and relationship_types tables and data to install and update
 *
 * Revision 1.14  2004/06/28 14:30:01  maulani
 * - add address format strings for many countries
 *
 * Revision 1.13  2004/06/26 13:11:29  braverock
 * - execute sql for sort order on activity types
 *   - applies SF patch #979564 by Marc Spoorendonk (grmbl)
 *
 * Revision 1.12  2004/06/14 18:13:51  introspectshun
 * - Add adodb-params.php include for multi-db compatibility.
 * - Now use ADODB GetInsertSQL, GetUpdateSQL functions.
 *
 * Revision 1.11  2004/06/13 09:13:20  braverock
 * - add sort_order to activity_types
 *
 * Revision 1.10  2004/06/04 14:53:48  braverock
 * - change activity_templates duration to varchar for advanced date functionality
 *
 * Revision 1.9  2004/06/03 16:14:56  braverock
 * - add functionality to support workflow and activity templates
 *   - functionality contributed by Brad Marshall
 *
 * Revision 1.8  2004/05/04 23:48:02  maulani
 * - Added a system parameters table to the database.  This table can be used
 *   for items that would otherwise be dumped into the vars.php file. These
 *   include config items that are not required for database connectivity nor
 *   have access speed performance implications.  Accessor and setor functions
 *   added to utils-misc.
 * - Still need to create editing screen in admin section
 *
 * Revision 1.7  2004/04/25 23:09:56  braverock
 * add division_id alter table command to resolve problems from upgrading from 12Jan
 *
 * Revision 1.6  2004/04/23 17:11:41  gpowers
 * Removed http_user_agent from audit_items table. It is space consuming and
 * redundant, as most httpd servers can be configured to log this information.
 *
 * If anyone has run the previsous version of this script, no harm will be
 * done, they will just have an extra column in the audit table. But, I wanted
 * to patch this ASAP, to minize the number of people who might run it.
 *
 * Revision 1.5  2004/04/23 16:00:53  gpowers
 * Removed addresses.line3 - this was not an approved change
 * Added comments telling the reasons for the changes
 *
 * Revision 1.4  2004/04/23 15:07:29  gpowers
 * added addresses.line, campaign_statuses.status_open_indicator, audit_items.remote_addr, audit_items.remote_port, audit_items.session_id, audit_items.http_user_agent
 *
 * Revision 1.3  2004/04/13 15:47:12  maulani
 * - add data integrity check so all companies have addresses
 *
 * Revision 1.2  2004/04/13 15:06:41  maulani
 * - Add active contact data integrity check to database cleanup
 *
 * Revision 1.1  2004/04/12 18:59:01  maulani
 * - Make database structure and data cleanup available withing Admin interface
 *
 * Revision 1.7  2004/04/13 12:29:20  maulani
 * - Move the data clean and update files into the admin section of XRMS
 *
 * Revision 1.6  2004/04/12 14:34:02  maulani
 * - Add indexes for foreign key company_id
 *
 * Revision 1.5  2004/03/26 16:17:00  maulani
 * - Cleanup formatting
 *
 * Revision 1.3  2004/03/23 14:34:05  braverock
 * - add check for result set before closing rst
 *
 * Revision 1.2  2004/03/22 02:05:08  braverock
 * - add case_priority_score_adjustment to fix SF bug 906413
 *
 * Revision 1.1  2004/03/18 01:07:18  maulani
 * - Create installation tests to check whether the include location and
 *   vars.php have been configured.
 * - Create PHP-based database installation to replace old SQL scripts
 * - Create PHP-update routine to update users to latest schema/data as
 *   XRMS evolves.
 *
 */
?>
