-- MySQL dump 8.23
--
-- Host: localhost    Database: test_xrms
---------------------------------------------------------
-- Server version	3.23.58

--
-- Table structure for table `account_statuses`
--

CREATE TABLE account_statuses (
  account_status_id int(11) NOT NULL auto_increment,
  account_status_short_name varchar(10) NOT NULL default '',
  account_status_pretty_name varchar(100) NOT NULL default '',
  account_status_pretty_plural varchar(100) NOT NULL default '',
  account_status_display_html varchar(100) NOT NULL default '',
  account_status_record_status char(1) default 'a',
  PRIMARY KEY  (account_status_id)
) TYPE=MyISAM;

--
-- Dumping data for table `account_statuses`
--


INSERT INTO account_statuses VALUES (1,'N/A','N/A','N/A','<font color=#999999><b>N/A</b></font>','a');
INSERT INTO account_statuses VALUES (2,'Closed','Closed','Closed','<font color=#cc0000><b>Closed</b></font>','a');
INSERT INTO account_statuses VALUES (3,'Hold','Hold','Hold','<font color=#ff9933><b>Hold</b></font>','a');
INSERT INTO account_statuses VALUES (4,'Approved','Approved','Approved','<font color=#009900><b>Approved</b></font>','a');

--
-- Table structure for table `activities`
--

CREATE TABLE activities (
  activity_id int(11) NOT NULL auto_increment,
  activity_type_id int(11) NOT NULL default '0',
  user_id int(11) NOT NULL default '0',
  company_id int(11) NOT NULL default '0',
  contact_id int(11) NOT NULL default '0',
  on_what_table varchar(100) NOT NULL default '',
  on_what_id int(11) NOT NULL default '0',
  on_what_status int(11) NOT NULL default '0',
  activity_title varchar(100) NOT NULL default '',
  activity_description text NOT NULL,
  entered_at datetime default NULL,
  entered_by int(11) NOT NULL default '0',
  scheduled_at datetime default NULL,
  ends_at datetime default NULL,
  completed_at datetime default NULL,
  activity_status char(1) default 'o',
  activity_record_status char(1) default 'a',
  PRIMARY KEY  (activity_id)
) TYPE=MyISAM;

--
-- Dumping data for table `activities`
--



--
-- Table structure for table `activity_templates`
--

CREATE TABLE activity_templates (
  activity_template_id int(11) NOT NULL auto_increment,
  role_id int(11) NOT NULL default '0',
  activity_type_id int(11) NOT NULL default '0',
  on_what_table varchar(100) NOT NULL default '',
  on_what_id int(11) NOT NULL default '0',
  activity_title varchar(100) NOT NULL default '',
  activity_description text NOT NULL,
  default_text text,
  duration varchar(20) NOT NULL default '1',
  sort_order tinyint(4) NOT NULL default '1',
  activity_template_record_status char(1) NOT NULL default 'a',
  PRIMARY KEY  (activity_template_id)
) TYPE=MyISAM;

--
-- Dumping data for table `activity_templates`
--



--
-- Table structure for table `activity_types`
--

CREATE TABLE activity_types (
  activity_type_id int(11) NOT NULL auto_increment,
  activity_type_short_name varchar(10) NOT NULL default '',
  activity_type_pretty_name varchar(100) NOT NULL default '',
  activity_type_pretty_plural varchar(100) NOT NULL default '',
  activity_type_display_html varchar(100) NOT NULL default '',
  activity_type_score_adjustment int(11) NOT NULL default '0',
  activity_type_record_status char(1) NOT NULL default 'a',
  sort_order tinyint(4) NOT NULL default '1',
  PRIMARY KEY  (activity_type_id)
) TYPE=MyISAM;

--
-- Dumping data for table `activity_types`
--


INSERT INTO activity_types VALUES (1,'CTO','call to','calls to','call to',0,'a',1);
INSERT INTO activity_types VALUES (2,'CFR','call from','calls from','call from',0,'a',2);
INSERT INTO activity_types VALUES (3,'ETO','e-mail to','e-mails to','e-mail to',0,'a',3);
INSERT INTO activity_types VALUES (4,'EFR','e-mail from','e-mails from','e-mail from',0,'a',4);
INSERT INTO activity_types VALUES (5,'FTO','fax to','faxes to','fax to',0,'a',5);
INSERT INTO activity_types VALUES (6,'FFR','fax from','faxes from','fax from',0,'a',6);
INSERT INTO activity_types VALUES (7,'LTT','letter to','letter to','letter to',0,'a',7);
INSERT INTO activity_types VALUES (8,'LTF','letter from','letter from','letter from',0,'a',8);
INSERT INTO activity_types VALUES (9,'INT','internal','internal','internal',0,'a',9);
INSERT INTO activity_types VALUES (10,'PRO','process','process','process',0,'a',10);

--
-- Table structure for table `address_format_strings`
--

CREATE TABLE address_format_strings (
  address_format_string_id int(11) NOT NULL auto_increment,
  address_format_string varchar(255) default NULL,
  address_format_string_record_status char(1) NOT NULL default 'a',
  PRIMARY KEY  (address_format_string_id)
) TYPE=MyISAM;

--
-- Dumping data for table `address_format_strings`
--


INSERT INTO address_format_strings VALUES (1,'$lines<br>$city, $province $postal_code<br>$country','a');
INSERT INTO address_format_strings VALUES (2,'$lines<br>$postal_code $city<br>$province<br>$country','a');
INSERT INTO address_format_strings VALUES (3,'$lines<br>$postal_code $city $province<br>$country','a');
INSERT INTO address_format_strings VALUES (4,'$lines<br>$city $province $postal_code<br>$country','a');
INSERT INTO address_format_strings VALUES (5,'$lines<br>$postal_code $province<br>$city<br>$country','a');
INSERT INTO address_format_strings VALUES (6,'$lines<br>$postal_code $city<br>$country','a');
INSERT INTO address_format_strings VALUES (7,'$postal_code $city<br>$lines<br>$country','a');
INSERT INTO address_format_strings VALUES (8,'$lines<br>$province<br>$city $postal_code<br>$country','a');
INSERT INTO address_format_strings VALUES (9,'$lines<br>$city<br>$province $postal_code<br>$country','a');
INSERT INTO address_format_strings VALUES (10,'$postal_code<br>$province $city<br>$lines<br>$country','a');
INSERT INTO address_format_strings VALUES (11,'$lines<br>$city $province<br>$postal_code<br>$country','a');
INSERT INTO address_format_strings VALUES (12,'$country $postal_code<br>$province $city<br>$lines','a');
INSERT INTO address_format_strings VALUES (13,'$lines<br>$city<br>$province<br>$postal_code<br>$country','a');
INSERT INTO address_format_strings VALUES (14,'$lines<br>$city $postal_code<br>$country','a');
INSERT INTO address_format_strings VALUES (15,'$lines<br>$city, $province $postal_code<br>$country','a');

--
-- Table structure for table `addresses`
--

CREATE TABLE addresses (
  address_id int(11) NOT NULL auto_increment,
  company_id int(11) NOT NULL default '0',
  country_id int(11) NOT NULL default '1',
  address_name varchar(100) NOT NULL default '',
  address_body varchar(255) NOT NULL default '',
  line1 varchar(255) NOT NULL default '',
  line2 varchar(255) NOT NULL default '',
  city varchar(255) NOT NULL default '',
  province varchar(255) NOT NULL default '',
  postal_code varchar(255) NOT NULL default '',
  use_pretty_address char(1) NOT NULL default 'f',
  offset float default NULL,
  daylight_savings_id int(10) unsigned default NULL,
  address_record_status char(1) NOT NULL default 'a',
  PRIMARY KEY  (address_id),
  KEY company_id (company_id),
  KEY city (city),
  KEY province (province),
  KEY address_record_status (address_record_status)
) TYPE=MyISAM;

--
-- Dumping data for table `addresses`
--


INSERT INTO addresses VALUES (1,1,1,'Address 1','3201 West Rolling Hills Circle\nFt. Lauderdale, FL 33328\nUSA','3201 West Rolling Hills Circle','','Ft. Lauderdale','FL','33328','f',NULL,NULL,'a');
INSERT INTO addresses VALUES (2,2,1,'Address 2','11 Platinum Drive\nLos Angeles, CA 90001\nUSA','11 Platinum Drive','','Los Angeles','CA','90001','f',NULL,NULL,'a');
INSERT INTO addresses VALUES (3,3,1,'Address 3','123 Main Street\nSuite 100\nSandusky, OH 44870\nUSA','123 Main Street','Suite 100','Sandusky','OH','44870','f',NULL,NULL,'a');

--
-- Table structure for table `audit_items`
--

CREATE TABLE audit_items (
  audit_item_id int(11) NOT NULL auto_increment,
  user_id int(11) NOT NULL default '0',
  audit_item_type varchar(50) default '',
  on_what_table varchar(100) default '',
  on_what_id varchar(10) default '',
  audit_item_timestamp datetime default NULL,
  audit_item_record_status char(1) default 'a',
  PRIMARY KEY  (audit_item_id)
) TYPE=MyISAM;

--
-- Dumping data for table `audit_items`
--



--
-- Table structure for table `campaign_statuses`
--

CREATE TABLE campaign_statuses (
  campaign_status_id int(11) NOT NULL auto_increment,
  campaign_status_short_name varchar(10) NOT NULL default '',
  campaign_status_pretty_name varchar(100) NOT NULL default '',
  campaign_status_pretty_plural varchar(100) NOT NULL default '',
  campaign_status_display_html varchar(100) NOT NULL default '',
  campaign_status_record_status char(3) NOT NULL default 'a',
  PRIMARY KEY  (campaign_status_id)
) TYPE=MyISAM;

--
-- Dumping data for table `campaign_statuses`
--


INSERT INTO campaign_statuses VALUES (1,'NEW','New','New','New','a');
INSERT INTO campaign_statuses VALUES (2,'PLAN','Planning','Planning','Planning','a');
INSERT INTO campaign_statuses VALUES (3,'ACT','Active','Active','Active','a');
INSERT INTO campaign_statuses VALUES (4,'CLO','Closed','Closed','Closed','a');

--
-- Table structure for table `campaign_types`
--

CREATE TABLE campaign_types (
  campaign_type_id int(11) NOT NULL auto_increment,
  campaign_type_short_name varchar(10) NOT NULL default '',
  campaign_type_pretty_name varchar(100) NOT NULL default '',
  campaign_type_pretty_plural varchar(100) NOT NULL default '',
  campaign_type_display_html varchar(100) NOT NULL default '',
  campaign_type_record_status char(3) NOT NULL default 'a',
  PRIMARY KEY  (campaign_type_id)
) TYPE=MyISAM;

--
-- Dumping data for table `campaign_types`
--


INSERT INTO campaign_types VALUES (1,'OTH','Other','Other','Other','a');
INSERT INTO campaign_types VALUES (2,'EML','E-Mail','E-Mail','E-Mail','a');
INSERT INTO campaign_types VALUES (3,'TEL','Phone','Phone','Phone','a');
INSERT INTO campaign_types VALUES (4,'MAIL','Mail','Mail','Mail','a');
INSERT INTO campaign_types VALUES (5,'EVT','Event','Event','Event','a');
INSERT INTO campaign_types VALUES (6,'MAG','Magazine','Magazine','Magazine','a');
INSERT INTO campaign_types VALUES (7,'TV','Television','Television','Television','a');

--
-- Table structure for table `campaigns`
--

CREATE TABLE campaigns (
  campaign_id int(11) NOT NULL auto_increment,
  campaign_type_id int(11) NOT NULL default '0',
  campaign_status_id int(11) NOT NULL default '0',
  user_id int(11) NOT NULL default '0',
  campaign_title varchar(100) NOT NULL default '',
  campaign_description text NOT NULL,
  starts_at datetime default NULL,
  ends_at datetime default NULL,
  cost decimal(8,2) NOT NULL default '0.01',
  entered_at datetime default NULL,
  entered_by int(11) NOT NULL default '0',
  last_modified_at datetime default NULL,
  last_modified_by int(11) NOT NULL default '0',
  campaign_record_status char(1) NOT NULL default 'a',
  PRIMARY KEY  (campaign_id)
) TYPE=MyISAM;

--
-- Dumping data for table `campaigns`
--



--
-- Table structure for table `case_priorities`
--

CREATE TABLE case_priorities (
  case_priority_id int(11) NOT NULL auto_increment,
  case_priority_short_name varchar(10) NOT NULL default '',
  case_priority_pretty_name varchar(100) NOT NULL default '',
  case_priority_pretty_plural varchar(100) NOT NULL default '',
  case_priority_display_html varchar(100) NOT NULL default '',
  case_priority_score_adjustment int(11) NOT NULL default '0',
  case_priority_record_status char(1) NOT NULL default 'a',
  PRIMARY KEY  (case_priority_id)
) TYPE=MyISAM;

--
-- Dumping data for table `case_priorities`
--


INSERT INTO case_priorities VALUES (1,'CRIT','Critical','Critical','Critical',0,'a');
INSERT INTO case_priorities VALUES (2,'HIGH','High','High','High',0,'a');
INSERT INTO case_priorities VALUES (3,'MED','Medium','Medium','Medium',0,'a');
INSERT INTO case_priorities VALUES (4,'LOW','Low','Low','Low',0,'a');

--
-- Table structure for table `case_statuses`
--

CREATE TABLE case_statuses (
  case_status_id int(11) NOT NULL auto_increment,
  sort_order tinyint(4) NOT NULL default '1',
  status_open_indicator char(1) NOT NULL default 'o',
  case_status_short_name varchar(10) NOT NULL default '',
  case_status_pretty_name varchar(100) NOT NULL default '',
  case_status_pretty_plural varchar(100) NOT NULL default '',
  case_status_display_html varchar(100) NOT NULL default '',
  case_status_long_desc varchar(200) NOT NULL default '',
  case_type_id int(11) NOT NULL default '1',
  case_status_record_status char(1) NOT NULL default 'a',
  PRIMARY KEY  (case_status_id)
) TYPE=MyISAM;

--
-- Dumping data for table `case_statuses`
--


INSERT INTO case_statuses VALUES (1,1,'o','NEW','New','New','New','',1,'a');
INSERT INTO case_statuses VALUES (2,2,'o','OPEN','Open','Open','Open','',1,'a');
INSERT INTO case_statuses VALUES (3,3,'o','PRO','In Progress','In Progress','In Progress','',1,'a');
INSERT INTO case_statuses VALUES (4,4,'c','FIN','Finished','Finished','Finished','',1,'a');
INSERT INTO case_statuses VALUES (5,5,'c','CLO','Closed','Closed','Closed','',1,'a');

--
-- Table structure for table `case_types`
--

CREATE TABLE case_types (
  case_type_id int(11) NOT NULL auto_increment,
  case_type_short_name varchar(10) NOT NULL default '',
  case_type_pretty_name varchar(100) NOT NULL default '',
  case_type_pretty_plural varchar(100) NOT NULL default '',
  case_type_display_html varchar(100) NOT NULL default '',
  case_type_record_status char(1) NOT NULL default 'a',
  PRIMARY KEY  (case_type_id)
) TYPE=MyISAM;

--
-- Dumping data for table `case_types`
--


INSERT INTO case_types VALUES (1,'HELP','Help Item','Help Items','Help Item','a');
INSERT INTO case_types VALUES (2,'BUG','Bug','Bugs','Bug','a');
INSERT INTO case_types VALUES (3,'RFE','Feature Request','Feature Requests','Feature Request','a');

--
-- Table structure for table `cases`
--

CREATE TABLE cases (
  case_id int(11) NOT NULL auto_increment,
  case_type_id int(11) NOT NULL default '0',
  case_status_id int(11) NOT NULL default '0',
  case_priority_id int(11) NOT NULL default '0',
  company_id int(11) NOT NULL default '0',
  division_id int(11) default NULL,
  contact_id int(11) NOT NULL default '0',
  user_id int(11) NOT NULL default '0',
  priority int(11) NOT NULL default '0',
  case_title varchar(100) NOT NULL default '',
  case_description text NOT NULL,
  due_at datetime default NULL,
  entered_at datetime default NULL,
  entered_by int(11) NOT NULL default '0',
  last_modified_at datetime default NULL,
  last_modified_by int(11) NOT NULL default '0',
  owned_at datetime default NULL,
  owned_by int(11) NOT NULL default '0',
  closed_at datetime default NULL,
  closed_by int(11) NOT NULL default '0',
  case_record_status char(1) NOT NULL default 'a',
  PRIMARY KEY  (case_id)
) TYPE=MyISAM;

--
-- Dumping data for table `cases`
--



--
-- Table structure for table `categories`
--

CREATE TABLE categories (
  category_id int(11) NOT NULL auto_increment,
  category_short_name varchar(10) NOT NULL default '',
  category_pretty_name varchar(100) NOT NULL default '',
  category_pretty_plural varchar(100) NOT NULL default '',
  category_display_html varchar(100) NOT NULL default '',
  category_record_status char(1) NOT NULL default 'a',
  PRIMARY KEY  (category_id)
) TYPE=MyISAM;

--
-- Dumping data for table `categories`
--


INSERT INTO categories VALUES (1,'TEST1','Test Category 1','Test Category 1','Test Category 1','a');
INSERT INTO categories VALUES (2,'TEST2','Test Category 2','Test Category 2','Test Category 2','a');
INSERT INTO categories VALUES (3,'TEST3','Test Category 3','Test Category 3','Test Category 3','a');

--
-- Table structure for table `category_category_scope_map`
--

CREATE TABLE category_category_scope_map (
  category_id int(11) NOT NULL default '0',
  category_scope_id int(11) NOT NULL default '0'
) TYPE=MyISAM;

--
-- Dumping data for table `category_category_scope_map`
--


INSERT INTO category_category_scope_map VALUES (1,1);
INSERT INTO category_category_scope_map VALUES (1,2);
INSERT INTO category_category_scope_map VALUES (1,3);
INSERT INTO category_category_scope_map VALUES (1,4);
INSERT INTO category_category_scope_map VALUES (1,5);
INSERT INTO category_category_scope_map VALUES (2,1);
INSERT INTO category_category_scope_map VALUES (2,2);
INSERT INTO category_category_scope_map VALUES (2,3);
INSERT INTO category_category_scope_map VALUES (2,4);
INSERT INTO category_category_scope_map VALUES (2,5);
INSERT INTO category_category_scope_map VALUES (3,1);
INSERT INTO category_category_scope_map VALUES (3,2);
INSERT INTO category_category_scope_map VALUES (3,3);
INSERT INTO category_category_scope_map VALUES (3,4);
INSERT INTO category_category_scope_map VALUES (3,5);

--
-- Table structure for table `category_scopes`
--

CREATE TABLE category_scopes (
  category_scope_id int(11) NOT NULL auto_increment,
  category_scope_short_name varchar(10) NOT NULL default '',
  category_scope_pretty_name varchar(100) NOT NULL default '',
  category_scope_pretty_plural varchar(100) NOT NULL default '',
  category_scope_display_html varchar(100) NOT NULL default '',
  on_what_table varchar(100) NOT NULL default '',
  category_scope_record_status char(1) default 'a',
  PRIMARY KEY  (category_scope_id)
) TYPE=MyISAM;

--
-- Dumping data for table `category_scopes`
--


INSERT INTO category_scopes VALUES (1,'COMP','Company','Companies','Company','companies','a');
INSERT INTO category_scopes VALUES (2,'CONT','Contact','Contacts','Contact','contacts','a');
INSERT INTO category_scopes VALUES (3,'OPP','Opportunity','Opportunities','Opportunity','opportunities','a');
INSERT INTO category_scopes VALUES (4,'CASE','Case','Cases','Case','cases','a');
INSERT INTO category_scopes VALUES (5,'CAMP','Campaign','Campaigns','Campaign','campaigns','a');

--
-- Table structure for table `companies`
--

CREATE TABLE companies (
  company_id int(11) NOT NULL auto_increment,
  user_id int(11) NOT NULL default '0',
  company_source_id int(11) NOT NULL default '0',
  industry_id int(11) NOT NULL default '0',
  crm_status_id int(11) NOT NULL default '0',
  rating_id int(11) NOT NULL default '0',
  account_status_id int(11) NOT NULL default '0',
  company_name varchar(100) NOT NULL default '',
  company_code varchar(10) NOT NULL default '',
  legal_name varchar(100) NOT NULL default '',
  tax_id varchar(100) NOT NULL default '',
  profile text NOT NULL,
  phone varchar(50) NOT NULL default '',
  phone2 varchar(50) NOT NULL default '',
  fax varchar(50) NOT NULL default '',
  url varchar(50) NOT NULL default '',
  employees varchar(50) NOT NULL default '',
  revenue varchar(50) NOT NULL default '',
  credit_limit int(11) NOT NULL default '0',
  terms int(11) NOT NULL default '0',
  entered_at datetime default NULL,
  entered_by int(11) NOT NULL default '0',
  last_modified_at datetime default NULL,
  last_modified_by int(11) NOT NULL default '0',
  default_primary_address int(11) NOT NULL default '0',
  default_billing_address int(11) NOT NULL default '0',
  default_shipping_address int(11) NOT NULL default '0',
  default_payment_address int(11) NOT NULL default '0',
  custom1 varchar(100) NOT NULL default '',
  custom2 varchar(100) NOT NULL default '',
  custom3 varchar(100) NOT NULL default '',
  custom4 varchar(100) NOT NULL default '',
  extref1 varchar(50) NOT NULL default '',
  extref2 varchar(50) NOT NULL default '',
  extref3 varchar(50) NOT NULL default '',
  company_record_status char(1) default 'a',
  PRIMARY KEY  (company_id),
  KEY company_record_status (company_record_status)
) TYPE=MyISAM;

--
-- Dumping data for table `companies`
--


INSERT INTO companies VALUES (1,1,1,1,2,4,4,'Bushwood Components','BUSH01','','','(Bushwood Components is a fictitious company.)<p>This field can be used to hold a paragraph or two of text (either plain or <font color=blue><b>HTML</b></font>) about a company.','(800) 555-2000','(800) 555-2001','(800) 555-2002','http://www.bushwood.com','','',100000,10,'2003-01-01 12:00:00',1,'2003-01-01 12:00:00',1,1,1,1,1,'','','','','10090','10091','','a');
INSERT INTO companies VALUES (2,1,2,2,3,4,4,'Polymer Electronics','POLY01','','','(Polymer Electronics is a fictitious company.)<p>This field can be used to hold a paragraph or two of text (either plain or <font color=blue><b>HTML</b></font>) about a company.','(800) 555-3000','(800) 555-3001','(800) 555-3002','http://www.polymer.com','','',200000,20,'2003-01-01 12:00:00',1,'2003-01-01 12:00:00',1,2,2,2,2,'','','','','10092','10093','','a');
INSERT INTO companies VALUES (3,1,3,3,4,4,4,'Callahan Manufacturing','CALL01','','','(Callahan Manufacturing is a fictitious company.)<p>This field can be used to hold a paragraph or two of text (either plain or <font color=blue><b>HTML</b></font>) about a company.','(800) 555-4000','(800) 555-4001','(800) 555-4002','http://www.callahan.com','','',300000,30,'2003-01-01 12:00:00',1,'2003-01-01 12:00:00',1,3,3,3,3,'','','','','10094','10095','','a');

--
-- Table structure for table `company_company_type_map`
--

CREATE TABLE company_company_type_map (
  company_id int(11) NOT NULL default '0',
  company_type_id int(11) NOT NULL default '0'
) TYPE=MyISAM;

--
-- Dumping data for table `company_company_type_map`
--



--
-- Table structure for table `company_division`
--

CREATE TABLE company_division (
  division_id int(11) NOT NULL auto_increment,
  company_id int(11) NOT NULL default '0',
  address_id int(11) default NULL,
  user_id int(11) NOT NULL default '0',
  company_source_id int(11) NOT NULL default '0',
  industry_id int(11) NOT NULL default '0',
  division_name varchar(100) NOT NULL default '',
  description text NOT NULL,
  entered_at datetime default NULL,
  entered_by int(11) NOT NULL default '0',
  last_modified_at datetime default NULL,
  last_modified_by int(11) NOT NULL default '0',
  custom1 varchar(100) NOT NULL default '',
  custom2 varchar(100) NOT NULL default '',
  custom3 varchar(100) NOT NULL default '',
  custom4 varchar(100) NOT NULL default '',
  division_record_status char(1) default 'a',
  PRIMARY KEY  (division_id)
) TYPE=MyISAM;

--
-- Dumping data for table `company_division`
--



--
-- Table structure for table `company_former_names`
--

CREATE TABLE company_former_names (
  company_id int(11) NOT NULL default '0',
  namechange_at datetime NOT NULL default '0000-00-00 00:00:00',
  former_name varchar(100) NOT NULL default '',
  description varchar(100) default NULL,
  KEY company_id (company_id)
) TYPE=MyISAM;

--
-- Dumping data for table `company_former_names`
--



--
-- Table structure for table `company_relationship`
--

CREATE TABLE company_relationship (
  company_from_id int(11) NOT NULL default '0',
  relationship_type varchar(100) NOT NULL default '',
  company_to_id int(11) NOT NULL default '0',
  established_at datetime NOT NULL default '0000-00-00 00:00:00',
  KEY company_from_id (company_from_id,company_to_id)
) TYPE=MyISAM;

--
-- Dumping data for table `company_relationship`
--



--
-- Table structure for table `company_sources`
--

CREATE TABLE company_sources (
  company_source_id int(11) NOT NULL auto_increment,
  company_source_short_name varchar(10) NOT NULL default '',
  company_source_pretty_name varchar(100) NOT NULL default '',
  company_source_pretty_plural varchar(100) NOT NULL default '',
  company_source_display_html varchar(100) NOT NULL default '',
  company_source_record_status char(1) default 'a',
  company_source_score_adjustment int(11) NOT NULL default '0',
  PRIMARY KEY  (company_source_id)
) TYPE=MyISAM;

--
-- Dumping data for table `company_sources`
--


INSERT INTO company_sources VALUES (1,'OTH','Other','Other','Other','a',0);
INSERT INTO company_sources VALUES (2,'ADV','Advertisement','Advertisements','Advertisement','a',0);
INSERT INTO company_sources VALUES (3,'DM','Direct Mail','Direct Mail','Direct Mail','a',0);
INSERT INTO company_sources VALUES (4,'RAD','Radio','Radio','Radio','a',0);
INSERT INTO company_sources VALUES (5,'SE','Search Engine','Search Engines','Search Engine','a',0);
INSERT INTO company_sources VALUES (6,'SEM','Seminar','Seminars','Seminar','a',0);
INSERT INTO company_sources VALUES (7,'TEL','Telemarketing','Telemarketings','Telemarketing','a',0);
INSERT INTO company_sources VALUES (8,'TRD','Trade Show','Trade Shows','Trade Show','a',0);
INSERT INTO company_sources VALUES (9,'WEB','Web Site','Web Sites','Web Site','a',0);
INSERT INTO company_sources VALUES (10,'WOM','Word of Mouth','Word of Mouth','Word of Mouth','a',0);

--
-- Table structure for table `company_types`
--

CREATE TABLE company_types (
  company_type_id int(11) NOT NULL auto_increment,
  company_type_short_name varchar(10) NOT NULL default '',
  company_type_pretty_name varchar(100) NOT NULL default '',
  company_type_pretty_plural varchar(100) NOT NULL default '',
  company_type_display_html varchar(100) NOT NULL default '',
  company_type_record_status char(1) default 'a',
  PRIMARY KEY  (company_type_id)
) TYPE=MyISAM;

--
-- Dumping data for table `company_types`
--


INSERT INTO company_types VALUES (1,'CUST','Customer','Customers','Customer','a');
INSERT INTO company_types VALUES (2,'VEND','Vendor','Vendors','Vendor','a');
INSERT INTO company_types VALUES (3,'PART','Partner','Partners','Partner','a');
INSERT INTO company_types VALUES (4,'COMP','Competitor','Competitors','Competitor','a');
INSERT INTO company_types VALUES (5,'SPEC','Special','Special','Special','a');

--
-- Table structure for table `contacts`
--

CREATE TABLE contacts (
  contact_id int(11) NOT NULL auto_increment,
  company_id int(11) NOT NULL default '0',
  division_id int(11) NOT NULL default '0',
  address_id int(11) NOT NULL default '0',
  salutation varchar(20) NOT NULL default '',
  last_name varchar(100) NOT NULL default '',
  first_names varchar(100) NOT NULL default '',
  gender char(1) NOT NULL default 'u',
  date_of_birth varchar(100) NOT NULL default '',
  summary varchar(100) NOT NULL default '',
  title varchar(100) NOT NULL default '',
  description varchar(100) NOT NULL default '',
  email varchar(100) NOT NULL default '',
  email_status char(1) default 'a',
  work_phone varchar(50) NOT NULL default '',
  cell_phone varchar(50) NOT NULL default '',
  home_phone varchar(50) NOT NULL default '',
  fax varchar(50) NOT NULL default '',
  aol_name varchar(50) NOT NULL default '',
  yahoo_name varchar(50) NOT NULL default '',
  msn_name varchar(50) NOT NULL default '',
  interests varchar(50) NOT NULL default '',
  profile text NOT NULL,
  custom1 varchar(50) NOT NULL default '',
  custom2 varchar(50) NOT NULL default '',
  custom3 varchar(50) NOT NULL default '',
  custom4 varchar(50) NOT NULL default '',
  entered_at datetime default NULL,
  entered_by int(11) NOT NULL default '0',
  last_modified_at datetime default NULL,
  last_modified_by int(11) NOT NULL default '0',
  contact_record_status char(1) NOT NULL default 'a',
  PRIMARY KEY  (contact_id),
  KEY company_id (company_id),
  KEY contact_record_status (contact_record_status)
) TYPE=MyISAM;

--
-- Dumping data for table `contacts`
--


INSERT INTO contacts VALUES (1,1,0,1,'','Webb','Ty','u','','1/2 owner','Account Manager','dad never liked us','twebb@bushwoodcc.com','a','(555) 555-2100','','','','twebb','twebb','twebb','','','','','','','2003-01-01 12:00:00',1,'2003-01-01 12:00:00',1,'a');
INSERT INTO contacts VALUES (2,1,0,1,'','Spackler','Carl','u','','do not call','Assistant Greenskeeper','to the bejeezus belt!','cspackler@bushwoodcc.com','a','(555) 555-2200','','','','cspackler','cspackler','cspackler','','','','','','','2003-01-01 12:00:00',1,'2003-01-01 12:00:00',1,'a');
INSERT INTO contacts VALUES (3,2,0,2,'','Fufkin','Artie','u','','','Director','','artie@polymer.com','a','(555) 555-3100','','','','artie','artie','artie','','','','','','','2003-01-01 12:00:00',1,'2003-01-01 12:00:00',1,'a');
INSERT INTO contacts VALUES (4,2,0,2,'','Smalls','Derek','u','','','Bass','','derek@polymer.com','a','(555) 555-3200','','','','derek','derek','derek','','','','','','','2003-01-01 12:00:00',1,'2003-01-01 12:00:00',1,'a');
INSERT INTO contacts VALUES (5,3,0,3,'','Callahan','Tommy','u','','works nights','President/CEO','','tommy@callahan.com','a','(555) 555-4100','','','','tcallahan','tcallahan','tcallahan','','','','','','','2003-01-01 12:00:00',1,'2003-01-01 12:00:00',1,'a');
INSERT INTO contacts VALUES (6,3,0,3,'','Hayden','Richard','u','','good contact','Buyer','All Lines','richard@callahan.com','a','(555) 555-4200','','','','rhayden','rhayden','rhayden','','','','','','','2003-01-01 12:00:00',1,'2003-01-01 12:00:00',1,'a');

--
-- Table structure for table `countries`
--

CREATE TABLE countries (
  country_id int(11) NOT NULL auto_increment,
  address_format_string_id int(11) NOT NULL default '1',
  country_name varchar(100) NOT NULL default '',
  un_code varchar(50) NOT NULL default '',
  iso_code1 varchar(50) NOT NULL default '',
  iso_code2 varchar(50) NOT NULL default '',
  iso_code3 varchar(50) NOT NULL default '',
  telephone_code varchar(50) NOT NULL default '',
  country_record_status char(1) NOT NULL default 'a',
  phone_format varchar(25) NOT NULL default '',
  PRIMARY KEY  (country_id)
) TYPE=MyISAM;

--
-- Dumping data for table `countries`
--


INSERT INTO countries VALUES (1,1,'','','','','','','a','');
INSERT INTO countries VALUES (2,1,'Afghanistan','004','','AF','AFG','93','a','');
INSERT INTO countries VALUES (3,1,'Albania','008','','AL','ALB','355','a','');
INSERT INTO countries VALUES (4,1,'Algeria','012','','DZ','DZA','213','a','');
INSERT INTO countries VALUES (5,1,'American Samoa','016','','AS','ASM','684','a','');
INSERT INTO countries VALUES (6,1,'Andorra','020','','AD','AND','376','a','');
INSERT INTO countries VALUES (7,1,'Angola','024','','AO','AGO','244','a','');
INSERT INTO countries VALUES (8,1,'Anguilla','660','','AI','AIA','1 264','a','');
INSERT INTO countries VALUES (9,1,'Antarctica','010','','AQ','ATA','672','a','');
INSERT INTO countries VALUES (10,1,'Antigua and Barbuda','028','','AG','ATG','1 268','a','');
INSERT INTO countries VALUES (11,2,'Argentina','032','','AR','ARG','54','a','');
INSERT INTO countries VALUES (12,1,'Armenia','051','','AM','ARM','374','a','');
INSERT INTO countries VALUES (13,1,'Aruba','533','','AW','ABW','297','a','');
INSERT INTO countries VALUES (14,4,'Australia','036','','AU','AUS','61','a','');
INSERT INTO countries VALUES (15,6,'Austria','040','','AT','AUT','43','a','');
INSERT INTO countries VALUES (16,1,'Azerbaijan','031','','AZ','AZE','994','a','');
INSERT INTO countries VALUES (17,1,'Bahamas','044','','BS','BHS','1 242','a','');
INSERT INTO countries VALUES (18,6,'Bahrain','048','','BH','BHR','973','a','');
INSERT INTO countries VALUES (19,1,'Bangladesh','050','','BD','BGD','880','a','');
INSERT INTO countries VALUES (20,1,'Barbados','052','','BB','BRB','1 246','a','');
INSERT INTO countries VALUES (21,1,'Belarus','112','','BY','BLR','375','a','');
INSERT INTO countries VALUES (22,6,'Belgium','056','','BE','BEL','32','a','');
INSERT INTO countries VALUES (23,1,'Belize','084','','BZ','BLZ','501','a','');
INSERT INTO countries VALUES (24,1,'Benin','204','','BJ','BEN','229','a','');
INSERT INTO countries VALUES (25,1,'Bermuda','060','','BM','BMU','1 441','a','');
INSERT INTO countries VALUES (26,1,'Bhutan','064','','BT','BTN','975','a','');
INSERT INTO countries VALUES (27,1,'Bolivia','068','','BO','BOL','591','a','');
INSERT INTO countries VALUES (28,6,'Bosnia and Herzegovina','070','','BA','BIH','387','a','');
INSERT INTO countries VALUES (29,1,'Botswana','072','','BW','BWA','267','a','');
INSERT INTO countries VALUES (30,3,'Brazil','076','','BR','BRA','55','a','');
INSERT INTO countries VALUES (31,1,'British Virgin Islands','092','','VG','VGB','1 284','a','');
INSERT INTO countries VALUES (32,1,'Brunei Darussalam','096','','BN','BRN','673','a','');
INSERT INTO countries VALUES (33,6,'Bulgaria','100','','BG','BGR','359','a','');
INSERT INTO countries VALUES (34,1,'Burkina Faso','854','','BF','BFA','226','a','');
INSERT INTO countries VALUES (35,1,'Burundi','108','','BI','BDI','257','a','');
INSERT INTO countries VALUES (36,1,'Cambodia','116','','KH','KHM','855','a','');
INSERT INTO countries VALUES (37,1,'Cameroon','120','','CM','CMR','237','a','');
INSERT INTO countries VALUES (38,4,'Canada','124','','CA','CAN','1','a','');
INSERT INTO countries VALUES (39,1,'Cape Verde','132','','CV','CPV','238','a','');
INSERT INTO countries VALUES (40,1,'Cayman Islands','136','','KY','CYM','1 345','a','');
INSERT INTO countries VALUES (41,1,'Central African Republic','140','','CF','CAF','236','a','');
INSERT INTO countries VALUES (42,1,'Chad','148','','TD','TCD','235','a','');
INSERT INTO countries VALUES (43,1,'Chile','152','','CL','CHL','56','a','');
INSERT INTO countries VALUES (44,3,'China','156','','CN','CHN','86','a','');
INSERT INTO countries VALUES (45,1,'Christmas Island','162','','CX','CXR','61','a','');
INSERT INTO countries VALUES (46,1,'Cocos (Keeling) Islands','166','','CC','CCK','61','a','');
INSERT INTO countries VALUES (47,1,'Colombia','170','','CO','COL','57','a','');
INSERT INTO countries VALUES (48,1,'Comoros','174','','KM','COM','269','a','');
INSERT INTO countries VALUES (49,1,'Congo','178','','CG','COG','242','a','');
INSERT INTO countries VALUES (50,1,'Cook Islands','184','','CK','COK','682','a','');
INSERT INTO countries VALUES (51,1,'Costa Rica','188','','CR','CRI','506','a','');
INSERT INTO countries VALUES (52,1,'Côte d’Ivoire','384','','CI','CIV','225','a','');
INSERT INTO countries VALUES (53,6,'Croatia','191','','HR','HRV','385','a','');
INSERT INTO countries VALUES (54,1,'Cuba','192','','CU','CUB','53','a','');
INSERT INTO countries VALUES (55,1,'Cyprus','196','','CY','CYP','357','a','');
INSERT INTO countries VALUES (56,6,'Czech Republic','203','','CZ','CZE','420','a','');
INSERT INTO countries VALUES (57,1,'Democratic People\'s Republic of Korea','408','','KP','PRK','850','a','');
INSERT INTO countries VALUES (58,1,'Democratic Republic of the Congo','180','','CD','COD','243','a','');
INSERT INTO countries VALUES (59,5,'Denmark','208','','DK','DNK','45','a','');
INSERT INTO countries VALUES (60,1,'Djibouti','262','','DJ','DJI','253','a','');
INSERT INTO countries VALUES (61,1,'Dominica','212','','DM','DMA','1 767','a','');
INSERT INTO countries VALUES (62,1,'Dominican Republic','214','','DO','DOM','1 809','a','');
INSERT INTO countries VALUES (63,1,'Ecuador','218','','EC','ECU','593','a','');
INSERT INTO countries VALUES (64,6,'Egypt','818','','EG','EGY','20','a','');
INSERT INTO countries VALUES (65,1,'El Salvador','222','','SV','SLV','503','a','');
INSERT INTO countries VALUES (66,1,'Equatorial Guinea','226','','GQ','GNQ','240','a','');
INSERT INTO countries VALUES (67,1,'Eritrea','232','','ER','ERI','291','a','');
INSERT INTO countries VALUES (68,1,'Estonia','233','','EE','EST','372','a','');
INSERT INTO countries VALUES (69,1,'Ethiopia','231','','ET','ETH','251','a','');
INSERT INTO countries VALUES (70,1,'Faeroe Islands','234','','FO','FRO','298','a','');
INSERT INTO countries VALUES (71,1,'Falkland Islands (Malvinas)','238','','FK','FLK','500','a','');
INSERT INTO countries VALUES (72,1,'Federated States of Micronesia','583','','FM','FSM','691','a','');
INSERT INTO countries VALUES (73,1,'Fiji','242','','FJ','FJI','679','a','');
INSERT INTO countries VALUES (74,6,'Finland','246','','FI','FIN','358','a','');
INSERT INTO countries VALUES (75,6,'France','250','','FR','FRA','33','a','');
INSERT INTO countries VALUES (76,6,'France, metropolitan','249','','FX','FXX','33','a','');
INSERT INTO countries VALUES (77,1,'French Guiana','254','','GF','GUF','594','a','');
INSERT INTO countries VALUES (78,1,'French Polynesia','258','','PF','PYF','689','a','');
INSERT INTO countries VALUES (79,1,'Gabon','266','','GA','GAB','241','a','');
INSERT INTO countries VALUES (80,1,'Gambia','270','','GM','GMB','220','a','');
INSERT INTO countries VALUES (81,1,'Georgia','268','','GE','GEO','995','a','');
INSERT INTO countries VALUES (82,6,'Germany','276','','DE','DEU','49','a','');
INSERT INTO countries VALUES (83,1,'Ghana','288','','GH','GHA','233','a','');
INSERT INTO countries VALUES (84,1,'Gibraltar','292','','GI','GIB','350','a','');
INSERT INTO countries VALUES (85,6,'Greece','300','','GR','GRC','30','a','');
INSERT INTO countries VALUES (86,6,'Greenland','304','','GL','GRL','299','a','');
INSERT INTO countries VALUES (87,1,'Grenada','308','','GD','GRD','1 473','a','');
INSERT INTO countries VALUES (88,1,'Guadeloupe','312','','GP','GLP','590','a','');
INSERT INTO countries VALUES (89,1,'Guam','316','','GU','GUM','1 671','a','');
INSERT INTO countries VALUES (90,1,'Guatemala','320','','GT','GTM','502','a','');
INSERT INTO countries VALUES (91,1,'Guinea','324','','GN','GIN','224','a','');
INSERT INTO countries VALUES (92,1,'Guinea-Bissau','624','','GW','GNB','245','a','');
INSERT INTO countries VALUES (93,1,'Guyana','328','','GY','GUY','592','a','');
INSERT INTO countries VALUES (94,1,'Haiti','332','','HT','HTI','509','a','');
INSERT INTO countries VALUES (95,1,'Holy See','336','','VA','VAT','39','a','');
INSERT INTO countries VALUES (96,1,'Honduras','340','','HN','HND','504','a','');
INSERT INTO countries VALUES (97,4,'Hong Kong Special Administrative Region of China','344','','HK','HKG','852','a','');
INSERT INTO countries VALUES (98,7,'Hungary','348','','HU','HUN','36','a','');
INSERT INTO countries VALUES (99,6,'Iceland','352','','IS','ISL','354','a','');
INSERT INTO countries VALUES (100,8,'India','356','','IN','IND','91','a','');
INSERT INTO countries VALUES (101,9,'Indonesia','360','','ID','IDN','62','a','');
INSERT INTO countries VALUES (102,1,'Iran','364','','IR','IRN','98','a','');
INSERT INTO countries VALUES (103,1,'Iraq','368','','IQ','IRQ','964','a','');
INSERT INTO countries VALUES (104,4,'Ireland','372','','IE','IRL','353','a','');
INSERT INTO countries VALUES (105,6,'Israel','376','','IL','ISR','972','a','');
INSERT INTO countries VALUES (106,3,'Italy','380','','IT','ITA','39','a','');
INSERT INTO countries VALUES (107,1,'Jamaica','388','','JM','JAM','1 876','a','');
INSERT INTO countries VALUES (108,10,'Japan','392','','JP','JPN','81','a','');
INSERT INTO countries VALUES (109,6,'Jordan','400','','JO','JOR','962','a','');
INSERT INTO countries VALUES (110,1,'Kazakhstan','398','','KZ','KAZ','7','a','');
INSERT INTO countries VALUES (111,1,'Kenya','404','','KE','KEN','254','a','');
INSERT INTO countries VALUES (112,1,'Kiribati','296','','KI','KIR','686','a','');
INSERT INTO countries VALUES (113,2,'Kuwait','414','','KW','KWT','965','a','');
INSERT INTO countries VALUES (114,1,'Kyrgyzstan','417','','KG','KGZ','996','a','');
INSERT INTO countries VALUES (115,1,'Lao People\'s Democratic Republic','418','','LA','LAO','856','a','');
INSERT INTO countries VALUES (116,1,'Latvia','428','','LV','LVA','371','a','');
INSERT INTO countries VALUES (117,6,'Lebanon','422','','LB','LBN','961','a','');
INSERT INTO countries VALUES (118,1,'Lesotho','426','','LS','LSO','266','a','');
INSERT INTO countries VALUES (119,1,'Liberia','430','','LR','LBR','231','a','');
INSERT INTO countries VALUES (120,1,'Libyan Arab Jamahiriya','434','','LY','LBY','218','a','');
INSERT INTO countries VALUES (121,1,'Liechtenstein','438','','LI','LIE','423','a','');
INSERT INTO countries VALUES (122,1,'Lithuania','440','','LT','LTU','370','a','');
INSERT INTO countries VALUES (123,6,'Luxembourg','442','','LU','LUX','352','a','');
INSERT INTO countries VALUES (124,1,'Macau','446','','MO','MAC','853','a','');
INSERT INTO countries VALUES (125,1,'Madagascar','450','','MG','MDG','261','a','');
INSERT INTO countries VALUES (126,1,'Malawi','454','','MW','MWI','265','a','');
INSERT INTO countries VALUES (127,1,'Malaysia','458','','MY','MYS','60','a','');
INSERT INTO countries VALUES (128,1,'Maldives','462','','MV','MDV','960','a','');
INSERT INTO countries VALUES (129,1,'Mali','466','','ML','MLI','223','a','');
INSERT INTO countries VALUES (130,1,'Malta','470','','MT','MLT','356','a','');
INSERT INTO countries VALUES (131,1,'Marshall Islands','584','','MH','MHL','692','a','');
INSERT INTO countries VALUES (132,1,'Martinique','474','','MQ','MTQ','596','a','');
INSERT INTO countries VALUES (133,1,'Mauritania','478','','MR','MRT','222','a','');
INSERT INTO countries VALUES (134,1,'Mauritius','480','','MU','MUS','230','a','');
INSERT INTO countries VALUES (135,1,'Mayotte','175','','YT','MYT','269','a','');
INSERT INTO countries VALUES (136,3,'Mexico','484','','MX','MEX','52','a','');
INSERT INTO countries VALUES (137,1,'Monaco','492','','MC','MCO','377','a','');
INSERT INTO countries VALUES (138,1,'Mongolia','496','','MN','MNG','976','a','');
INSERT INTO countries VALUES (139,1,'Montserrat','500','','MS','MSR','1 664','a','');
INSERT INTO countries VALUES (140,1,'Morocco','504','','MA','MAR','212','a','');
INSERT INTO countries VALUES (141,1,'Mozambique','508','','MZ','MOZ','258','a','');
INSERT INTO countries VALUES (142,1,'Myanmar','104','','MM','MMR','95','a','');
INSERT INTO countries VALUES (143,1,'Namibia','516','','NA','NAM','264','a','');
INSERT INTO countries VALUES (144,1,'Nauru','520','','NR','NRU','674','a','');
INSERT INTO countries VALUES (145,1,'Nepal','524','','NP','NPL','977','a','');
INSERT INTO countries VALUES (146,6,'Netherlands','528','','NL','NLD','31','a','');
INSERT INTO countries VALUES (147,1,'Netherlands Antilles','530','','AN','ANT','599','a','');
INSERT INTO countries VALUES (148,1,'New Caledonia','540','','NC','NCL','687','a','');
INSERT INTO countries VALUES (149,8,'New Zealand','554','','NZ','NZL','64','a','');
INSERT INTO countries VALUES (150,1,'Nicaragua','558','','NI','NIC','505','a','');
INSERT INTO countries VALUES (151,1,'Niger','562','','NE','NER','227','a','');
INSERT INTO countries VALUES (152,1,'Nigeria','566','','NG','NGA','234','a','');
INSERT INTO countries VALUES (153,1,'Niue','570','','NU','NIU','683','a','');
INSERT INTO countries VALUES (154,1,'Norfolk Island','574','','NF','NFK','672','a','');
INSERT INTO countries VALUES (155,1,'Northern Mariana Islands','580','','MP','MNP','1 670','a','');
INSERT INTO countries VALUES (156,6,'Norway','578','','NO','NOR','47','a','');
INSERT INTO countries VALUES (157,2,'Oman','512','','OM','OMN','968','a','');
INSERT INTO countries VALUES (158,1,'Pakistan','586','','PK','PAK','92','a','');
INSERT INTO countries VALUES (159,1,'Palau','585','','PW','PLW','680','a','');
INSERT INTO countries VALUES (160,1,'Panama','591','','PA','PAN','507','a','');
INSERT INTO countries VALUES (161,1,'Papua New Guinea','598','','PG','PNG','675','a','');
INSERT INTO countries VALUES (162,1,'Paraguay','600','','PY','PRY','595','a','');
INSERT INTO countries VALUES (163,1,'Peru','604','','PE','PER','51','a','');
INSERT INTO countries VALUES (164,1,'Philippines','608','','PH','PHL','63','a','');
INSERT INTO countries VALUES (165,2,'Poland','616','','PL','POL','48','a','');
INSERT INTO countries VALUES (166,3,'Portugal','620','','PT','PRT','351','a','');
INSERT INTO countries VALUES (167,1,'Puerto Rico','630','','PR','PRI','1 787','a','');
INSERT INTO countries VALUES (168,6,'Qatar','634','','QA','QAT','974','a','');
INSERT INTO countries VALUES (169,11,'Republic of Korea','410','','KR','KOR','82','a','');
INSERT INTO countries VALUES (170,1,'Republic of Moldova','498','','MD','MDA','373','a','');
INSERT INTO countries VALUES (171,1,'Réunion','638','','RE','REU','262','a','');
INSERT INTO countries VALUES (172,6,'Romania','642','','RO','ROM','40','a','');
INSERT INTO countries VALUES (173,12,'Russian Federation','643','','RU','RUS','7','a','');
INSERT INTO countries VALUES (174,1,'Rwanda','646','','RW','RWA','250','a','');
INSERT INTO countries VALUES (175,1,'Saint Helena','654','','SH','SHN','290','a','');
INSERT INTO countries VALUES (176,1,'Saint Kitts and Nevis','659','','KN','KNA','1 869','a','');
INSERT INTO countries VALUES (177,1,'Saint Lucia','662','','LC','LCA','1 758','a','');
INSERT INTO countries VALUES (178,1,'Saint Pierre and Miquelon','666','','PM','SPM','508','a','');
INSERT INTO countries VALUES (179,1,'Saint Vincent and the Grenadines','670','','VC','VCT','1 784','a','');
INSERT INTO countries VALUES (180,1,'Samoa','882','','WS','WSM','685','a','');
INSERT INTO countries VALUES (181,1,'San Marino','674','','SM','SMR','378','a','');
INSERT INTO countries VALUES (182,1,'São Tomé and Principe','678','','ST','STP','239','a','');
INSERT INTO countries VALUES (183,6,'Saudi Arabia','682','','SA','SAU','966','a','');
INSERT INTO countries VALUES (184,1,'Senegal','686','','SN','SEN','221','a','');
INSERT INTO countries VALUES (185,1,'Seychelles','690','','SC','SYC','248','a','');
INSERT INTO countries VALUES (186,1,'Sierra Leone','694','','SL','SLE','232','a','');
INSERT INTO countries VALUES (187,6,'Singapore','702','','SG','SGP','65','a','');
INSERT INTO countries VALUES (188,6,'Slovakia','703','','SK','SVK','421','a','');
INSERT INTO countries VALUES (189,6,'Slovenia','705','','SI','SVN','386','a','');
INSERT INTO countries VALUES (190,1,'Solomon Islands','90','','SB','SLB','677','a','');
INSERT INTO countries VALUES (191,1,'Somalia','706','','SO','SOM','252','a','');
INSERT INTO countries VALUES (192,13,'South Africa','710','','ZA','ZAF','27','a','');
INSERT INTO countries VALUES (193,3,'Spain','724','','ES','ESP','34','a','');
INSERT INTO countries VALUES (194,1,'Sri Lanka','144','','LK','LKA','94','a','');
INSERT INTO countries VALUES (195,1,'Sudan','736','','SD','SDN','249','a','');
INSERT INTO countries VALUES (196,1,'Suriname','740','','SR','SUR','597','a','');
INSERT INTO countries VALUES (197,1,'Swaziland','748','','SZ','SWZ','268','a','');
INSERT INTO countries VALUES (198,6,'Sweden','752','','SE','SWE','46','a','');
INSERT INTO countries VALUES (199,6,'Switzerland','756','','CH','CHE','41','a','');
INSERT INTO countries VALUES (200,6,'Syrian Arab Republic','760','','SY','SYR','963','a','');
INSERT INTO countries VALUES (201,4,'Taiwan','158','','TW','TWN','886','a','');
INSERT INTO countries VALUES (202,1,'Tajikistan','762','','TJ','TJK','7','a','');
INSERT INTO countries VALUES (203,1,'Thailand','764','','TH','THA','66','a','');
INSERT INTO countries VALUES (204,14,'The former Yugoslav Republic of Macedonia','807','','MK','MKD','389','a','');
INSERT INTO countries VALUES (205,1,'Togo','768','','TG','TGO','228','a','');
INSERT INTO countries VALUES (206,1,'Tonga','776','','TO','TON','676','a','');
INSERT INTO countries VALUES (207,1,'Trinidad and Tobago','780','','TT','TTO','1 868','a','');
INSERT INTO countries VALUES (208,1,'Tunisia','788','','TN','TUN','216','a','');
INSERT INTO countries VALUES (209,6,'Turkey','792','','TR','TUR','90','a','');
INSERT INTO countries VALUES (210,1,'Turkmenistan','795','','TM','TKM','993','a','');
INSERT INTO countries VALUES (211,1,'Turks and Caicos Islands','796','','TC','TCA','1 649','a','');
INSERT INTO countries VALUES (212,1,'Tuvalu','798','','TV','TUV','688','a','');
INSERT INTO countries VALUES (213,1,'Uganda','800','','UG','UGA','256','a','');
INSERT INTO countries VALUES (214,11,'Ukraine','804','','UA','UKR','380','a','');
INSERT INTO countries VALUES (215,1,'United Arab Emirates','784','','AE','ARE','971','a','');
INSERT INTO countries VALUES (216,13,'United Kingdom','826','','GB','GBR','44','a','');
INSERT INTO countries VALUES (217,1,'United Republic of Tanzania','834','','TZ','TZA','255','a','');
INSERT INTO countries VALUES (218,15,'United States','840','','US','USA','1','a','(###) ###-####');
INSERT INTO countries VALUES (219,15,'United States Virgin Islands','850','','VI','VIR','1 340','a','');
INSERT INTO countries VALUES (220,1,'Uruguay','858','','UY','URY','598','a','');
INSERT INTO countries VALUES (221,1,'Uzbekistan','860','','UZ','UZB','998','a','');
INSERT INTO countries VALUES (222,1,'Vanuatu','548','','VU','VUT','678','a','');
INSERT INTO countries VALUES (223,1,'Venezuela','862','','VE','VEN','58','a','');
INSERT INTO countries VALUES (224,1,'Viet Nam','704','','VN','VNM','84','a','');
INSERT INTO countries VALUES (225,6,'Yemen','887','','YE','YEM','967','a','');
INSERT INTO countries VALUES (226,1,'Yugoslavia','891','','YU','YUG','381','a','');
INSERT INTO countries VALUES (227,1,'Zambia','894','','ZM','ZMB','260','a','');
INSERT INTO countries VALUES (228,1,'Zimbabwe','716','','ZW','ZWE','263','a','');

--
-- Table structure for table `crm_statuses`
--

CREATE TABLE crm_statuses (
  crm_status_id int(11) NOT NULL auto_increment,
  crm_status_short_name varchar(10) NOT NULL default '',
  crm_status_pretty_name varchar(100) NOT NULL default '',
  crm_status_pretty_plural varchar(100) NOT NULL default '',
  crm_status_display_html varchar(100) NOT NULL default '',
  crm_status_record_status char(1) default 'a',
  PRIMARY KEY  (crm_status_id)
) TYPE=MyISAM;

--
-- Dumping data for table `crm_statuses`
--


INSERT INTO crm_statuses VALUES (1,'Lead','Lead','Leads','Lead','a');
INSERT INTO crm_statuses VALUES (2,'Prospect','Prospect','Prospects','Prospect','a');
INSERT INTO crm_statuses VALUES (3,'Qualified','Qualified','Qualified','Qualified','a');
INSERT INTO crm_statuses VALUES (4,'Developed','Developed','Developed','Developed','a');
INSERT INTO crm_statuses VALUES (5,'Closed','Closed','Closed','Closed','a');

--
-- Table structure for table `email_templates`
--

CREATE TABLE email_templates (
  email_template_id int(11) NOT NULL auto_increment,
  email_template_title varchar(100) NOT NULL default '',
  email_template_body text NOT NULL,
  email_template_record_status char(1) NOT NULL default 'a',
  PRIMARY KEY  (email_template_id)
) TYPE=MyISAM;

--
-- Dumping data for table `email_templates`
--


INSERT INTO email_templates VALUES (1,'Blank Template','','a');
INSERT INTO email_templates VALUES (2,'Introduction','','a');
INSERT INTO email_templates VALUES (3,'Sales Pitch','','a');
INSERT INTO email_templates VALUES (4,'Thanks for Your Business','','a');
INSERT INTO email_templates VALUES (5,'Customer Service Inquiry','','a');

--
-- Table structure for table `entity_category_map`
--

CREATE TABLE entity_category_map (
  category_id int(11) NOT NULL default '0',
  on_what_table varchar(100) NOT NULL default '',
  on_what_id int(11) NOT NULL default '0'
) TYPE=MyISAM;

--
-- Dumping data for table `entity_category_map`
--



--
-- Table structure for table `files`
--

CREATE TABLE files (
  file_id int(11) NOT NULL auto_increment,
  file_pretty_name varchar(100) NOT NULL default '',
  file_description text NOT NULL,
  file_filesystem_name varchar(100) NOT NULL default '',
  file_size int(11) NOT NULL default '0',
  file_type varchar(100) NOT NULL default '',
  on_what_table varchar(100) NOT NULL default '',
  on_what_id int(11) NOT NULL default '0',
  entered_at datetime default NULL,
  entered_by int(11) NOT NULL default '0',
  file_record_status char(1) default 'a',
  PRIMARY KEY  (file_id)
) TYPE=MyISAM;

--
-- Dumping data for table `files`
--



--
-- Table structure for table `industries`
--

CREATE TABLE industries (
  industry_id int(11) NOT NULL auto_increment,
  industry_short_name varchar(10) NOT NULL default '',
  industry_pretty_name varchar(100) NOT NULL default '',
  industry_pretty_plural varchar(100) NOT NULL default '',
  industry_display_html varchar(100) NOT NULL default '',
  industry_record_status char(1) default 'a',
  PRIMARY KEY  (industry_id)
) TYPE=MyISAM;

--
-- Dumping data for table `industries`
--


INSERT INTO industries VALUES (1,'OTH','Other','Other','Other','a');
INSERT INTO industries VALUES (2,'ADV','Advertising','Advertising','Advertising','a');
INSERT INTO industries VALUES (3,'ARCH','Architecture','Architecture','Architecture','a');
INSERT INTO industries VALUES (4,'CHEM','Chemicals','Chemicals','Chemicals','a');
INSERT INTO industries VALUES (5,'COM','Communications','Communications','Communications','a');
INSERT INTO industries VALUES (6,'COMP','Computers','Computers','Computers','a');
INSERT INTO industries VALUES (7,'CONST','Construction','Construction','Construction','a');
INSERT INTO industries VALUES (8,'CONS','Consulting','Consulting','Consulting','a');
INSERT INTO industries VALUES (9,'DIST','Distribution','Distribution','Distribution','a');
INSERT INTO industries VALUES (10,'EDU','Education','Education','Education','a');
INSERT INTO industries VALUES (11,'FIN','Finance','Finance','Finance','a');
INSERT INTO industries VALUES (12,'GOV','Government','Government','Government','a');
INSERT INTO industries VALUES (13,'HEAL','Healthcare','Healthcare','Healthcare','a');
INSERT INTO industries VALUES (14,'INS','Insurance','Insurance','Insurance','a');
INSERT INTO industries VALUES (15,'LEG','Legal','Legal','Legal','a');
INSERT INTO industries VALUES (16,'MAN','Manufacturing','Manufacturing','Manufacturing','a');
INSERT INTO industries VALUES (17,'NP','Non-Profit','Non-Profit','Non-Profit','a');
INSERT INTO industries VALUES (18,'RE','Real Estate','Real Estate','Real Estate','a');
INSERT INTO industries VALUES (19,'REST','Restaurant','Restaurant','Restaurant','a');
INSERT INTO industries VALUES (20,'RET','Retail','Retail','Retail','a');

--
-- Table structure for table `notes`
--

CREATE TABLE notes (
  note_id int(11) NOT NULL auto_increment,
  note_description text NOT NULL,
  on_what_table varchar(100) default NULL,
  on_what_id int(11) NOT NULL default '0',
  entered_at datetime default NULL,
  entered_by int(11) NOT NULL default '0',
  note_record_status char(1) NOT NULL default 'a',
  PRIMARY KEY  (note_id)
) TYPE=MyISAM;

--
-- Dumping data for table `notes`
--



--
-- Table structure for table `opportunities`
--

CREATE TABLE opportunities (
  opportunity_id int(11) NOT NULL auto_increment,
  opportunity_status_id int(11) NOT NULL default '0',
  campaign_id int(11) default NULL,
  company_id int(11) NOT NULL default '0',
  division_id int(11) default NULL,
  contact_id int(11) NOT NULL default '0',
  user_id int(11) NOT NULL default '0',
  opportunity_title varchar(100) NOT NULL default '',
  opportunity_description text NOT NULL,
  next_step varchar(100) NOT NULL default '',
  size decimal(10,2) NOT NULL default '0.00',
  probability int(11) NOT NULL default '0',
  close_at datetime default NULL,
  entered_at datetime default NULL,
  entered_by int(11) NOT NULL default '0',
  last_modified_at datetime default NULL,
  last_modified_by int(11) NOT NULL default '0',
  owned_at datetime default NULL,
  owned_by int(11) NOT NULL default '0',
  closed_at datetime default NULL,
  closed_by int(11) NOT NULL default '0',
  opportunity_record_status char(1) default 'a',
  PRIMARY KEY  (opportunity_id)
) TYPE=MyISAM;

--
-- Dumping data for table `opportunities`
--



--
-- Table structure for table `opportunity_statuses`
--

CREATE TABLE opportunity_statuses (
  opportunity_status_id int(11) NOT NULL auto_increment,
  sort_order tinyint(4) NOT NULL default '1',
  status_open_indicator char(1) NOT NULL default 'o',
  opportunity_status_short_name varchar(10) NOT NULL default '',
  opportunity_status_pretty_name varchar(100) NOT NULL default '',
  opportunity_status_pretty_plural varchar(100) NOT NULL default '',
  opportunity_status_display_html varchar(100) NOT NULL default '',
  opportunity_status_record_status char(1) NOT NULL default 'a',
  opportunity_status_long_desc varchar(255) NOT NULL default '',
  PRIMARY KEY  (opportunity_status_id)
) TYPE=MyISAM;

--
-- Dumping data for table `opportunity_statuses`
--


INSERT INTO opportunity_statuses VALUES (1,1,'o','NEW','New','New','New','a','');
INSERT INTO opportunity_statuses VALUES (2,2,'o','PRE','Preliminaries','Preliminaries','Preliminaries','a','');
INSERT INTO opportunity_statuses VALUES (3,3,'o','DIS','Discussion','Discussion','Discussion','a','');
INSERT INTO opportunity_statuses VALUES (4,4,'o','NEG','Negotiation','Negotiation','Negotiation','a','');
INSERT INTO opportunity_statuses VALUES (5,5,'w','CLW','Closed/Won','Closed/Won','Closed/Won','a','');
INSERT INTO opportunity_statuses VALUES (6,6,'l','CLL','Closed/Lost','Closed/Lost','Closed/Lost','a','');
INSERT INTO opportunity_statuses VALUES (7,6,'l','CLL','Closed/Lost','Closed/Lost','Closed/Lost','a','');

--
-- Table structure for table `ratings`
--

CREATE TABLE ratings (
  rating_id int(11) NOT NULL auto_increment,
  rating_short_name varchar(10) NOT NULL default '',
  rating_pretty_name varchar(100) NOT NULL default '',
  rating_pretty_plural varchar(100) NOT NULL default '',
  rating_display_html varchar(100) NOT NULL default '',
  rating_record_status char(1) default 'a',
  PRIMARY KEY  (rating_id)
) TYPE=MyISAM;

--
-- Dumping data for table `ratings`
--


INSERT INTO ratings VALUES (1,'N/A','N/A','N/A','<font color=#999999><b>N/A</b></font>','a');
INSERT INTO ratings VALUES (2,'Poor','Poor','Poor','<font color=#cc0000><b>Poor</b></font>','a');
INSERT INTO ratings VALUES (3,'Fair','Fair','Fair','<font color=#ff9933><b>Fair</b></font>','a');
INSERT INTO ratings VALUES (4,'Good','Good','Good','<font color=#009900><b>Good</b></font>','a');

--
-- Table structure for table `recent_items`
--

CREATE TABLE recent_items (
  recent_item_id int(11) NOT NULL auto_increment,
  user_id int(11) NOT NULL default '0',
  on_what_table varchar(100) NOT NULL default '',
  recent_action varchar(100) NOT NULL default '',
  on_what_id int(11) NOT NULL default '0',
  recent_item_timestamp timestamp(14) NOT NULL,
  PRIMARY KEY  (recent_item_id)
) TYPE=MyISAM;

--
-- Dumping data for table `recent_items`
--



--
-- Table structure for table `relationship_types`
--

CREATE TABLE relationship_types (
  relationship_type_id int(10) unsigned NOT NULL auto_increment,
  relationship_name varchar(48) NOT NULL default '',
  from_what_table varchar(24) NOT NULL default '',
  to_what_table varchar(24) NOT NULL default '',
  from_what_text varchar(32) NOT NULL default '',
  to_what_text varchar(32) NOT NULL default '',
  relationship_status char(1) NOT NULL default 'a',
  pre_formatting varchar(25) default NULL,
  post_formatting varchar(25) default NULL,
  PRIMARY KEY  (relationship_type_id)
) TYPE=MyISAM;

--
-- Dumping data for table `relationship_types`
--


INSERT INTO relationship_types VALUES (1,'company relationships','companies','companies','Acquired','Acquired by','a',NULL,NULL);
INSERT INTO relationship_types VALUES (2,'company relationships','companies','companies','Retains Consultant','Consultant for','a',NULL,NULL);
INSERT INTO relationship_types VALUES (3,'company relationships','companies','companies','Manufactures for','Uses Manufacturer','a',NULL,NULL);
INSERT INTO relationship_types VALUES (4,'company relationships','companies','companies','Parent Company of','Subsidiary of','a',NULL,NULL);
INSERT INTO relationship_types VALUES (5,'company relationships','companies','companies','Uses Supplier','Supplier for','a',NULL,NULL);
INSERT INTO relationship_types VALUES (6,'company link','contacts','companies','Owns','Owned By','a','<b>','</b>');
INSERT INTO relationship_types VALUES (7,'company link','contacts','companies','Manages','Managed By','a',NULL,NULL);
INSERT INTO relationship_types VALUES (8,'company link','contacts','companies','Consultant for','Retains Consultant','a',NULL,NULL);

--
-- Table structure for table `relationships`
--

CREATE TABLE relationships (
  relationship_id int(10) unsigned NOT NULL auto_increment,
  from_what_id int(10) unsigned NOT NULL default '0',
  to_what_id int(10) unsigned NOT NULL default '0',
  relationship_type_id int(10) unsigned NOT NULL default '0',
  established_at datetime default NULL,
  ended_on datetime default NULL,
  relationship_status char(1) NOT NULL default 'a',
  PRIMARY KEY  (relationship_id),
  KEY from_what_id (from_what_id),
  KEY to_what_id (to_what_id)
) TYPE=MyISAM;

--
-- Dumping data for table `relationships`
--



--
-- Table structure for table `roles`
--

CREATE TABLE roles (
  role_id int(11) NOT NULL auto_increment,
  role_short_name varchar(10) NOT NULL default '',
  role_pretty_name varchar(100) NOT NULL default '',
  role_pretty_plural varchar(100) NOT NULL default '',
  role_display_html varchar(100) NOT NULL default '',
  role_record_status char(1) default 'a',
  PRIMARY KEY  (role_id)
) TYPE=MyISAM;

--
-- Dumping data for table `roles`
--


INSERT INTO roles VALUES (1,'User','User','Users','User','a');
INSERT INTO roles VALUES (2,'Admin','Admin','Admin','Admin','a');

--
-- Table structure for table `saved_actions`
--

CREATE TABLE saved_actions (
  saved_id int(10) unsigned NOT NULL auto_increment,
  saved_title varchar(100) NOT NULL default '',
  user_id int(10) unsigned NOT NULL default '0',
  on_what_table varchar(100) NOT NULL default '',
  saved_action varchar(100) NOT NULL default '',
  group_item int(1) NOT NULL default '0',
  saved_data text NOT NULL,
  saved_status char(1) NOT NULL default 'a',
  PRIMARY KEY  (saved_id),
  KEY user_id (user_id),
  KEY group_item (group_item)
) TYPE=MyISAM;

--
-- Dumping data for table `saved_actions`
--



--
-- Table structure for table `system_parameters`
--

CREATE TABLE system_parameters (
  param_id varchar(40) NOT NULL default '',
  string_val varchar(100) default NULL,
  int_val int(11) default NULL,
  float_val float default NULL,
  datetime_val datetime default NULL,
  UNIQUE KEY param_id (param_id)
) TYPE=MyISAM;

--
-- Dumping data for table `system_parameters`
--


INSERT INTO system_parameters VALUES ('Default GST Offset',NULL,-5,NULL,NULL);
INSERT INTO system_parameters VALUES ('Audit Level',NULL,4,NULL,NULL);
INSERT INTO system_parameters VALUES ('Activities Default Behavior','Fast',NULL,NULL,NULL);
INSERT INTO system_parameters VALUES ('LDAP Version',NULL,2,NULL,NULL);

--
-- Table structure for table `time_daylight_savings`
--

CREATE TABLE time_daylight_savings (
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
) TYPE=MyISAM;

--
-- Dumping data for table `time_daylight_savings`
--


INSERT INTO time_daylight_savings VALUES (1,'','',0,'','',0,0,'2004-08-02',0);
INSERT INTO time_daylight_savings VALUES (2,'','',0,'','',0,1,'2004-08-02',1);
INSERT INTO time_daylight_savings VALUES (3,'first','Sunday',4,'last','Sunday',0,1,'2004-08-02',1);

--
-- Table structure for table `time_zones`
--

CREATE TABLE time_zones (
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
) TYPE=MyISAM;

--
-- Dumping data for table `time_zones`
--


INSERT INTO time_zones VALUES (1,218,'AL',NULL,NULL,3,-6,'y');
INSERT INTO time_zones VALUES (2,218,'AK',NULL,NULL,3,-9,'n');
INSERT INTO time_zones VALUES (3,218,'AK','Anchorage',NULL,3,-9,'y');
INSERT INTO time_zones VALUES (4,218,'AK','Bethel',NULL,3,-9,'y');
INSERT INTO time_zones VALUES (5,218,'AK','College',NULL,3,-9,'y');
INSERT INTO time_zones VALUES (6,218,'AK','Eielson AFB',NULL,3,-9,'y');
INSERT INTO time_zones VALUES (7,218,'AK','Fairbanks',NULL,3,-9,'y');
INSERT INTO time_zones VALUES (8,218,'AK','Juneau',NULL,3,-9,'y');
INSERT INTO time_zones VALUES (9,218,'AK','Kalifornsky',NULL,3,-9,'y');
INSERT INTO time_zones VALUES (10,218,'AK','Kenai',NULL,3,-9,'y');
INSERT INTO time_zones VALUES (11,218,'AK','Ketchikan',NULL,3,-9,'y');
INSERT INTO time_zones VALUES (12,218,'AK','Knik-Fairview',NULL,3,-9,'y');
INSERT INTO time_zones VALUES (13,218,'AK','Kodiak',NULL,3,-9,'y');
INSERT INTO time_zones VALUES (14,218,'AK','Lakes',NULL,3,-9,'y');
INSERT INTO time_zones VALUES (15,218,'AK','Meadow Lakes',NULL,3,-9,'y');
INSERT INTO time_zones VALUES (16,218,'AK','Sitka',NULL,3,-9,'y');
INSERT INTO time_zones VALUES (17,218,'AK','Tanaina',NULL,3,-9,'y');
INSERT INTO time_zones VALUES (18,218,'AK','Wasilla',NULL,3,-9,'y');
INSERT INTO time_zones VALUES (19,218,'AZ',NULL,NULL,1,-7,'y');
INSERT INTO time_zones VALUES (20,218,'AR',NULL,NULL,3,-6,'y');
INSERT INTO time_zones VALUES (21,218,'CA',NULL,NULL,3,-8,'y');
INSERT INTO time_zones VALUES (22,218,'CO',NULL,NULL,3,-7,'y');
INSERT INTO time_zones VALUES (23,218,'CT',NULL,NULL,3,-5,'y');
INSERT INTO time_zones VALUES (24,218,'DE',NULL,NULL,3,-5,'y');
INSERT INTO time_zones VALUES (25,218,'FL',NULL,NULL,3,-5,'n');
INSERT INTO time_zones VALUES (26,218,'FL','Alamonte Springs',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (27,218,'FL','Boca Raton',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (28,218,'FL','Boynton Beach',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (29,218,'FL','Bradenton',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (30,218,'FL','Cape Coral',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (31,218,'FL','Clearwater',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (32,218,'FL','Coral Gables',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (33,218,'FL','Coral Springs',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (34,218,'FL','Davie',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (35,218,'FL','Daytona Beach',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (36,218,'FL','Deerfield Beach',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (37,218,'FL','Delray Beach',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (38,218,'FL','Deltona',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (39,218,'FL','Fort Lauderdale',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (40,218,'FL','Fort Myers',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (41,218,'FL','Gainesville',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (42,218,'FL','Hialeah',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (43,218,'FL','Hollywood',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (44,218,'FL','Jacksonville',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (45,218,'FL','Kissimmee',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (46,218,'FL','Lakeland',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (47,218,'FL','Largo',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (48,218,'FL','Lauderhill',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (49,218,'FL','Margate',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (50,218,'FL','Melbourne',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (51,218,'FL','Miami Beach',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (52,218,'FL','Miami',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (53,218,'FL','Miramar',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (54,218,'FL','North Miami Beach',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (55,218,'FL','North Miami',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (56,218,'FL','Ocala',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (57,218,'FL','Orlando',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (58,218,'FL','Palm Bay',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (59,218,'FL','Pembroke Pines',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (60,218,'FL','Pensacola',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (61,218,'FL','Plantation',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (62,218,'FL','Pompano Beach',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (63,218,'FL','Port Orange',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (64,218,'FL','Port St. Lucie',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (65,218,'FL','Sarasota',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (66,218,'FL','St. Petersburg',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (67,218,'FL','Sunrise',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (68,218,'FL','Tallahassee',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (69,218,'FL','Tamarac',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (70,218,'FL','Tampa',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (71,218,'FL','Titusville',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (72,218,'FL','West Palm Beach',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (73,218,'FL','Weston',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (74,218,'GA',NULL,NULL,3,-5,'y');
INSERT INTO time_zones VALUES (75,218,'HI',NULL,NULL,1,-10,'y');
INSERT INTO time_zones VALUES (76,218,'ID',NULL,NULL,3,-7,'n');
INSERT INTO time_zones VALUES (77,218,'ID','Ammon',NULL,3,-7,'y');
INSERT INTO time_zones VALUES (78,218,'ID','Blackfoot',NULL,3,-7,'y');
INSERT INTO time_zones VALUES (79,218,'ID','Boise',NULL,3,-7,'y');
INSERT INTO time_zones VALUES (80,218,'ID','Burley',NULL,3,-7,'y');
INSERT INTO time_zones VALUES (81,218,'ID','Caldwell',NULL,3,-7,'y');
INSERT INTO time_zones VALUES (82,218,'ID','Chubbuck',NULL,3,-7,'y');
INSERT INTO time_zones VALUES (83,218,'ID','Coeur d\'Alene',NULL,3,-8,'y');
INSERT INTO time_zones VALUES (84,218,'ID','Eagle',NULL,3,-7,'y');
INSERT INTO time_zones VALUES (85,218,'ID','Garden',NULL,3,-7,'y');
INSERT INTO time_zones VALUES (86,218,'ID','Hailey',NULL,3,-7,'y');
INSERT INTO time_zones VALUES (87,218,'ID','Hayden',NULL,3,-8,'y');
INSERT INTO time_zones VALUES (88,218,'ID','Idaho Falls',NULL,3,-7,'y');
INSERT INTO time_zones VALUES (89,218,'ID','Jerome',NULL,3,-7,'y');
INSERT INTO time_zones VALUES (90,218,'ID','Lewiston',NULL,3,-8,'y');
INSERT INTO time_zones VALUES (91,218,'ID','Meridian',NULL,3,-7,'y');
INSERT INTO time_zones VALUES (92,218,'ID','Moscow',NULL,3,-8,'y');
INSERT INTO time_zones VALUES (93,218,'ID','Mountain Home',NULL,3,-7,'y');
INSERT INTO time_zones VALUES (94,218,'ID','Nampa',NULL,3,-7,'y');
INSERT INTO time_zones VALUES (95,218,'ID','Payette',NULL,3,-7,'y');
INSERT INTO time_zones VALUES (96,218,'ID','Pocatello',NULL,3,-7,'y');
INSERT INTO time_zones VALUES (97,218,'ID','Post Falls',NULL,3,-7,'y');
INSERT INTO time_zones VALUES (98,218,'ID','Rexburg',NULL,3,-7,'y');
INSERT INTO time_zones VALUES (99,218,'ID','Sandpoint',NULL,3,-8,'y');
INSERT INTO time_zones VALUES (100,218,'ID','Twin Falls',NULL,3,-7,'y');
INSERT INTO time_zones VALUES (101,218,'IL',NULL,NULL,3,-6,'y');
INSERT INTO time_zones VALUES (102,218,'IN',NULL,NULL,1,-5,'n');
INSERT INTO time_zones VALUES (103,218,'IN','Alexandria',NULL,1,-5,'y');
INSERT INTO time_zones VALUES (104,218,'IN','Anderson',NULL,1,-5,'y');
INSERT INTO time_zones VALUES (105,218,'IN','Angola',NULL,1,-5,'y');
INSERT INTO time_zones VALUES (106,218,'IN','Auburn',NULL,1,-5,'y');
INSERT INTO time_zones VALUES (107,218,'IN','Avon',NULL,1,-5,'y');
INSERT INTO time_zones VALUES (108,218,'IN','Batesville',NULL,1,-5,'y');
INSERT INTO time_zones VALUES (109,218,'IN','Bedford',NULL,1,-5,'y');
INSERT INTO time_zones VALUES (110,218,'IN','Beech Grove',NULL,1,-5,'y');
INSERT INTO time_zones VALUES (111,218,'IN','Bloomington',NULL,1,-5,'y');
INSERT INTO time_zones VALUES (112,218,'IN','Bluffton',NULL,1,-5,'y');
INSERT INTO time_zones VALUES (113,218,'IN','Boonville',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (114,218,'IN','Brazil',NULL,1,-5,'y');
INSERT INTO time_zones VALUES (115,218,'IN','Brownsburg',NULL,1,-5,'y');
INSERT INTO time_zones VALUES (116,218,'IN','Carmel',NULL,1,-5,'y');
INSERT INTO time_zones VALUES (117,218,'IN','Cedar Lake',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (118,218,'IN','Charlestown',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (119,218,'IN','Chesterton',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (120,218,'IN','Clarksville',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (121,218,'IN','Columbia City',NULL,1,-5,'y');
INSERT INTO time_zones VALUES (122,218,'IN','Columbus',NULL,1,-5,'y');
INSERT INTO time_zones VALUES (123,218,'IN','Connersville',NULL,1,-5,'y');
INSERT INTO time_zones VALUES (124,218,'IN','Crawfordsville',NULL,1,-5,'y');
INSERT INTO time_zones VALUES (125,218,'IN','Crown Point',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (126,218,'IN','Danville',NULL,1,-5,'y');
INSERT INTO time_zones VALUES (127,218,'IN','Decatur',NULL,1,-5,'y');
INSERT INTO time_zones VALUES (128,218,'IN','Dyer',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (129,218,'IN','East Chicago',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (130,218,'IN','Elkhart',NULL,1,-5,'y');
INSERT INTO time_zones VALUES (131,218,'IN','Elwood',NULL,1,-5,'y');
INSERT INTO time_zones VALUES (132,218,'IN','Evansville',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (133,218,'IN','Evansville',NULL,1,-5,'y');
INSERT INTO time_zones VALUES (134,218,'IN','Fishers',NULL,1,-5,'y');
INSERT INTO time_zones VALUES (135,218,'IN','Fort Wayne',NULL,1,-5,'y');
INSERT INTO time_zones VALUES (136,218,'IN','Frankfort',NULL,1,-5,'y');
INSERT INTO time_zones VALUES (137,218,'IN','Franklin',NULL,1,-5,'y');
INSERT INTO time_zones VALUES (138,218,'IN','Gary',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (139,218,'IN','Gas City',NULL,1,-5,'y');
INSERT INTO time_zones VALUES (140,218,'IN','Goshen',NULL,1,-5,'y');
INSERT INTO time_zones VALUES (141,218,'IN','Greencastle',NULL,1,-5,'y');
INSERT INTO time_zones VALUES (142,218,'IN','Greenfield',NULL,1,-5,'y');
INSERT INTO time_zones VALUES (143,218,'IN','Greensburg',NULL,1,-5,'y');
INSERT INTO time_zones VALUES (144,218,'IN','Greenwood',NULL,1,-5,'y');
INSERT INTO time_zones VALUES (145,218,'IN','Griffith',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (146,218,'IN','Hammond',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (147,218,'IN','Hartford City',NULL,1,-5,'y');
INSERT INTO time_zones VALUES (148,218,'IN','Highland',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (149,218,'IN','Hobart',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (150,218,'IN','Huntington',NULL,1,-5,'y');
INSERT INTO time_zones VALUES (151,218,'IN','Indianapolis',NULL,1,-5,'y');
INSERT INTO time_zones VALUES (152,218,'IN','Jasper',NULL,1,-5,'y');
INSERT INTO time_zones VALUES (153,218,'IN','Jeffersonville',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (154,218,'IN','Kendallville',NULL,1,-5,'y');
INSERT INTO time_zones VALUES (155,218,'IN','Kokomo',NULL,1,-5,'y');
INSERT INTO time_zones VALUES (156,218,'IN','La Porte',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (157,218,'IN','Lafayette',NULL,1,-5,'y');
INSERT INTO time_zones VALUES (158,218,'IN','Lake Station',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (159,218,'IN','Lawrence',NULL,1,-5,'y');
INSERT INTO time_zones VALUES (160,218,'IN','Lebanon',NULL,1,-5,'y');
INSERT INTO time_zones VALUES (161,218,'IN','Logansport',NULL,1,-5,'y');
INSERT INTO time_zones VALUES (162,218,'IN','Lowell',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (163,218,'IN','Madison',NULL,1,-5,'y');
INSERT INTO time_zones VALUES (164,218,'IN','Marion',NULL,1,-5,'y');
INSERT INTO time_zones VALUES (165,218,'IN','Martinsville',NULL,1,-5,'y');
INSERT INTO time_zones VALUES (166,218,'IN','Merillville',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (167,218,'IN','Michigan City',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (168,218,'IN','Mishawaka',NULL,1,-5,'y');
INSERT INTO time_zones VALUES (169,218,'IN','Mooresville',NULL,1,-5,'y');
INSERT INTO time_zones VALUES (170,218,'IN','Mount Vernon',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (171,218,'IN','Muncie',NULL,1,-5,'y');
INSERT INTO time_zones VALUES (172,218,'IN','Munster',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (173,218,'IN','Nappanee',NULL,1,-5,'y');
INSERT INTO time_zones VALUES (174,218,'IN','New Albany',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (175,218,'IN','New Castle',NULL,1,-5,'y');
INSERT INTO time_zones VALUES (176,218,'IN','New Haven',NULL,1,-5,'y');
INSERT INTO time_zones VALUES (177,218,'IN','Noblesville',NULL,1,-5,'y');
INSERT INTO time_zones VALUES (178,218,'IN','North Manchester',NULL,1,-5,'y');
INSERT INTO time_zones VALUES (179,218,'IN','North Vernon',NULL,1,-5,'y');
INSERT INTO time_zones VALUES (180,218,'IN','Peru',NULL,1,-5,'y');
INSERT INTO time_zones VALUES (181,218,'IN','Plainfield',NULL,1,-5,'y');
INSERT INTO time_zones VALUES (182,218,'IN','Plymouth',NULL,1,-5,'y');
INSERT INTO time_zones VALUES (183,218,'IN','Portage',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (184,218,'IN','Portland',NULL,1,-5,'y');
INSERT INTO time_zones VALUES (185,218,'IN','Princeton',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (186,218,'IN','Richmond',NULL,1,-5,'y');
INSERT INTO time_zones VALUES (187,218,'IN','Rochester',NULL,1,-5,'y');
INSERT INTO time_zones VALUES (188,218,'IN','Rushville',NULL,1,-5,'y');
INSERT INTO time_zones VALUES (189,218,'IN','Salem',NULL,1,-5,'y');
INSERT INTO time_zones VALUES (190,218,'IN','Schererville',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (191,218,'IN','Scottsburg',NULL,1,-5,'y');
INSERT INTO time_zones VALUES (192,218,'IN','Sellersburg',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (193,218,'IN','Seymour',NULL,1,-5,'y');
INSERT INTO time_zones VALUES (194,218,'IN','Shelbyville',NULL,1,-5,'y');
INSERT INTO time_zones VALUES (195,218,'IN','South Bend',NULL,1,-5,'y');
INSERT INTO time_zones VALUES (196,218,'IN','Speedway',NULL,1,-5,'y');
INSERT INTO time_zones VALUES (197,218,'IN','St. John',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (198,218,'IN','Tell City',NULL,1,-5,'y');
INSERT INTO time_zones VALUES (199,218,'IN','Terre Haute',NULL,1,-5,'y');
INSERT INTO time_zones VALUES (200,218,'IN','Valparaiso',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (201,218,'IN','Vincennes',NULL,1,-5,'y');
INSERT INTO time_zones VALUES (202,218,'IN','Wabash',NULL,1,-5,'y');
INSERT INTO time_zones VALUES (203,218,'IN','Warsaw',NULL,1,-5,'y');
INSERT INTO time_zones VALUES (204,218,'IN','Washington',NULL,1,-5,'y');
INSERT INTO time_zones VALUES (205,218,'IN','West Lafayette',NULL,1,-5,'y');
INSERT INTO time_zones VALUES (206,218,'IN','Westfield',NULL,1,-5,'y');
INSERT INTO time_zones VALUES (207,218,'IN','Zionsville',NULL,1,-5,'y');
INSERT INTO time_zones VALUES (208,218,'IA',NULL,NULL,3,-6,'y');
INSERT INTO time_zones VALUES (209,218,'KS',NULL,NULL,3,-6,'n');
INSERT INTO time_zones VALUES (210,218,'KS','Abilene',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (211,218,'KS','Andover',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (212,218,'KS','Arkansas City',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (213,218,'KS','Atchison',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (214,218,'KS','Augusta',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (215,218,'KS','Bonner Springs',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (216,218,'KS','Chanute',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (217,218,'KS','Coffeyville',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (218,218,'KS','Derby',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (219,218,'KS','Dodge City',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (220,218,'KS','El Dorado',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (221,218,'KS','Emporia',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (222,218,'KS','Fort Scott',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (223,218,'KS','Garden City',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (224,218,'KS','Gardner',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (225,218,'KS','Great Bend',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (226,218,'KS','Hays',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (227,218,'KS','Haysville',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (228,218,'KS','Hutchinson',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (229,218,'KS','Independence',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (230,218,'KS','Iola',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (231,218,'KS','Junction City',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (232,218,'KS','Kansas City',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (233,218,'KS','Lansing',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (234,218,'KS','Lawrence',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (235,218,'KS','Leavenworth',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (236,218,'KS','Leawood',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (237,218,'KS','Lenexa',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (238,218,'KS','Liberal',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (239,218,'KS','Manhattan',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (240,218,'KS','McPherson',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (241,218,'KS','Merriam',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (242,218,'KS','Mission',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (243,218,'KS','Newton',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (244,218,'KS','Olathe',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (245,218,'KS','Ottowa',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (246,218,'KS','Overland Park',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (247,218,'KS','Parsons',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (248,218,'KS','Pittsburg',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (249,218,'KS','Prairie Village',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (250,218,'KS','Pratt',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (251,218,'KS','Roeland Park',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (252,218,'KS','Salina',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (253,218,'KS','Shawnee',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (254,218,'KS','Topeka',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (255,218,'KS','Wellington',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (256,218,'KS','Wichita',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (257,218,'KS','Winfield',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (258,218,'KY',NULL,NULL,3,-5,'n');
INSERT INTO time_zones VALUES (259,218,'KY','Alexandria',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (260,218,'KY','Ashland',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (261,218,'KY','Bardstown',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (262,218,'KY','Berea',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (263,218,'KY','Bowling Green',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (264,218,'KY','Campbellsville',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (265,218,'KY','Covington',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (266,218,'KY','Danville',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (267,218,'KY','Edgewood',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (268,218,'KY','Elizabethtown',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (269,218,'KY','Elsmere',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (270,218,'KY','Erlanger',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (271,218,'KY','Florence',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (272,218,'KY','Fort Knox',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (273,218,'KY','Fort Mitchell',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (274,218,'KY','Fort Thomas',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (275,218,'KY','Frankfort',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (276,218,'KY','Franklin',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (277,218,'KY','Georgetown',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (278,218,'KY','Glasgow',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (279,218,'KY','Harrodsburg',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (280,218,'KY','Henderson',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (281,218,'KY','Hopkinsville',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (282,218,'KY','Independence',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (283,218,'KY','Jeffersontown',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (284,218,'KY','Lawrenceburg',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (285,218,'KY','Lexington-Fayette',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (286,218,'KY','Louisville',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (287,218,'KY','Lyndon',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (288,218,'KY','Madisonville',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (289,218,'KY','Mayfield',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (290,218,'KY','Maysville',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (291,218,'KY','Middlesborough',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (292,218,'KY','Mount Washington',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (293,218,'KY','Murray',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (294,218,'KY','Newport',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (295,218,'KY','Nicholasville',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (296,218,'KY','Owensboro',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (297,218,'KY','Paducah',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (298,218,'KY','Paris',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (299,218,'KY','Radcliff',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (300,218,'KY','Richmond',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (301,218,'KY','Shelbyville',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (302,218,'KY','Shepherdsville',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (303,218,'KY','Shively',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (304,218,'KY','Somerset',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (305,218,'KY','St. Matthews',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (306,218,'KY','Winchester',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (307,218,'LA',NULL,NULL,3,-6,'y');
INSERT INTO time_zones VALUES (308,218,'ME',NULL,NULL,3,-5,'y');
INSERT INTO time_zones VALUES (309,218,'MD',NULL,NULL,3,-5,'y');
INSERT INTO time_zones VALUES (310,218,'MA',NULL,NULL,3,-5,'y');
INSERT INTO time_zones VALUES (311,218,'MI',NULL,NULL,3,-5,'n');
INSERT INTO time_zones VALUES (312,218,'MI','Adrian',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (313,218,'MI','Allen Park',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (314,218,'MI','Anne Arbor',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (315,218,'MI','Auburn Hills',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (316,218,'MI','Battle Creek',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (317,218,'MI','Bedford',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (318,218,'MI','Birmingham',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (319,218,'MI','Blackman',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (320,218,'MI','Bloomfield',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (321,218,'MI','Brownstown',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (322,218,'MI','Burton',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (323,218,'MI','Canton',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (324,218,'MI','Chesterfield',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (325,218,'MI','Clinton',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (326,218,'MI','Commerce',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (327,218,'MI','Davison',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (328,218,'MI','Dearborn',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (329,218,'MI','Delhi',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (330,218,'MI','Delta',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (331,218,'MI','Detroit',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (332,218,'MI','East Lansing',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (333,218,'MI','Eastpointe',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (334,218,'MI','Farmington Hills',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (335,218,'MI','Ferndale',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (336,218,'MI','Flint',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (337,218,'MI','Forest Hills',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (338,218,'MI','Frenchtown',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (339,218,'MI','Gaines',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (340,218,'MI','Garden City',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (341,218,'MI','Genesee',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (342,218,'MI','Georgetown',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (343,218,'MI','Grand Blanc',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (344,218,'MI','Grand Rapids',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (345,218,'MI','Hamburg',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (346,218,'MI','Hamtramck',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (347,218,'MI','Harrison',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (348,218,'MI','Hazel Park',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (349,218,'MI','Highland',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (350,218,'MI','Holland',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (351,218,'MI','Independence',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (352,218,'MI','Inkster',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (353,218,'MI','Jackson',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (354,218,'MI','Kalamazoo',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (355,218,'MI','Kentwood',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (356,218,'MI','Lansing',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (357,218,'MI','Lincoln Park',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (358,218,'MI','Livonia',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (359,218,'MI','Macomb',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (360,218,'MI','Madison Heights',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (361,218,'MI','Mariquette',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (362,218,'MI','Meridian',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (363,218,'MI','Midland',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (364,218,'MI','Monroe',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (365,218,'MI','Mount Morris',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (366,218,'MI','Mount Pleasant',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (367,218,'MI','Muskegon',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (368,218,'MI','Northville',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (369,218,'MI','Norton Shores',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (370,218,'MI','Novi',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (371,218,'MI','Oak Park',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (372,218,'MI','Okemos',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (373,218,'MI','Orion',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (374,218,'MI','Pittsfield',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (375,218,'MI','Plainfield',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (376,218,'MI','Plymouth',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (377,218,'MI','Pontiac',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (378,218,'MI','Port Huron',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (379,218,'MI','Portage',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (380,218,'MI','Redford',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (381,218,'MI','Rochester Hills',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (382,218,'MI','Romulus',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (383,218,'MI','Roseville',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (384,218,'MI','Royal Oak',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (385,218,'MI','Saginaw',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (386,218,'MI','Shelby',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (387,218,'MI','Southfield',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (388,218,'MI','Southgate',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (389,218,'MI','St. Clair Shores',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (390,218,'MI','Sterling Heights',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (391,218,'MI','Summit',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (392,218,'MI','Taylor',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (393,218,'MI','Trenton',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (394,218,'MI','Troy',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (395,218,'MI','Van Buren',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (396,218,'MI','Walker',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (397,218,'MI','Warren',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (398,218,'MI','Washington',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (399,218,'MI','Waterford',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (400,218,'MI','Wayne',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (401,218,'MI','West Bloomfield',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (402,218,'MI','Westland',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (403,218,'MI','White Lake',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (404,218,'MI','Wyandotte',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (405,218,'MI','Wyoming',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (406,218,'MI','Ypsilanti',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (407,218,'MN',NULL,NULL,3,-6,'y');
INSERT INTO time_zones VALUES (408,218,'MS',NULL,NULL,3,-6,'y');
INSERT INTO time_zones VALUES (409,218,'MO',NULL,NULL,3,-6,'y');
INSERT INTO time_zones VALUES (410,218,'MT',NULL,NULL,3,-7,'y');
INSERT INTO time_zones VALUES (411,218,'NE',NULL,NULL,3,-6,'n');
INSERT INTO time_zones VALUES (412,218,'NE','Alliance',NULL,3,-7,'y');
INSERT INTO time_zones VALUES (413,218,'NE','Beatrice',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (414,218,'NE','Bellevue',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (415,218,'NE','Blair',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (416,218,'NE','Chadron',NULL,3,-7,'y');
INSERT INTO time_zones VALUES (417,218,'NE','Chalco',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (418,218,'NE','Columbus',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (419,218,'NE','Crete',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (420,218,'NE','Elkhorn',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (421,218,'NE','Fremont',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (422,218,'NE','Gering',NULL,3,-7,'y');
INSERT INTO time_zones VALUES (423,218,'NE','Grand Island',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (424,218,'NE','Hastings',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (425,218,'NE','Holdrege',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (426,218,'NE','Kearney',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (427,218,'NE','La Vista',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (428,218,'NE','Lexington',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (429,218,'NE','Lincoln',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (430,218,'NE','McCook',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (431,218,'NE','Nebraska City',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (432,218,'NE','Norfolk',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (433,218,'NE','North Platte',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (434,218,'NE','Offutt AFB',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (435,218,'NE','Omaha',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (436,218,'NE','Papillion',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (437,218,'NE','Plattsmouth',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (438,218,'NE','Ralston',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (439,218,'NE','Scottsbluff',NULL,3,-7,'y');
INSERT INTO time_zones VALUES (440,218,'NE','Seward',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (441,218,'NE','Sidney',NULL,3,-7,'y');
INSERT INTO time_zones VALUES (442,218,'NE','South Sioux City',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (443,218,'NE','York',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (444,218,'NV',NULL,NULL,3,-8,'y');
INSERT INTO time_zones VALUES (445,218,'NH',NULL,NULL,3,-5,'y');
INSERT INTO time_zones VALUES (446,218,'NJ',NULL,NULL,3,-5,'y');
INSERT INTO time_zones VALUES (447,218,'NM',NULL,NULL,3,-7,'y');
INSERT INTO time_zones VALUES (448,218,'NY',NULL,NULL,3,-5,'y');
INSERT INTO time_zones VALUES (449,218,'NC',NULL,NULL,3,-5,'y');
INSERT INTO time_zones VALUES (450,218,'ND',NULL,NULL,3,-6,'n');
INSERT INTO time_zones VALUES (451,218,'ND','Bismarck',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (452,218,'ND','Devils Lake',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (453,218,'ND','Dickinson',NULL,3,-7,'y');
INSERT INTO time_zones VALUES (454,218,'ND','Fargo',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (455,218,'ND','Grand Forks',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (456,218,'ND','Jamestown',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (457,218,'ND','Mandan',NULL,3,-7,'y');
INSERT INTO time_zones VALUES (458,218,'ND','Minot',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (459,218,'ND','Valley City',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (460,218,'ND','Wahpeton',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (461,218,'ND','West Fargo',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (462,218,'ND','Williston',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (463,218,'OH',NULL,NULL,3,-5,'y');
INSERT INTO time_zones VALUES (464,218,'OK',NULL,NULL,3,-6,'y');
INSERT INTO time_zones VALUES (465,218,'OR',NULL,NULL,3,-8,'n');
INSERT INTO time_zones VALUES (466,218,'OR','Albany',NULL,3,-8,'y');
INSERT INTO time_zones VALUES (467,218,'OR','Aloha',NULL,3,-8,'y');
INSERT INTO time_zones VALUES (468,218,'OR','Altamont',NULL,3,-8,'y');
INSERT INTO time_zones VALUES (469,218,'OR','Ashland',NULL,3,-8,'y');
INSERT INTO time_zones VALUES (470,218,'OR','Beaverton',NULL,3,-8,'y');
INSERT INTO time_zones VALUES (471,218,'OR','Bend',NULL,3,-8,'y');
INSERT INTO time_zones VALUES (472,218,'OR','Canby',NULL,3,-8,'y');
INSERT INTO time_zones VALUES (473,218,'OR','Cedar Mill',NULL,3,-8,'y');
INSERT INTO time_zones VALUES (474,218,'OR','Central Point',NULL,3,-8,'y');
INSERT INTO time_zones VALUES (475,218,'OR','City of The Dalles',NULL,3,-8,'y');
INSERT INTO time_zones VALUES (476,218,'OR','Coos Bay',NULL,3,-8,'y');
INSERT INTO time_zones VALUES (477,218,'OR','Corvalis',NULL,3,-8,'y');
INSERT INTO time_zones VALUES (478,218,'OR','Dallas',NULL,3,-8,'y');
INSERT INTO time_zones VALUES (479,218,'OR','Eugene',NULL,3,-8,'y');
INSERT INTO time_zones VALUES (480,218,'OR','Forest Grove',NULL,3,-8,'y');
INSERT INTO time_zones VALUES (481,218,'OR','Four Corners',NULL,3,-8,'y');
INSERT INTO time_zones VALUES (482,218,'OR','Gladstone',NULL,3,-8,'y');
INSERT INTO time_zones VALUES (483,218,'OR','Grants Pass',NULL,3,-8,'y');
INSERT INTO time_zones VALUES (484,218,'OR','Gresham',NULL,3,-8,'y');
INSERT INTO time_zones VALUES (485,218,'OR','Hayesville',NULL,3,-8,'y');
INSERT INTO time_zones VALUES (486,218,'OR','Hermiston',NULL,3,-8,'y');
INSERT INTO time_zones VALUES (487,218,'OR','Hillsboro',NULL,3,-8,'y');
INSERT INTO time_zones VALUES (488,218,'OR','Keizer',NULL,3,-8,'y');
INSERT INTO time_zones VALUES (489,218,'OR','Klamath Falls',NULL,3,-8,'y');
INSERT INTO time_zones VALUES (490,218,'OR','La Grande',NULL,3,-8,'y');
INSERT INTO time_zones VALUES (491,218,'OR','Lake Oswego',NULL,3,-8,'y');
INSERT INTO time_zones VALUES (492,218,'OR','Lebanon',NULL,3,-8,'y');
INSERT INTO time_zones VALUES (493,218,'OR','McMinnville',NULL,3,-8,'y');
INSERT INTO time_zones VALUES (494,218,'OR','Medford',NULL,3,-8,'y');
INSERT INTO time_zones VALUES (495,218,'OR','Milwaukie',NULL,3,-8,'y');
INSERT INTO time_zones VALUES (496,218,'OR','Newberg',NULL,3,-8,'y');
INSERT INTO time_zones VALUES (497,218,'OR','Oak Grove',NULL,3,-8,'y');
INSERT INTO time_zones VALUES (498,218,'OR','Oatfield',NULL,3,-8,'y');
INSERT INTO time_zones VALUES (499,218,'OR','Ontario',NULL,3,-7,'y');
INSERT INTO time_zones VALUES (500,218,'OR','Oregon City',NULL,3,-8,'y');
INSERT INTO time_zones VALUES (501,218,'OR','Pendleton',NULL,3,-8,'y');
INSERT INTO time_zones VALUES (502,218,'OR','Portland',NULL,3,-8,'y');
INSERT INTO time_zones VALUES (503,218,'OR','Redmond',NULL,3,-8,'y');
INSERT INTO time_zones VALUES (504,218,'OR','Roseburg',NULL,3,-8,'y');
INSERT INTO time_zones VALUES (505,218,'OR','Salem',NULL,3,-8,'y');
INSERT INTO time_zones VALUES (506,218,'OR','Sherwood',NULL,3,-8,'y');
INSERT INTO time_zones VALUES (507,218,'OR','Springfield',NULL,3,-8,'y');
INSERT INTO time_zones VALUES (508,218,'OR','Tigard',NULL,3,-8,'y');
INSERT INTO time_zones VALUES (509,218,'OR','Troutdale',NULL,3,-8,'y');
INSERT INTO time_zones VALUES (510,218,'OR','Tualatin',NULL,3,-8,'y');
INSERT INTO time_zones VALUES (511,218,'OR','West Linn',NULL,3,-8,'y');
INSERT INTO time_zones VALUES (512,218,'OR','Wilsonville',NULL,3,-8,'y');
INSERT INTO time_zones VALUES (513,218,'OR','Woddburn',NULL,3,-8,'y');
INSERT INTO time_zones VALUES (514,218,'PA',NULL,NULL,3,-5,'y');
INSERT INTO time_zones VALUES (515,218,'PR',NULL,NULL,1,-4,'y');
INSERT INTO time_zones VALUES (516,218,'RI',NULL,NULL,3,-5,'y');
INSERT INTO time_zones VALUES (517,218,'SC',NULL,NULL,3,-5,'y');
INSERT INTO time_zones VALUES (518,218,'SD',NULL,NULL,3,-6,'n');
INSERT INTO time_zones VALUES (519,218,'SD','Aberdeen',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (520,218,'SD','Brookings',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (521,218,'SD','Huron',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (522,218,'SD','Mitchell',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (523,218,'SD','Pierre',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (524,218,'SD','Rapid City',NULL,3,-7,'y');
INSERT INTO time_zones VALUES (525,218,'SD','Rapid Valley',NULL,3,-7,'y');
INSERT INTO time_zones VALUES (526,218,'SD','Sioux Falls',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (527,218,'SD','Spearfish',NULL,3,-7,'y');
INSERT INTO time_zones VALUES (528,218,'SD','Vermillion',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (529,218,'SD','Watertown',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (530,218,'SD','Yankton',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (531,218,'TN',NULL,NULL,3,-6,'n');
INSERT INTO time_zones VALUES (532,218,'TN','Alcoa',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (533,218,'TN','Athens',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (534,218,'TN','Bartlett',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (535,218,'TN','Bloomingdale',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (536,218,'TN','Brentwood',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (537,218,'TN','Bristol',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (538,218,'TN','Brownsville',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (539,218,'TN','Chattanooga',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (540,218,'TN','Clarksville',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (541,218,'TN','Cleveland',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (542,218,'TN','Clinton',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (543,218,'TN','Collegedale',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (544,218,'TN','Collierville',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (545,218,'TN','Colonial Heights',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (546,218,'TN','Columbia',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (547,218,'TN','Cookeville',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (548,218,'TN','Covington',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (549,218,'TN','Crossville',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (550,218,'TN','Dickson',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (551,218,'TN','Dyersburg',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (552,218,'TN','East Brainerd',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (553,218,'TN','East Ridge',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (554,218,'TN','Elizabethton',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (555,218,'TN','Farragut',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (556,218,'TN','Fayetteville',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (557,218,'TN','Franklin',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (558,218,'TN','Gallatin',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (559,218,'TN','Germantown',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (560,218,'TN','Goodlettsville',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (561,218,'TN','Green Hill',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (562,218,'TN','Greeneville',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (563,218,'TN','Harriman',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (564,218,'TN','Harrison',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (565,218,'TN','Hendersonville',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (566,218,'TN','Humboldt',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (567,218,'TN','Jackson',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (568,218,'TN','Jefferson City',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (569,218,'TN','Johnson City',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (570,218,'TN','Kingsport',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (571,218,'TN','Knoxville',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (572,218,'TN','La Follette',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (573,218,'TN','La Vergne',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (574,218,'TN','Lakeland',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (575,218,'TN','Lawrenceburg',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (576,218,'TN','Lebanon',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (577,218,'TN','Lenoir City',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (578,218,'TN','Lewisburg',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (579,218,'TN','Lexington',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (580,218,'TN','Manchester',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (581,218,'TN','Martin',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (582,218,'TN','Maryville',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (583,218,'TN','McMinnville',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (584,218,'TN','Memphis',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (585,218,'TN','Middle Valley',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (586,218,'TN','Milan',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (587,218,'TN','Millington',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (588,218,'TN','Morristown',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (589,218,'TN','Mount Juliet',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (590,218,'TN','Murfreesboro',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (591,218,'TN','Nashville-Davidson',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (592,218,'TN','Newport',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (593,218,'TN','Oak Ridge',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (594,218,'TN','Paris',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (595,218,'TN','Portland',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (596,218,'TN','Pulaski',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (597,218,'TN','Red Bank',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (598,218,'TN','Ripley',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (599,218,'TN','Savannah',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (600,218,'TN','Sevierville',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (601,218,'TN','Seymour',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (602,218,'TN','Shelbyville',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (603,218,'TN','Signal Mountain',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (604,218,'TN','Smyrna',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (605,218,'TN','Soddy-Daisy',NULL,3,-5,'y');
INSERT INTO time_zones VALUES (606,218,'TN','Spring Hill',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (607,218,'TN','Springfield',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (608,218,'TN','Tullahoma',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (609,218,'TN','Union',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (610,218,'TN','White House',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (611,218,'TN','Winchester',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (612,218,'TX',NULL,NULL,3,-6,'n');
INSERT INTO time_zones VALUES (613,218,'TX','Abilene',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (614,218,'TX','Allen',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (615,218,'TX','Amarillo',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (616,218,'TX','Arlington',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (617,218,'TX','Atascocita',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (618,218,'TX','Austin',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (619,218,'TX','Baytown',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (620,218,'TX','Beaumont',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (621,218,'TX','Bedford',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (622,218,'TX','Big Spring',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (623,218,'TX','Brownsville',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (624,218,'TX','Bryan',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (625,218,'TX','Carrollton',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (626,218,'TX','Cedar Hill',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (627,218,'TX','Cedar Park',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (628,218,'TX','Channelview',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (629,218,'TX','Cleburne',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (630,218,'TX','College Station',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (631,218,'TX','Conroe',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (632,218,'TX','Coppell',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (633,218,'TX','Copperas Cove',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (634,218,'TX','Corpus Christi',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (635,218,'TX','Corsicana',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (636,218,'TX','Dallas',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (637,218,'TX','Deer Park',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (638,218,'TX','Del Rio',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (639,218,'TX','Denton',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (640,218,'TX','DeSoto',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (641,218,'TX','Duncanville',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (642,218,'TX','Edinburg',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (643,218,'TX','El Paso',NULL,3,-7,'y');
INSERT INTO time_zones VALUES (644,218,'TX','Euless',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (645,218,'TX','Farmers Branch',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (646,218,'TX','Flower Mound',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (647,218,'TX','Fort Hood',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (648,218,'TX','Fort Worth',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (649,218,'TX','Friendswood',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (650,218,'TX','Frisco',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (651,218,'TX','Galveston',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (652,218,'TX','Garland',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (653,218,'TX','Georgetown',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (654,218,'TX','Grand Prairie',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (655,218,'TX','Grapevine',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (656,218,'TX','Haltom City',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (657,218,'TX','Harlingen',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (658,218,'TX','Houston',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (659,218,'TX','Huntsville',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (660,218,'TX','Hurst',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (661,218,'TX','Irving',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (662,218,'TX','Keller',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (663,218,'TX','Killeen',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (664,218,'TX','Kingsville',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (665,218,'TX','La Porte',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (666,218,'TX','Lake Jackson',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (667,218,'TX','Lancaster',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (668,218,'TX','Laredo',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (669,218,'TX','League City',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (670,218,'TX','Lewisville',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (671,218,'TX','Longview',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (672,218,'TX','Lubbock',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (673,218,'TX','Lufkin',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (674,218,'TX','Mansfield',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (675,218,'TX','McAllen',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (676,218,'TX','McKinney',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (677,218,'TX','Mesquite',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (678,218,'TX','Midland',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (679,218,'TX','Mission Bend',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (680,218,'TX','Mission',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (681,218,'TX','Missouri City',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (682,218,'TX','Nacogdoches',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (683,218,'TX','New Braunfels',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (684,218,'TX','North Richland Hills',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (685,218,'TX','Odessa',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (686,218,'TX','Paris',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (687,218,'TX','Pasadena',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (688,218,'TX','Pearland',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (689,218,'TX','Pharr',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (690,218,'TX','Plano',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (691,218,'TX','Port Arthur',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (692,218,'TX','Richardson',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (693,218,'TX','Round Rock',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (694,218,'TX','Rowlett',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (695,218,'TX','San Angelo',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (696,218,'TX','San Antonio',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (697,218,'TX','San Juan',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (698,218,'TX','San Marcos',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (699,218,'TX','Sherman',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (700,218,'TX','Socorro',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (701,218,'TX','Spring',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (702,218,'TX','Sugar Land',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (703,218,'TX','Temple',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (704,218,'TX','Texarkana',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (705,218,'TX','Texas City',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (706,218,'TX','The Colony',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (707,218,'TX','The Woodlands',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (708,218,'TX','Tyler',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (709,218,'TX','Victoria',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (710,218,'TX','Waco',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (711,218,'TX','Weslaco',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (712,218,'TX','Wichita Falls',NULL,3,-6,'y');
INSERT INTO time_zones VALUES (713,218,'UT',NULL,NULL,3,-7,'y');
INSERT INTO time_zones VALUES (714,218,'VT',NULL,NULL,3,-5,'y');
INSERT INTO time_zones VALUES (715,218,'VA',NULL,NULL,3,-5,'y');
INSERT INTO time_zones VALUES (716,218,'WA',NULL,NULL,3,-8,'y');
INSERT INTO time_zones VALUES (717,218,'DC',NULL,NULL,3,-5,'y');
INSERT INTO time_zones VALUES (718,218,'WV',NULL,NULL,3,-5,'y');
INSERT INTO time_zones VALUES (719,218,'WI',NULL,NULL,3,-6,'y');
INSERT INTO time_zones VALUES (720,218,'WY',NULL,NULL,3,-7,'y');

--
-- Table structure for table `users`
--

CREATE TABLE users (
  user_id int(11) NOT NULL auto_increment,
  user_contact_id int(11) NOT NULL default '0',
  role_id int(11) NOT NULL default '0',
  username varchar(100) NOT NULL default '',
  password varchar(100) NOT NULL default '',
  last_name varchar(100) NOT NULL default '',
  first_names varchar(100) NOT NULL default '',
  email varchar(100) NOT NULL default '',
  language varchar(50) NOT NULL default 'english',
  gmt_offset int(11) NOT NULL default '0',
  last_hit datetime default NULL,
  user_record_status char(1) default 'a',
  PRIMARY KEY  (user_id),
  UNIQUE KEY username (username)
) TYPE=MyISAM;

--
-- Dumping data for table `users`
--


INSERT INTO users VALUES (1,0,2,'@@ADMIN_NAME@@','@@ADMIN_PASSWD@@','One','User','@@ADMIN_EMAIL@@','english',0,NULL,'a');

