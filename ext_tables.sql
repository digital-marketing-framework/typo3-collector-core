
CREATE TABLE `tx_digitalmarketingframework_domain_model_invalidrequest` (
	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	count int(11) unsigned DEFAULT '0' NOT NULL,
	identifier varchar(1024) DEFAULT '' NOT NULL,
	KEY key_identifier (identifier)
);
