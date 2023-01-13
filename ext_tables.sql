
CREATE TABLE `tx_digitalmarketingframework_domain_model_invalidrequest` (
	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	count int(11) unsigned DEFAULT '0' NOT NULL,
	identifier text DEFAULT '' NOT NULL,
	KEY ip (ip)
);
