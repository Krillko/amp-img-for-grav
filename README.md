# Ampimage Plugin

The **Ampimage** Plugin is for [Grav CMS](http://github.com/getgrav/grav) used together with [Google Accelerated Mobile Pages](https://www.ampproject.org)

## Getting started with AMP
Grav doesn't support AMP out of the box but it's pretty easy to get started. I've created a minimal theme to get you started: [Amp skeleton theme](https://github.com/Krillko/amp-skeleton)

## Usage
The plugin replaces html `<img>` with AMP-images.
Just add images as usual in grav, and they will be replaced.
    
    ![Alternative text](my-example-image.jpg)

## Installation

To install this plugin, just download the zip version of this repository and unzip it under `/your/site/grav/user/plugins`. Then, rename the folder to `ampimage`. You can find these files on [GitHub](https://github.com/kristoffer-ekendahl/grav-plugin-ampimage) or via [GetGrav.org](http://getgrav.org/downloads/plugins#extras).

You should now have all the plugin files under

    /your/site/grav/user/plugins/ampimage
	
> NOTE: This plugin is a modular component for Grav which requires [Grav](http://github.com/getgrav/grav) and the [Error](https://github.com/getgrav/grav-plugin-error) and [Problems](https://github.com/getgrav/grav-plugin-problems) to operate.

## Configuration
Here is the default configuration and an explanation of available options:

```yaml
enabled: true

# add <figure> element around <amp-img>
addFigure: true

# move class attribute from image to figure element
moveClassToFigure: true

# sets amp:s layout attribute for all images
defaultLayout: responsive

```

For available layout modes see [AMP HTML Layout System](https://github.com/ampproject/amphtml/blob/master/spec/amp-html-layout.md) 

## Known issues

At the moment manual sizing in .md document doesn't work

    ![Test image](testimage.jpg?cropResize=300,300)

Ampimg can't determin the size from a non-file image.

## Credits

I looked a lot at [Ole Viks](https://github.com/OleVik) [Grav Image Captions Plugin](https://github.com/OleVik/grav-plugin-imgcaptions) before I started building.

## To Do

- [ ] Grav Package Manager
- [ ] Fix resizing issue
- [ ] Enable different layout mode for each image

