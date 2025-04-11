<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */
dov_the_nav(
	'Header Main',
	false,
	array(
		'walker' => new DOV_Mega_Menu_Walker_Main(),
		'hover'  => true,
	)
);
