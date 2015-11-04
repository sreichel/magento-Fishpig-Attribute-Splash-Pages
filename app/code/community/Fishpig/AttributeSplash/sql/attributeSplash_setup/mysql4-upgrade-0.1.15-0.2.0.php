<?php

	
	$this->startSetup();
	$this->run("

		ALTER TABLE {$this->getTable('attributesplash_attribute_option_extra')} ADD layout_update_xml TEXT NOT NULL default '' AFTER cms_block;

	");
	
	$this->endSetup();
	
	
	
