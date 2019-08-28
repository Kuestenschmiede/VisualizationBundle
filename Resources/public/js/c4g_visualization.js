const c4gVisualization = {};

c4gVisualization.elements = document.getElementsByClassName('c4g_chart');
c4gVisualization.charts = [];
c4gVisualization.generateCharts = function() {
    let elIndex = 0;
    while (elIndex < c4gVisualization.elements.length) {
        let element = c4gVisualization.elements.item(elIndex);
        fetch('con4gis/fetchchart/' + element.dataset.chart)
            .then(function (response) {
                return response.json();
            })
            .then(function (responseJson) {

                c4gVisualization.charts.push(
                    {
                        bindto: '#' + element.id,
                        base: responseJson,
                        range: 'default',
                        chart: c3.generate(c4gVisualization.parseJson('#' + element.id, responseJson)),
                        update: function() {
                            this.chart = c3.generate(c4gVisualization.parseJson(this.bindto, this.base, this.range));
                        },
                    }
                );
                //console.log(c4gVisualization.parseJson('#' + element.id, responseJson));
                //console.log(responseJson);

            });
        elIndex += 1;
    }
};

c4gVisualization.parseJson = function(bindto, json, range = 'range_default') {

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
    }

    if ((typeof json.zoom !== 'undefined') && (typeof json.zoom.enabled !== 'undefined')) {
        c3json.zoom.enabled = json.zoom.enabled;
    }

    console.log(c3json);
    return c3json;
};

c4gVisualization.getChartByBindId = function(id) {
    let index = 0;
    while (0 < c4gVisualization.charts.length) {
        if (c4gVisualization.charts[index].bindto === '#' + id) {
            return c4gVisualization.charts[index];
        }
        index += 1;
    }
    return null;
};

c4gVisualization.addClickListeners = function() {
    let buttons = document.getElementsByClassName('c4g_chart_range_button');
    let index = 0;
    while (index < buttons.length) {
        buttons.item(index).addEventListener('click', function() {
            let chart = c4gVisualization.getChartByBindId(this.dataset.target);
            chart.range = this.dataset.range;
            chart.update();
        });
        index += 1;
    }
};

c4gVisualization.generateCharts();
c4gVisualization.addClickListeners();
