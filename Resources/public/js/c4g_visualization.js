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
            .then(function (myJson) {

                let c3json = {
                    bindto: '#' + element.id,
                    data: {
                        xs: {},
                        columns: [],
                        types: {},
                        colors: {},
                        names: {},
                        groups: []
                    },
                    zoom: {
                        enabled: false
                    }
                };

                let index = 0;
                while (index < myJson.colors.length) {
                    c3json.data.colors['y' + index] = myJson.colors[index];
                    index += 1;
                }

                index = 0;
                while (index < myJson.data.length) {
                    c3json.data.xs['y' + index] = 'x' + index;
                    let x = ['x' + index];
                    let y = ['y' + index];
                    let i = 0;
                    while (i < myJson.data[index].dataPoints.length) {
                        x.push(myJson.data[index].dataPoints[i].x);
                        y.push(myJson.data[index].dataPoints[i].y);
                        i += 1;
                    }
                    c3json.data.columns.push(x, y);
                    c3json.data.types['y' + index] = myJson.data[index].type;
                    if (typeof myJson.data[index].name !== 'undefined') {
                        c3json.data.names['y' + index] = myJson.data[index].name;
                    }
                    if (typeof myJson.data[index].group !== 'undefined') {
                        while (typeof c3json.data.groups[myJson.data[index].group] === 'undefined') {
                            c3json.data.groups.push([]);
                        }
                        c3json.data.groups[myJson.data[index].group].push('y' + index);
                    }
                    index += 1;
                }

                if ((typeof myJson.zoom !== 'undefined') && (typeof myJson.zoom.enabled !== 'undefined')) {
                    c3json.zoom.enabled = myJson.zoom.enabled;
                }

                c4gVisualization.charts.push(
                    {
                        chart: c3.generate(c3json),
                        base: c3json
                    }
                );

            });
        elIndex += 1;
    }
};

c4gVisualization.generateCharts();