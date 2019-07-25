<?php
namespace Grav\Plugin;

use \Grav\Common\Plugin;
use \Grav\Common\Grav;
use \Grav\Common\Page\Page;

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
        /** @var Page $page */
        $page = $this->grav['page'];
        $config = $this->mergeConfig($page);
        $featherlight = $this->config->get('plugins.featherlight.enabled');

        $this->active = $config->get('active') && !$featherlight;

        if ($this->active) {
            $this->enable([
                'onTwigSiteVariables' => ['onTwigSiteVariables', 0]
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
     * Generate initialization JS snippet
     */
    protected function getInitJs($config)
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
                $config['closeOnWindowResize'],
                $config['enableGrab'],
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
}
