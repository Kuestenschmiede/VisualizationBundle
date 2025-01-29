/*
 * This file is part of con4gis, the gis-kit for Contao CMS.
 * @package con4gis
 * @version 10
 * @author con4gis contributors (see "authors.txt")
 * @license LGPL-3.0-or-later
 * @copyright (c) 2010-2025, by Küstenschmiede GmbH Software & Design
 * @link https://www.con4gis.org
 */

'use strict';

// import c3 from 'c3';
// import * as d3 from 'd3';
import bb, {
  bar,
  line,
  area,
  spline,
  areaSpline,
  pie,
  donut,
  gauge
} from "billboard.js";

class Vis {

  constructor() {
    this.elements = document.getElementsByClassName('c4g_chart');
    this.charts = [];
  }


  generateCharts(opt_callback) {
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
        let url = '/con4gis/fetchChart/' + element.dataset.chart;
        fetch(url)
          .then(response => response.json())
          .then((responseJson) => {
            // responseJson = this.setTickConfigForYAxis(responseJson);
            let chartJson = scope.parseJson('#' + element.id, responseJson, null);
            let chart = bb.generate(chartJson);
            chart.json = chartJson;
            let objChart = {
              chart: chart,
              ranges: responseJson.ranges,
              id: '#' + element.id,
              data: responseJson.data
            };

            chart.range = function(range) {
              document.querySelectorAll('.c4g_chart_range_button').forEach((element) => {
                if (element.getAttribute('data-range') === range) {
                  element.classList.add("range-active");
                } else {
                  element.classList.remove("range-active");
                }
              });


              let bounds = objChart.ranges[range];
              let unloadNames = [];
              if (bounds) {
                let lowerBound = bounds.lowerBound;
                let upperBound = bounds.upperBound;

                let columns = [];
                for (let i = 0; i < objChart.data.length; i++) {
                  let name = objChart.data[i].name;
                  let xName = `x${i}`;
                  let yName = `y${i}`;
                  let xValues = [];
                  let yValues = [];

                  for (let j = 0; j < objChart.data[i].dataPoints.length; j++) {
                    let point = objChart.data[i].dataPoints[j];

                    if (point.x >= lowerBound && point.x <= upperBound) {
                      xValues.push(objChart.data[i].dataPoints[j].x);
                      yValues.push(objChart.data[i].dataPoints[j].y);
                    }
                  }

                  unloadNames.push(xName, yName);

                  columns.push([xName, ...xValues]);
                  columns.push([yName, ...yValues]);

                }

                chart.load({
                  columns: columns,
                  resizeAfter: true,
                  unload: unloadNames
                });
              }


              // this.json = scope.parseJson(this.bindto, this.base, this, range);
            };

            chart.update = function() {
              this.chart = this.json ? bb.generate(this.json) : '';
              let activeRangeButton = document.querySelector(".c4g_chart_range_button.range-active");
              let range = "";
              if (activeRangeButton) {
                range = activeRangeButton.getAttribute('data-range');
              }
              // if (this.base.data[0].xType === "datetime" && range === "range_all") {
              //   // needed to clean up the labels on the X axis for large timeseries data that spans multiple years
              //   cleanTicks();
              //   window.setTimeout(cleanTicks, 1000);
              //   window.addEventListener('resize', () => {
              //     window.setTimeout(cleanTicks, 100);
              //   });
              //   window.addEventListener('focus', () => {
              //     window.setTimeout(cleanTicks, 100);
              //   });
              // }
            };


            // };
            //
            // chart.json = scope.parseJson('#' + element.id, responseJson, chart);
            //
            // // set format for x axis
            // if (responseJson.axis && typeof responseJson.axis.x.tick !== 'undefined' && typeof responseJson.axis.x.tick.format !== 'undefined') {
            //   chart.format = responseJson.axis.x.tick.format;
            //   chart.json.axis.x.tick.format = function (x) {
            //     let chrt = scope.getChartByBindId(element.id);
            //     return chrt.format[x];
            //   };
            // }
            //
            // if (responseJson.axis && typeof responseJson.axis.x.tick !== 'undefined' && typeof responseJson.axis.x.tick.rotate === '1') {
            //   chart.rotate = responseJson.axis.x.tick.rotate;
            //   chart.json.axis.x.tick.rotate = function (x) {
            //     let chrt = scope.getChartByBindId(element.id);
            //     return chrt.rotate[x];
            //   };
            // }
            //
            scope.charts.push(objChart);
            //
            // chart.update();
            //
            // if (opt_callback && typeof opt_callback === 'function') {
            //   opt_callback();
            // }

          });
      }

      elIndex += 1;
    }
  }

  setTickConfigForYAxis(json) {
    if (json.axis) {
      if (json.axis.y && json.axis.y.tickFormat) {
        if (!json.axis.y.tick) {
          json.axis.y.tick = {};
        }
        json.axis.y.tick.format = (d) => {
          let roundedVal = parseFloat(d).toFixed(3); // ToDo set parameter from config
          return roundedVal + json.axis.y.tickFormat;
        };
      }
      if (json.axis.y.labelCount) {
        if (!json.axis.y.tick) {
          json.axis.y.tick = {};
        }
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
        if (!json.axis.y2.tick) {
          json.axis.y2.tick = {};
        }
        let labelCount = parseInt(json.axis.y2.labelCount, 10);
        if (labelCount > 0) {
          json.axis.y2.tick.count = labelCount;
        }
      }
    }
    return json;
  }

  parseJson(bindto, json, chart, range = 'range_default') {
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
      },
      grid: {
        x: {
          show: false
        },
        y: {
          show: false
        }
      }
    };

    if (!json) {
      return '';
    }

    let index = 0;
    while (index < json.colors.length) {
      c3json.data.colors['y' + index] = json.colors[index];
      c3json.data.colors[json.data[index].name] = json.colors[index];
      index += 1;
    }
    if (typeof json.axis !== 'undefined') {
      c3json.axis = json.axis;
    }

    let rangeLowerBound;
    let rangeUpperBound;
    let setMinMaxValues; // used for range_all
    let minValue = 0, maxValue = 0;

    if (range !== 'range_all') {
      if (c3json.axis.x.tick) {
        c3json.axis.x.tick.values = c3json.axis.x.tick.singleValues;
        c3json.axis.x.tick.format = (value) => {
          return c3json.axis.x.tick.singleFormat[value];
        }
      }
      if (typeof json.ranges[range] === 'undefined') {
        range = 'range_all';
      } else {
        rangeLowerBound = json.ranges[range].lowerBound;
        rangeUpperBound = json.ranges[range].upperBound;
        if (json.ranges[range].yMin) {
          c3json.axis.y.min = json.ranges[range].yMin;
        }
        if (json.ranges[range].y2Min) {
          c3json.axis.y2.min = json.ranges[range].y2Min;
        }
        if (json.ranges[range].yMax) {
          c3json.axis.y.max = json.ranges[range].yMax;
        }
        if (json.ranges[range].y2Max) {
          c3json.axis.y2.max = json.ranges[range].y2Max;
        }
      }
    } else {

      setMinMaxValues = true;
      // c3json.axis.x.tick.values = c3json.axis.x.tick.valuesAll;
      // c3json.axis.x.tick.format = (value) => {
      //   return c3json.axis.x.tick.formatAll[value];
      // }
      // c3json.axis.x.tick.culling = true;
      // c3json.axis.x.tick.values = [];
      c3json.axis.x.tick.format = (value) => {
        // console.log(value);
        // return c3json.axis.x.tick.singleFormat[value];
        // console.log(value);
        let date = new Date();
        date.setTime(value * 1000);

        return date.getFullYear();
      }
    }

    index = 0;
    let hasCustomTooltip = false;
    while (index < json.data.length) {
      let x = [];
      let y = [];
      let i = 0;

      if (json.data[index].type === "line") {
        if (typeof json.data[index].name !== 'undefined') {
          x.push('x' + index);
          y.push(json.data[index].name);
          c3json.data.xs[json.data[index].name] = 'x' + index;
        } else {
          x.push('x' + index);
          y.push('y' + index);
          c3json.data.xs['y' + index] = 'x' + index;
        }
      } else {
        c3json.data.xs['y' + index] = 'x' + index;
        x.push('x' + index);
        y.push('y' + index);
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

        if (setMinMaxValues) {
          // check values for bounds
          if (json.data[index].dataPoints[i].y < minValue) {
            minValue = json.data[index].dataPoints[i].y;
          }
          if (json.data[index].dataPoints[i].y > maxValue) {
            maxValue = json.data[index].dataPoints[i].y;
          }

          c3json.axis.y.min = minValue;
          c3json.axis.y.max = maxValue;
        }

        if (chartTypeCondition || rangeCondition) {
          x.push(json.data[index].dataPoints[i].x);
          y.push(json.data[index].dataPoints[i].y);
        }

        i += 1;
      }
      c3json.data.columns.push(x, y);

      if (json.data[index].type === 'areaspline') {
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

      //ToDo
      if (typeof json.data[index].dataPoints[0].redirect !== 'undefined') {
        c3json.data.redirects['y' + index] = json.data[index].dataPoints[0].redirect;
      }

      if (json.data[index].tooltipExtension) {
        hasCustomTooltip = true;
      }

      index += 1;
    }

    if (typeof json.grid !== 'undefined') {
      if (json.grid.x.show) {
        c3json.grid.x.show = true;
      }
      if (json.grid.y.show) {
        c3json.grid.y.show = true;
      }
    }

    if (typeof json.subchart !== 'undefined') {
      c3json.subchart = json.subchart;
    }

    if ((typeof json.zoom !== 'undefined') && (typeof json.zoom.enabled !== 'undefined')) {
      c3json.zoom.enabled = json.zoom.enabled;
      c3json.zoom.type = 'scroll';
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
      if (json.data[0].type === "pie") {
        if (!c3json.pie) {
          c3json.pie =  {};
        }
        c3json.pie.label = {show: json.labels.enabled};
      }

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
      if (json.data[0].type === 'line') {
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
            valueDiv += "<div class='c4g-tooltip-element-extension-line'>" + json.data[i].tooltipExtension + "</div>";
            valueDiv += "</div>";
          }

          valueDiv += "</div>"; // close c3-tooltip-container
          valueDiv += "</div>"; // close c3-tooltip

          return valueDiv;
        }
      } else {
        c3json.tooltip.contents = function (data, defaultTitleFormat, defaultValueFormat, color) {
          let valueDiv = "<div class='c3-tooltip'>";

          valueDiv += "<div class='c3-tooltip-container'>";

          for (let i = 0; i < data.length; i++ ) {
            let index = data[i].index;
            valueDiv += "<div class='c4g-tooltip-element-extension'>" + json.data[index].tooltipExtension + "</div>";
            valueDiv += "</div>";
          }

          valueDiv += "</div>"; // close c3-tooltip-container
          valueDiv += "</div>"; // close c3-tooltip

          return valueDiv;
        }
      }

    }

    let scope = this;
    c3json.data.onclick = function (d, element) {
      let chrt = scope.getChartByBindId(bindto.substr(1, bindto.length));
      if (chrt) {
        let redirect = chrt.json.data.redirects[d.id];
        if (redirect && redirect !== 0) {
          window.location = chrt.json.data.redirects[d.id]
        }
      }
    }

    return c3json;
  }

  getChartByBindId(id) {
    let index = 0;
    while (index < this.charts.length) {
      if (this.charts[index].id === ('#' + id)) {
        return this.charts[index].chart;
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
        if (chart) {
          chart.range(this.dataset.range);
          // chart.update();
        }
      });
      index += 1;
    }
  }
}

let vis = new Vis();
vis.addClickListeners();
vis.generateCharts(() => {
  let button = document.querySelector(".c4g_chart_range_button.range-active");
  if (button) {
    button.click();
  }
});



function cleanTicks() {
  let ticks = document.querySelectorAll(".c3-axis-x > .tick > text > tspan");
  let yearValues = [];
  for (let i = 0; i < ticks.length; i++) {
    if (!yearValues.includes(ticks[i].innerHTML)) {
      yearValues.push(ticks[i].innerHTML);
    } else {
      ticks[i].parentNode.parentNode.remove();
    }
  }
}
