
CREATE TABLE `tx_dmfcollectorcore_domain_model_invalidrequest` (
	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	count int(11) unsigned DEFAULT '0' NOT NULL,
	identifier varchar(1024) DEFAULT '' NOT NULL,
	KEY key_identifier (identifier)
);

CREATE TABLE tt_content (
	tx_dmf_collector_core_content_modifier text DEFAULT '' NOT NULL
);

CREATE TABLE pages (
	tx_dmf_collector_core_content_modifier text DEFAULT '' NOT NULL
);
