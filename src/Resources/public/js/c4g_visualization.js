/*
 * This file is part of con4gis, the gis-kit for Contao CMS.
 * @package con4gis
 * @version 8
 * @author con4gis contributors (see "authors.txt")
 * @license LGPL-3.0-or-later
 * @copyright (c) 2010-2021, by Küstenschmiede GmbH Software & Design
 * @link https://www.con4gis.org
 */

'use strict';

import c3 from 'c3';
import * as d3 from 'd3';

class Vis {

  constructor() {
    this.elements = document.getElementsByClassName('c4g_chart');
    this.charts = [];
  }


  generateCharts() {
    const scope = this;
    let elIndex = 0;

    // var deLocaleDef = {
    //     "dateTime": "%A, der %e. %B %Y, %X",
    //     "date": "%d.%m.%Y",
    //     "time": "%H:%M:%S",
    //     "periods": ["vormittags", "nachmittags"],
    //     "days": ["Sonntag", "Montag", "Dienstag", "Mittwoch", "Donnerstag", "Freitag", "Samstag"],
    //     "shortDays": ["So", "Mo", "Di", "Mi", "Do", "Fr", "Sa"],
    //     "months": ["Januar", "Februar", "März", "April", "Mai", "Juni", "Juli", "August", "September", "Oktober", "November", "Dezember"],
    //     "shortMonths": ["Jan", "Feb", "Mrz", "Apr", "Mai", "Jun", "Jul", "Aug", "Sep", "Okt", "Nov", "Dez"],
    //     "decimal": ",",
    //     "thousands": "'",
    //     "grouping": [3],
    //     "currency": ["", "\u00a0EUR"]
    // };
    //
    // var deLocale = d3.locale(deLocaleDef);

    while (elIndex < this.elements.length) {
      let element = this.elements.item(elIndex);
      if (element && element.dataset && element.dataset.chart) {
        let url = '/visualization-api/fetchChart/' + element.dataset.chart;
        fetch(url)
          .then(response => response.json())
          .then((responseJson) => {

            console.log(responseJson);

            responseJson = this.setTickConfigForYAxis(responseJson);

            let chart = {
              bindto: '#' + element.id,
              base: responseJson,
              json: {},
              range: function(range) {
                this.json = scope.parseJson(this.bindto, this.base, this, range);
              },
              update: function() {
                this.chart = c3.generate(this.json);
              },
            };

            chart.json = scope.parseJson('#' + element.id, responseJson, chart);

            // set format for x axis
            if (typeof responseJson.axis.x.tick !== 'undefined' && typeof responseJson.axis.x.tick.format !== 'undefined') {
              chart.format = responseJson.axis.x.tick.format;
              chart.json.axis.x.tick.format = function (x) {
                let chrt = scope.getChartByBindId(element.id);
                return chrt.format[x];
              };
            }

            if (typeof responseJson.axis.x.tick !== 'undefined' && typeof responseJson.axis.x.tick.rotate === '1') {
              chart.rotate = responseJson.axis.x.tick.rotate;
              chart.json.axis.x.tick.rotate = function (x) {
                let chrt = scope.getChartByBindId(element.id);
                return chrt.rotate[x];
              };
            }

            scope.charts.push(chart);

            chart.update();

          });
      }

      elIndex += 1;
    }
  }

  setTickConfigForYAxis(json) {
    if (json.axis.y.tickFormat) {
      if (!json.axis.y.tick) {
        json.axis.y.tick = {};
      }
      json.axis.y.tick.format = (d) => {
        let roundedVal = parseFloat(d).toFixed(3); // ToDo set parameter from config
        return roundedVal + json.axis.y.tickFormat;
      };
    }
    if (json.axis.y.labelCount) {
      let labelCount = parseInt(json.axis.y.labelCount, 10);
      if (labelCount > 0) {
        json.axis.y.tick.count = labelCount;
      }
    }

    if (json.axis.y2.tickFormat) {
      if (!json.axis.y2.tick) {
        json.axis.y2.tick = {};
      }
      json.axis.y2.tick.format = (d) => {
        let roundedVal = parseFloat(d).toFixed(3); // ToDo set parameter from config
        return roundedVal + json.axis.y2.tickFormat;
      };
    }
    if (json.axis.y2.labelCount) {
      let labelCount = parseInt(json.axis.y2.labelCount, 10);
      if (labelCount > 0) {
        json.axis.y2.tick.count = labelCount;
      }
    }

    return json;
  }

  parseJson(bindto, json, chart, range = 'range_default') {
    //console.log(range);
    let c3json = {
      bindto: bindto,
      data: {
        xs: {},
        columns: [],
        types: {},
        colors: {},
        names: {},
        groups: [],
        redirects: {},
      },
      axis: {},
      tooltip: {
        format: {

        }
      },
      zoom: {
        enabled: false
      },
      points: {
        enabled: true
      },
      legend: {
        enabled: true
      },
      tooltips: {
        enabled: true
      },
      labels: {
        enabled: false
      },
      oneLabelPerElement: {
        enabled: false
      }
    };

    let index = 0;
    while (index < json.colors.length) {
      c3json.data.colors['y' + index] = json.colors[index];
      index += 1;
    }

    let rangeLowerBound;
    let rangeUpperBound;
    if (range !== 'range_all') {
      if (typeof json.ranges[range] === 'undefined') {
        range = 'range_all';
      } else {
        rangeLowerBound = json.ranges[range].lowerBound;
        rangeUpperBound = json.ranges[range].upperBound;
        //console.log(rangeLowerBound + "/" + rangeUpperBound);
      }
    }

    index = 0;
    let hasCustomTooltip = false;
    while (index < json.data.length) {
      let x = [];
      let y = [];
      let i = 0;

      if (typeof json.data[index].name !== 'undefined') {
        x.push('x' + index);
        y.push(json.data[index].name);
        c3json.data.xs[json.data[index].name] = 'x' + index;
      } else {
        x.push('x' + index);
        y.push('y' + index);
        c3json.data.xs['y' + index] = 'x' + index;
      }

      if (!c3json.data.axes) {
        c3json.data.axes = {};
      }
      if (!c3json.data.axes[json.data[index].name]) {
        c3json.data.axes[json.data[index].name] = json.data[index].target;
      }

      while (i < json.data[index].dataPoints.length) {
        let chartTypeCondition = (json.data[index].type === 'pie') || (json.data[index].type === 'donut') || (json.data[index].type === 'gauge');
        let rangeCondition = (range === 'range_all') || (json.data[index].dataPoints[i].x >= rangeLowerBound  && json.data[index].dataPoints[i].x <= rangeUpperBound);

        if (chartTypeCondition || rangeCondition) {
          x.push(json.data[index].dataPoints[i].x);
          y.push(json.data[index].dataPoints[i].y);
        }

        i += 1;
      }
      c3json.data.columns.push(x, y);

      if (json.data[index].type == 'areaspline') {
        json.data[index].type = 'area-spline';
      }

      var type = json.data[index].type !== 'gantt' ? json.data[index].type : 'line';

      c3json.data.types['y' + index] = type;
      if (typeof json.data[index].name !== 'undefined') {
        c3json.data.names['y' + index] = json.data[index].name;
      }
      if (typeof json.data[index].group !== 'undefined') {
        while (typeof c3json.data.groups[json.data[index].group] === 'undefined') {
          c3json.data.groups.push([]);
        }
        c3json.data.groups[json.data[index].group].push('y' + index);
      }

      if (typeof json.data[index].group !== 'undefined') {
        while (typeof c3json.data.groups[json.data[index].group] === 'undefined') {
          c3json.data.groups.push([]);
        }
        c3json.data.groups[json.data[index].group].push('y' + index);
      }

      //ToDo
      if (typeof json.data[index].dataPoints[0].redirect !== 'undefined') {
        c3json.data.redirects['y' + index] = json.data[index].dataPoints[0].redirect;
      }

      if (json.data[index].tooltipExtension) {
        hasCustomTooltip = true;
      }

      index += 1;
    }

    if (typeof json.axis !== 'undefined') {
      c3json.axis = json.axis;
    }

    if (typeof json.subchart !== 'undefined') {
      c3json.subchart = json.subchart;
    }

    if ((typeof json.zoom !== 'undefined') && (typeof json.zoom.enabled !== 'undefined')) {
      c3json.zoom.enabled = json.zoom.enabled;
      c3json.zoom.type = 'drag';
    }

    if ((typeof json.points !== 'undefined') && (typeof json.points.enabled !== 'undefined')) {
      c3json.point = {
        show: json.points.enabled
      }
    }

    if ((typeof json.legend !== 'undefined') && (typeof json.legend.enabled !== 'undefined')) {
      c3json.legend = {
        hide: !json.legend.enabled
      }
    }

    if ((typeof json.labels !== 'undefined') && (typeof json.labels.enabled !== 'undefined')) {
      c3json.data.labels = json.labels.enabled;

      if (((typeof json.oneLabelPerElement !== 'undefined') && (typeof json.oneLabelPerElement.enabled !== 'undefined') && json.oneLabelPerElement.enabled)) {
        let scope = this;
        c3json.data.labels = {
          format: function (v, id, i, j) {
            let chrt = scope.getChartByBindId(bindto.substr(1, bindto.length));
            if ( (id) && (i == 0) ) {
              return chrt.json.data.names[id];
            } else {
              return '';
            }
          }
        }
      }
    }

    if ((typeof json.tooltips !== 'undefined') && (typeof json.tooltips.enabled !== 'undefined') && json.tooltips.enabled &&
      (typeof json.tooltip !== 'undefined' && typeof json.tooltip.format !== 'undefined' && typeof json.tooltip.format.title !== 'undefined')) {
      chart.tooltipformattitle = json.tooltip.format.title;
      let scope = this;
      c3json.tooltip.format.title = function (x) {
        let chrt = scope.getChartByBindId(bindto.substr(1, bindto.length));
        return chrt.tooltipformattitle[x];
      };
    } else {
      c3json.tooltip.show = (json.tooltips.enabled && json.tooltips.enabled !== 'undefined');
    }

    c3json.tooltip.format.value = (value, ratio, id, index) => {
      if (id === "y0") {
        return value + " " + c3json.axis.y.tickFormat;
      } else if (id === "y1" && c3json.axis.y2.show) {
        return value + " " + c3json.axis.y2.tickFormat;
      } else if (c3json.data.axes[id]) {
        return value + " " + c3json.axis[c3json.data.axes[id]].tickFormat;
      }

      return value;
    };

    // check for custom tooltip
    if (hasCustomTooltip) {
      c3json.tooltip.contents = function (data, defaultTitleFormat, defaultValueFormat, color) {
        let valueDiv = "<div class='c3-tooltip'>";

        if (json.data[0].xType === "datetime") {
          // js works with microseconds
          let value = new Date(data[0].x * 1000);
          value = value.toLocaleDateString("de");
          valueDiv += "<div class='c4g-tooltip-name'>" + value + "</div>";
        } else {
          valueDiv += "<div class='c4g-tooltip-name'>" + data[0].x + "</div>";
        }

        valueDiv += "<div class='c3-tooltip-container'>";

        for (let i = 0; i < data.length; i++ ) {

          let axisName = c3json.data.axes[data[i].name];

          valueDiv += "<div class='c4g-tooltip-element'>";
          valueDiv += "<div class='c4g-tooltip-element-color' style='background-color: " + color(data[i].name) + ";'></div>";
          valueDiv += "<div class='c4g-tooltip-element-value'>" + data[i].name + ": " + defaultValueFormat(data[i].value, 1.0, axisName) + "</div>";
          valueDiv += "<div class='c4g-tooltip-element-extension'>" + json.data[i].tooltipExtension + "</div>";
          valueDiv += "</div>";
        }

        valueDiv += "</div>"; // close c3-tooltip-container
        valueDiv += "</div>"; // close c3-tooltip

        return valueDiv;
      }
    }

    let scope = this;
    c3json.data.onclick = function (d, element) {
      let chrt = scope.getChartByBindId(bindto.substr(1, bindto.length));
      let redirect = chrt.json.data.redirects[d.id];
      if (redirect && redirect != 0) {
        window.location = chrt.json.data.redirects[d.id]
      }
    }

    //console.log(c3json);
    return c3json;
  }

  getChartByBindId(id) {
    let index = 0;
    while (index < this.charts.length) {
      if (this.charts[index].bindto === '#' + id) {
        return this.charts[index];
      }
      index += 1;
    }
    return null;
  }

  addClickListeners() {
    const scope = this;
    let buttons = document.getElementsByClassName('c4g_chart_range_button');
    let index = 0;
    while (index < buttons.length) {
      buttons.item(index).addEventListener('click', function() {
        let chart = scope.getChartByBindId(this.dataset.target);
        chart.range(this.dataset.range);
        chart.update();
      });
      index += 1;
    }
  }
}

let vis = new Vis();
vis.generateCharts();
vis.addClickListeners();
