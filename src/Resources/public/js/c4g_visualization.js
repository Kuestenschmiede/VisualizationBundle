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

    while (elIndex < this.elements.length) {
      let element = this.elements.item(elIndex);
      if (element && element.dataset && element.dataset.chart) {
        let url = '/con4gis/fetchChart/' + element.dataset.chart;
        fetch(url)
          .then(response => response.json())
          .then((responseJson) => {
            // responseJson = this.setTickConfigForYAxis(responseJson);
            let chartJson = scope.parseJson('#' + element.id, responseJson, null);
            //console.log(chartJson);
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

              // TODO in function auslagern, da auch in parseJson gebraucht
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
            };

            scope.charts.push(objChart);
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
    let bbjson = {
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
      bbjson.data.colors['y' + index] = json.colors[index];
      bbjson.data.colors[json.data[index].name] = json.colors[index];
      index += 1;
    }
    if (typeof json.axis !== 'undefined') {
      bbjson.axis = json.axis;
    }

    let rangeLowerBound;
    let rangeUpperBound;
    let setMinMaxValues; // used for range_all
    let minValue = 0, maxValue = 0;

    if (range !== 'range_all') {
      if (bbjson.axis.x.tick) {
        bbjson.axis.x.tick.values = bbjson.axis.x.tick.singleValues;
        bbjson.axis.x.tick.format = (value) => {
          return bbjson.axis.x.tick.singleFormat[value];
        }
      }
      if (typeof json.ranges[range] === 'undefined') {
        range = 'range_all';
      } else {
        rangeLowerBound = json.ranges[range].lowerBound;
        rangeUpperBound = json.ranges[range].upperBound;
        if (json.ranges[range].yMin) {
          bbjson.axis.y.min = json.ranges[range].yMin;
        }
        if (json.ranges[range].y2Min) {
          bbjson.axis.y2.min = json.ranges[range].y2Min;
        }
        if (json.ranges[range].yMax) {
          bbjson.axis.y.max = json.ranges[range].yMax;
        }
        if (json.ranges[range].y2Max) {
          bbjson.axis.y2.max = json.ranges[range].y2Max;
        }
      }
    } else {

      setMinMaxValues = true;
      // bbjson.axis.x.tick.values = bbjson.axis.x.tick.valuesAll;
      // bbjson.axis.x.tick.format = (value) => {
      //   return bbjson.axis.x.tick.formatAll[value];
      // }
      // bbjson.axis.x.tick.culling = true;
      // bbjson.axis.x.tick.values = [];
      bbjson.axis.x.tick.format = (value) => {
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
          bbjson.data.xs[json.data[index].name] = 'x' + index;
        } else {
          x.push('x' + index);
          y.push('y' + index);
          bbjson.data.xs['y' + index] = 'x' + index;
        }
      } else {
        bbjson.data.xs['y' + index] = 'x' + index;
        x.push('x' + index);
        y.push('y' + index);
      }

      if (!bbjson.data.axes) {
        bbjson.data.axes = {};
      }
      if (!bbjson.data.axes[json.data[index].name]) {
        bbjson.data.axes[json.data[index].name] = json.data[index].target;
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

          bbjson.axis.y.min = minValue;
          bbjson.axis.y.max = maxValue;
        }

        if (chartTypeCondition || rangeCondition) {
          x.push(json.data[index].dataPoints[i].x);
          y.push(json.data[index].dataPoints[i].y);
        }

        i += 1;
      }
      bbjson.data.columns.push(x, y);

      if (json.data[index].type === 'areaspline') {
        json.data[index].type = 'area-spline';
      }

      var type = json.data[index].type !== 'gantt' ? json.data[index].type : 'line';

      bbjson.data.types['y' + index] = type;
      if (typeof json.data[index].name !== 'undefined') {
        bbjson.data.names['y' + index] = json.data[index].name;
      }
      if (typeof json.data[index].group !== 'undefined') {
        while (typeof bbjson.data.groups[json.data[index].group] === 'undefined') {
          bbjson.data.groups.push([]);
        }
        bbjson.data.groups[json.data[index].group].push('y' + index);
      }

      //ToDo
      if (typeof json.data[index].dataPoints[0].redirect !== 'undefined') {
        bbjson.data.redirects['y' + index] = json.data[index].dataPoints[0].redirect;
      }

      if (json.data[index].tooltipExtension) {
        hasCustomTooltip = true;
      }

      index += 1;
    }

    if (typeof json.grid !== 'undefined') {
      if (json.grid.x.show) {
        bbjson.grid.x.show = true;
      }
      if (json.grid.y.show) {
        bbjson.grid.y.show = true;
      }
    }

    if (typeof json.subchart !== 'undefined') {
      bbjson.subchart = json.subchart;
    }

    if ((typeof json.zoom !== 'undefined') && (typeof json.zoom.enabled !== 'undefined')) {
      bbjson.zoom.enabled = json.zoom.enabled;
      bbjson.zoom.type = 'scroll';
    }

    if ((typeof json.points !== 'undefined') && (typeof json.points.enabled !== 'undefined')) {
      bbjson.point = {
        show: json.points.enabled
      }
    }

    if ((typeof json.legend !== 'undefined') && (typeof json.legend.enabled !== 'undefined')) {
      bbjson.legend = {
        hide: !json.legend.enabled
      }
    }

    if ((typeof json.labels !== 'undefined') && (typeof json.labels.enabled !== 'undefined')) {
      bbjson.data.labels = json.labels.enabled;
      if (json.labels.enabled && json.labels.colors) {
        bbjson.data.labels = { colors: json.labels.colors };
      }

      if (((typeof json.oneLabelPerElement !== 'undefined') && (typeof json.oneLabelPerElement.enabled !== 'undefined') && json.oneLabelPerElement.enabled)) {
        let scope = this;
        bbjson.data.labels = {
          format: function (v, id, i, j) {
            let chrt = scope.getChartByBindId(bindto.substr(1, bindto.length));
            if (chrt && id && (i == 0) ) {
              return chrt.json.data.names[id];
            } else {
              return '';
            }
          }
        }
      }
    }

    bbjson.tooltip.format.value = (value, ratio, id, index) => {

      // check for custom tooltip
      if (json.data[index] && json.data[index].tooltipExtension) {
        let el = document.createElement("p");
        el.innerHTML = json.data[index].tooltipExtension;
        return el.innerText;
      } else {
        if (id === "y0") {
          return value + " " + bbjson.axis.y.tickFormat;
        } else if (id === "y1" && bbjson.axis.y2.show) {
          return value + " " + bbjson.axis.y2.tickFormat;
        } else if (bbjson.data.axes[id]) {
          return value + " " + bbjson.axis[bbjson.data.axes[id]].tickFormat;
        } else if (id.indexOf("y") === 0) {
          return value + " " + bbjson.axis.y.tickFormat;
        }

        return value;
      }
    };

    let hasDate = false;
    for (let i = 0; i < json.data.length; i++) {
      if (json.data[i].xType === "datetime") {
        hasDate = true;
        break;
      }
    }

    bbjson.tooltip.format.title = function(xValue) {

      if (hasDate) {
        let value = new Date(xValue * 1000);
        xValue = value.toLocaleDateString("de");
      }

      return xValue;
    }

    let scope = this;
    bbjson.data.onclick = function (d, element) {
      let chrt = scope.getChartByBindId(bindto.substr(1, bindto.length));
      if (chrt) {
        let redirect = chrt.json.data.redirects[d.id];
        if (redirect && redirect !== 0) {
          window.location = chrt.json.data.redirects[d.id]
        }
      }
    }

    if (json.line) {
      bbjson.line = json.line;
    }

    return bbjson;
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

  cleanTicks() {
    let ticks = document.querySelectorAll(".bb-axis-x > .tick > text > tspan");
    let yearValues = [];
    for (let i = 0; i < ticks.length; i++) {
      if (!yearValues.includes(ticks[i].innerHTML)) {
        yearValues.push(ticks[i].innerHTML);
      } else {
        ticks[i].parentNode.parentNode.remove();
      }
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




