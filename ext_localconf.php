<?php
defined('TYPO3_MODE') || die('Access denied.');

// Register custom cache action item: clear processed files
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['additionalBackendItems']['cacheActions'][] = \JosefGlatz\ToolbarClearProcessedfiles\Hooks\Backend\Toolbar\ClearProcessedFilesMenuItem::class;
