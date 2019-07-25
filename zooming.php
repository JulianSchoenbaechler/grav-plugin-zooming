<?php

namespace Grav\Plugin;

use Grav\Common\Plugin;
use Grav\Common\Page\Page;
use RocketTheme\Toolbox\Event\Event;
use DiDom\Document;

class ZoomingPlugin extends Plugin
{
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'onPluginsInitialized' => ['onPluginsInitialized', 0]
        ];
    }

    /**
     * Initialize configuration
     */
    public function onPluginsInitialized()
    {
        if ($this->isAdmin()) {
            $this->active = false;
            return;
        }

        $this->enable([
            'onPageInitialized' => ['onPageInitialized', 0]
        ]);
    }

    /**
     * Initialize configuration
     */
    public function onPageInitialized()
    {
        require_once(__DIR__ . '/vendor/autoload.php');

        /** @var Page $page */
        $page = $this->grav['page'];
        $config = $this->mergeConfig($page);
        $featherlight = $this->config->get('plugins.featherlight.enabled');

        $this->active = $config->get('active') && !$featherlight;

        if ($this->active) {
            $this->enable([
                'onTwigSiteVariables' => ['onTwigSiteVariables', 0],
                'onPageContentProcessed' => ['onPageContentProcessed', 0]
            ]);
        }
    }

    /**
     * If enabled on this page, load the JS + CSS theme
     */
    public function onTwigSiteVariables()
    {
        /** @var Page $page */
        $page = $this->grav['page'];
        $config = $this->mergeConfig($page);

        $init = $this->getInitJs($config);

        $this->grav['assets']
            ->addJs('plugin://zooming/js/zooming.min.js')
            ->addInlineJs($init, ['group' => 'bottom']);
    }

    /**
     * Process the content and let the cache serve it again
     *
     * @param Event $event
     */
    public function onPageContentProcessed(Event $event)
    {
        /** @var Page $page */
        $page = $event['page'];

        $content = $this->manipulateDataAttributes($page->content());
        $page->setRawContent($content);
    }

    /**
     * Generate initialization JS snippet
     *
     * @param object $config
     * @return string
     */
    protected function getInitJs(object $config)
    {
        $asset = $this->grav['locator']->findResource($config['initTemplate'], false);
        $init = file_get_contents(ROOT_DIR . $asset);

        $init = str_replace(
            array(
                '{bgColor}',
                '{bgOpacity}',
                '{closeOnWindowResize}',
                '{enableGrab}',
                '{preloadImage}',
                '{scaleBase}',
                '{scaleExtra}',
                '{scrollThreshold}',
                '{transitionDuration}',
                '{transitionTimingFunction}',
                '{zIndex}'
            ),
            array(
                $config['bgColor'],
                $config['bgOpacity'],
                $config['closeOnWindowResize'] ? 'true' : 'false',
                $config['enableGrab'] ? 'true' : 'false',
                $config['preloadImage'] ? 'true' : 'false',
                $config['scaleBase'],
                $config['scaleExtra'],
                $config['scrollThreshold'],
                $config['transitionDuration'],
                $config['transitionTimingFunction'],
                $config['zIndex']
            ),
            $init
        );

        return $init;
    }

    /**
     * Search for lightbox 'data-' attributes and replace them with the ones compatible for Zooming
     *
     * @param string $content
     * @return string
     */
    protected function manipulateDataAttributes(string $content)
    {
        if (strlen($content) === 0) {
            return '';
        }

        $document = new Document($content);
        $lightboxes = $document->find('a[rel="lightbox"]');

        foreach ($lightboxes as $lightbox) {
            $image = $lightbox->firstInDocument('img');

            if (!$image) {
                continue;
            }

            if ($width = $lightbox->getAttribute('data-width')) {
                $image->setAttribute('data-zooming-width', $width);
                $lightbox->removeAttribute('data-width');
            }

            if ($height = $lightbox->getAttribute('data-height')) {
                $image->setAttribute('data-zooming-height', $height);
                $lightbox->removeAttribute('data-height');
            }
        }

        return $document->html();
    }
}
