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
                fetch('con4gis/fetchchart/' + element.dataset.chart)
                    .then(function (response) {
                        return response.json();
                    })
                    .then(function (responseJson) {

                        // console.log(responseJson.axis.x.tick.format);

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
        while (index < json.data.length) {
            c3json.data.xs['y' + index] = 'x' + index;
            let x = ['x' + index];
            let y = ['y' + index];
            let i = 0;
            while (i < json.data[index].dataPoints.length) {
                if ((json.data[index].type === 'pie') || (json.data[index].type === 'donut') || (json.data[index].type === 'gauge') || (range === 'range_all') || (json.data[index].dataPoints[i].x >= rangeLowerBound  && json.data[index].dataPoints[i].x <= rangeUpperBound)) {
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

            index += 1;
        }

        if (typeof json.axis !== 'undefined') {
            c3json.axis = json.axis;
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
            c3json.tooltip.show = false;
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
