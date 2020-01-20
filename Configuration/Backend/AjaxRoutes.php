<?php

return [
    'toolbar_clear_processedfiles' => [
        'path' => '/toolbar/clear/processedfiles',
        'target' => \JosefGlatz\ToolbarClearProcessedfiles\Hooks\Backend\Toolbar\ClearProcessedFilesMenuItem::class . '::clearProcessedFiles',
        'parameters' => [
        ]
    ],
];
