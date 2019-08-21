# Grav Zooming Plugin

Image zoom that makes sense.

`zooming` is a simple [Grav](http://github.com/getgrav/grav) plugin that adds **image zoom / lightbox** functionality via [Zooming](https://github.com/kingdido999/zooming) and acts as an alternative to the [featherlight](https://github.com/getgrav/grav-plugin-featherlight) plugin.

# Installation

Installing the Zooming plugin can be done in one of two ways. Our GPM (Grav Package Manager) installation method enables you to quickly and easily install the plugin with a simple terminal command, while the manual method enables you to do so via a zip file.

## GPM Installation (Preferred)

The simplest way to install this plugin is via the [Grav Package Manager (GPM)](http://learn.getgrav.org/advanced/grav-gpm) through your system's Terminal (also called the command line). From the root of your Grav install type:

    bin/gpm install zooming

This will install the Zooming plugin into your `/user/plugins` directory within Grav. Its files can be found under `/your/site/grav/user/plugins/zooming`.

## Manual Installation

To install this plugin, just download the zip version of this repository and unzip it under `/your/site/grav/user/plugins`. Then, rename the folder to `zooming`. You can find these files either on [GitHub](https://github.com/JulianSchoenbaechler/grav-plugin-zooming) or via [GetGrav.org](http://getgrav.org/downloads/plugins#extras).

You should now have all the plugin files under

    /your/site/grav/user/plugins/zooming

> NOTE: This plugin is a modular component for Grav which requires [Grav](http://github.com/getgrav/grav) to function

# Usage

To best understand how Zooming works, you should read through the original project [documentation](https://desmonding.me/zooming/docs/).

> NOTE: Zooming is intended to be used as an alternative to Featherlight. If featherlight is currently enabled, the plugin will stay inactive.

## Configuration

Zooming is **enabled** and **active** by default. You can change this behavior by setting `active: false` in the plugin's configuration. Simply copy the `user/plugins/zooming/zooming.yaml` into `user/config/plugins/zooming.yaml` and make your modifications.

```
enabled: true                                           # global enable/disable the entire plugin
active: true                                            # if the plugin is active and JS/CSS should be loaded
bgColor: 'rgb(255, 255, 255)'                           # background color of overlay
bgOpacity: 1                                            # background opacity of overlay
closeOnWindowResize: true                               # close the zoomed image when browser window is resized
enableGrab: true                                        # enable grabbing and dragging the image
preloadImage: false                                     # preload zoomable images
scaleBase: 1.0                                          # the base scale factor for zooming
scaleExtra: 0.5                                         # the additional scale factor while grabbing the image
scrollThreshold: 40                                     # how much scrolling it takes before closing out the instance
transitionDuration: 0.4                                 # transition duration in seconds
transitionTimingFunction: 'cubic-bezier(0.4, 0, 0, 1)'  # transition timing function
zIndex: 998                                             # the z-index that the overlay will be added with
initTemplate: plugin://zooming/js/zooming.init.js       # path to template file for JS init script
```

You can also override any default setings from the page headers:

eg:

    ---
    title: Sample Code With Custom Settings
    zooming:
        enableGrab: false
        transitionDuration: 0.2
        zIndex: 2
    ---


You can also disable Zooming for a particular page:

    ---
    title: Sample Code with Zooming disabled
    zooming:
        active: false
    ---

## Implementing a lightbox with Zooming

To implement a lightbox using Zooming in Grav, you must output the proper HTML output. Luckily Grav already takes care of this for you if you are using Grav media files.

In markdown this could look something like:

```
![Sample Image](sample-image.jpg?lightbox=1024&cropResize=200,200)
```

In Twig this could look like:

```
{{ page.media['sample-image.jpg'].lightbox(1024,768).cropResize(200,200).html('Sample Image') }}
```

More details can be found in the [Grav documentation for Media functionality](http://learn.getgrav.org/content/media).

# Updating

As development for the Zooming plugin continues, new versions may become available that add additional features and functionality, improve compatibility with newer Grav releases, and generally provide a better user experience. Updating Zooming is easy, and can be done through Grav's GPM system, as well as manually.

## GPM Update (Preferred)

The simplest way to update this plugin is via the [Grav Package Manager (GPM)](http://learn.getgrav.org/advanced/grav-gpm). You can do this by navigating to the root directory of your Grav install using your system's terminal (also called command line) and typing the following:

    bin/gpm update zooming

This command will check your Grav install to see if your Zooming plugin is due for an update. If a newer release is found, you will be asked whether or not you wish to update. To continue, type `y` and hit enter. The plugin will automatically update and clear Grav's cache.

## Manual Update

Manually updating Zooming is pretty simple. Here is what you will need to do to get this done:

* Delete the `your/site/user/plugins/zooming` directory.
* Download the new version of the Zooming plugin from either [GitHub](https://github.com/JulianSchoenbaechler/grav-plugin-zooming) or [GetGrav.org](http://getgrav.org/downloads/plugins#extras).
* Unzip the zip file in `your/site/user/plugins` and rename the resulting folder to `zooming`.
* Clear the Grav cache. The simplest way to do this is by going to the root Grav directory in terminal and typing `bin/grav clear-cache`.

> Note: Any changes you have made to any of the files listed under this directory will also be removed and replaced by the new set. Any files located elsewhere (for example a YAML settings file placed in `user/config/plugins`) will remain intact.
