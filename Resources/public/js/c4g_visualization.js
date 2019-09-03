'use strict';

class Vis {

    constructor() {
        this.elements = document.getElementsByClassName('c4g_chart');
        this.charts = [];
    }

    generateCharts() {
        const scope = this;
        let elIndex = 0;
        while (elIndex < this.elements.length) {
            let element = this.elements.item(elIndex);
            fetch('con4gis/fetchchart/' + element.dataset.chart)
                .then(function (response) {
                    return response.json();
                })
                .then(function (responseJson) {

                    // console.log(responseJson.axis.x.tick.format);

                    let chart = {
                        bindto: '#' + element.id,
                        base: responseJson,
                        json: scope.parseJson('#' + element.id, responseJson),
                        range: function(range) {
                            this.json = scope.parseJson(this.bindto, this.base, range);
                        },
                        update: function() {
                            this.chart = c3.generate(this.json);
                        },
                    };

                    if (typeof responseJson.axis.x.tick !== 'undefined' && typeof responseJson.axis.x.tick.format !== 'undefined') {
                        chart.format = responseJson.axis.x.tick.format;
                        chart.json.axis.x.tick.format = function (x) {
                            let chrt = scope.getChartByBindId(element.id);
                            return chrt.format[x];
                        };
                    }

                    scope.charts.push(chart);

                    chart.update();

                });
            elIndex += 1;
        }
    }

    parseJson(bindto, json, range = 'range_default') {
        //console.log(range);
        let c3json = {
            bindto: bindto,
            data: {
                xs: {},
                columns: [],
                types: {},
                colors: {},
                names: {},
                groups: []
            },
            axis: {},
            zoom: {
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
                if ((json.data[index].type === 'pie') || (range === 'range_all') || (json.data[index].dataPoints[i].x >= rangeLowerBound  && json.data[index].dataPoints[i].x <= rangeUpperBound)) {
                    x.push(json.data[index].dataPoints[i].x);
                    y.push(json.data[index].dataPoints[i].y);
                }
                i += 1;
            }
            c3json.data.columns.push(x, y);
            c3json.data.types['y' + index] = json.data[index].type;
            if (typeof json.data[index].name !== 'undefined') {
                c3json.data.names['y' + index] = json.data[index].name;
            }
            if (typeof json.data[index].group !== 'undefined') {
                while (typeof c3json.data.groups[json.data[index].group] === 'undefined') {
                    c3json.data.groups.push([]);
                }
                c3json.data.groups[json.data[index].group].push('y' + index);
            }
            index += 1;
        }

        if (typeof json.axis !== 'undefined') {
            c3json.axis = json.axis;
            // c3json.axis.format = function(x) {
            //     return json.axis.format[x];
            // };
        }

        if ((typeof json.zoom !== 'undefined') && (typeof json.zoom.enabled !== 'undefined')) {
            c3json.zoom.enabled = json.zoom.enabled;
        }

        console.log(c3json);
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
