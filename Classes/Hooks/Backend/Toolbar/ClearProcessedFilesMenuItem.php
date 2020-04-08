<?php declare(strict_types = 1);

namespace JosefGlatz\ToolbarClearProcessedfiles\Hooks\Backend\Toolbar;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Backend\Routing\Exception\RouteNotFoundException;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Backend\Toolbar\ClearCacheActionsHookInterface;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Http\Response;
use TYPO3\CMS\Core\Resource\ProcessedFileRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Add a CacheMenuItem for clearing processed files.
 *
 * Class ClearProcessedFilesMenuItem
 */
class ClearProcessedFilesMenuItem implements ClearCacheActionsHookInterface
{
    /**
     * Add an entry to CacheMenuItems array
     *
     * @param array $cacheActions Array of CacheMenuItems
     * @param array $optionValues Array of AccessConfigurations-identifiers (typically  used by userTS with options.clearCache.identifier)
     * @throws RouteNotFoundException
     */
    public function manipulateCacheActions(&$cacheActions, &$optionValues)
    {
        $backendUser = $this->getBackendUser();
        $languagePrefix = 'LLL:EXT:toolbar_clear_processedfiles/Resources/Private/Language/locallang_be.xlf:clearcacheaction.clear.processedfiles.';
        $menuItemPath = (string)$this->getUriBuilder()->buildUriFromRoute('ajax_' . 'toolbar_clear_processedfiles');

        if ($backendUser->isAdmin() && ((bool)($backendUser->getTSConfig()['options.']['clearCache.']['processedfiles'] ?? false) || $this->applicationContextIsDevelopment())) {
            $cacheActions[] = [
                'id' => 'toolbar_clear_processedfiles',
                'title' => $languagePrefix . 'title',
                'description' => $languagePrefix . 'description',
                'href' => $menuItemPath,
                'iconIdentifier' => 'toolbar-clear-processedfiles-button'
            ];
            $optionValues[] = 'toolbar_clear_processedfiles';
        }
    }

    /**
     * Clear processed files
     *
     * The sys_file_processedfile table is truncated and the physical files of local storages are deleted.
     */
    public function clearProcessedFiles(): ResponseInterface
    {
        $repository = GeneralUtility::makeInstance(ProcessedFileRepository::class);
        $repository->removeAll();

        return new Response();
    }

    /**
     * Returns the current BE user.
     *
     * @return BackendUserAuthentication
     */
    protected function getBackendUser(): BackendUserAuthentication
    {
        return $GLOBALS['BE_USER'];
    }

    /**
     * Check if current applicationContext is a development specific context
     *
     * @return bool
     */
    protected function applicationContextIsDevelopment(): bool
    {
        return Environment::getContext()->isDevelopment()
            || (Environment::getContext()->isProduction()
                && (string)Environment::getContext() === 'Production/Dev');
    }

    /**
     * Returns UriBuilder
     *
     * @return UriBuilder
     */
    protected function getUriBuilder(): UriBuilder
    {
        return GeneralUtility::makeInstance(UriBuilder::class);
    }
}
