# con4gis-VisualizationBundle

## Overview
Easily visualize your data in different types of charts.

__Features include:__
* Configure line, pie, bubble, step or bar charts to display data from your Contao database or mix the types 
* Load any data from any table or enter your own in the back end
* Switch between different ranges of data on the click of a button
* Add any image as watermark

## Installation
Via composer:
```
composer require con4gis/visualization
```
Alternatively, you can use the Contao Manager to install the con4gis-VisualizationBundle.

## Requirements
- [Contao](https://github.com/contao/core-bundle) (latest stable release)
- [CoreBundle](https://github.com/Kuestenschmiede/CoreBundle/releases) (*latest stable release*)

## Usage
This bundles provides two back end modules and one content element for Contao:

___Chart module___

Create and manage your charts. Add chart elements, such as lines or bars, add ranges, choose which axes should be shown and how they should be positioned. You can also add a watermark image to your chart.
More options include adding tooltips, zooming and adding a custom CSS class.

___Chart Element module___

Create and manage your chart elements.
The available chart types are:
- Line
- Area
- Bar
- Pie
- Gantt
- Ring / Donut
- Manometer
- Bubbles
- Steps

There are two options to add data to a chart element: manual input or loading 
data from a table. For manual input, an inputWizard assists you with the process.
For loading from another table, you just have to choose which table you want to load
and which columns you would like to use for data sources.
You can also customize the colors of the chart element. 

___Chart content element___

Add the content element to your article, pick the chart. Done!


## Documentation
Visit [docs.con4gis.org](https://docs.con4gis.org) for a user documentation. You can also contact us via the support forum there.
