/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = "./Resources/public/js/c4g_visualization.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./Resources/public/js/c4g_visualization.js":
/*!**************************************************!*\
  !*** ./Resources/public/js/c4g_visualization.js ***!
  \**************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var Vis = function () {
    function Vis() {
        _classCallCheck(this, Vis);

        this.elements = document.getElementsByClassName('c4g_chart');
        this.charts = [];
    }

    _createClass(Vis, [{
        key: 'generateCharts',
        value: function generateCharts() {
            var _this = this;

            var scope = this;
            var elIndex = 0;

            var _loop = function _loop() {
                var element = _this.elements.item(elIndex);
                fetch('con4gis/fetchchart/' + element.dataset.chart).then(function (response) {
                    return response.json();
                }).then(function (responseJson) {

                    // console.log(responseJson.axis.x.tick.format);

                    var chart = {
                        bindto: '#' + element.id,
                        base: responseJson,
                        json: {},
                        range: function range(_range) {
                            this.json = scope.parseJson(this.bindto, this.base, this, _range);
                        },
                        update: function update() {
                            this.chart = c3.generate(this.json);
                        }
                    };

                    chart.json = scope.parseJson('#' + element.id, responseJson, chart);

                    if (typeof responseJson.axis.x.tick !== 'undefined' && typeof responseJson.axis.x.tick.format !== 'undefined') {
                        chart.format = responseJson.axis.x.tick.format;
                        chart.json.axis.x.tick.format = function (x) {
                            var chrt = scope.getChartByBindId(element.id);
                            return chrt.format[x];
                        };
                    }

                    scope.charts.push(chart);

                    chart.update();
                });
                elIndex += 1;
            };

            while (elIndex < this.elements.length) {
                _loop();
            }
        }
    }, {
        key: 'parseJson',
        value: function parseJson(bindto, json, chart) {
            var range = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : 'range_default';

            //console.log(range);
            var c3json = {
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
                tooltip: {
                    format: {}
                },
                zoom: {
                    enabled: false
                }
            };

            var index = 0;
            while (index < json.colors.length) {
                c3json.data.colors['y' + index] = json.colors[index];
                index += 1;
            }

            var rangeLowerBound = void 0;
            var rangeUpperBound = void 0;
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
                var x = ['x' + index];
                var y = ['y' + index];
                var i = 0;
                while (i < json.data[index].dataPoints.length) {
                    if (json.data[index].type === 'pie' || range === 'range_all' || json.data[index].dataPoints[i].x >= rangeLowerBound && json.data[index].dataPoints[i].x <= rangeUpperBound) {
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

            if (typeof json.zoom !== 'undefined' && typeof json.zoom.enabled !== 'undefined') {
                c3json.zoom.enabled = json.zoom.enabled;
            }

            if (typeof json.tooltip !== 'undefined' && typeof json.tooltip.format !== 'undefined' && typeof json.tooltip.format.title !== 'undefined') {
                chart.tooltipformattitle = json.tooltip.format.title;
                var scope = this;
                c3json.tooltip.format.title = function (x) {
                    var chrt = scope.getChartByBindId(bindto.substr(1, bindto.length));
                    return chrt.tooltipformattitle[x];
                };
            }

            console.log(c3json);
            return c3json;
        }
    }, {
        key: 'getChartByBindId',
        value: function getChartByBindId(id) {
            var index = 0;
            while (index < this.charts.length) {
                if (this.charts[index].bindto === '#' + id) {
                    return this.charts[index];
                }
                index += 1;
            }
            return null;
        }
    }, {
        key: 'addClickListeners',
        value: function addClickListeners() {
            var scope = this;
            var buttons = document.getElementsByClassName('c4g_chart_range_button');
            var index = 0;
            while (index < buttons.length) {
                buttons.item(index).addEventListener('click', function () {
                    var chart = scope.getChartByBindId(this.dataset.target);
                    chart.range(this.dataset.range);
                    chart.update();
                });
                index += 1;
            }
        }
    }]);

    return Vis;
}();

var vis = new Vis();
vis.generateCharts();
vis.addClickListeners();

/***/ })

/******/ });
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vd2VicGFjay9ib290c3RyYXAiLCJ3ZWJwYWNrOi8vLy4vUmVzb3VyY2VzL3B1YmxpYy9qcy9jNGdfdmlzdWFsaXphdGlvbi5qcyJdLCJuYW1lcyI6WyJWaXMiLCJlbGVtZW50cyIsImRvY3VtZW50IiwiZ2V0RWxlbWVudHNCeUNsYXNzTmFtZSIsImNoYXJ0cyIsInNjb3BlIiwiZWxJbmRleCIsImVsZW1lbnQiLCJpdGVtIiwiZmV0Y2giLCJkYXRhc2V0IiwiY2hhcnQiLCJ0aGVuIiwicmVzcG9uc2UiLCJqc29uIiwicmVzcG9uc2VKc29uIiwiYmluZHRvIiwiaWQiLCJiYXNlIiwicmFuZ2UiLCJwYXJzZUpzb24iLCJ1cGRhdGUiLCJjMyIsImdlbmVyYXRlIiwiYXhpcyIsIngiLCJ0aWNrIiwiZm9ybWF0IiwiY2hydCIsImdldENoYXJ0QnlCaW5kSWQiLCJwdXNoIiwibGVuZ3RoIiwiYzNqc29uIiwiZGF0YSIsInhzIiwiY29sdW1ucyIsInR5cGVzIiwiY29sb3JzIiwibmFtZXMiLCJncm91cHMiLCJ0b29sdGlwIiwiem9vbSIsImVuYWJsZWQiLCJpbmRleCIsInJhbmdlTG93ZXJCb3VuZCIsInJhbmdlVXBwZXJCb3VuZCIsInJhbmdlcyIsImxvd2VyQm91bmQiLCJ1cHBlckJvdW5kIiwieSIsImkiLCJkYXRhUG9pbnRzIiwidHlwZSIsIm5hbWUiLCJncm91cCIsInRpdGxlIiwidG9vbHRpcGZvcm1hdHRpdGxlIiwic3Vic3RyIiwiY29uc29sZSIsImxvZyIsImJ1dHRvbnMiLCJhZGRFdmVudExpc3RlbmVyIiwidGFyZ2V0IiwidmlzIiwiZ2VuZXJhdGVDaGFydHMiLCJhZGRDbGlja0xpc3RlbmVycyJdLCJtYXBwaW5ncyI6IjtRQUFBO1FBQ0E7O1FBRUE7UUFDQTs7UUFFQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTs7UUFFQTtRQUNBOztRQUVBO1FBQ0E7O1FBRUE7UUFDQTtRQUNBOzs7UUFHQTtR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LEtBQXpCO0FBQ0FSLDBCQUFNVSxNQUFOO0FBQ0gsaUJBSkQ7QUFLQXNCLHlCQUFTLENBQVQ7QUFDSDtBQUNKOzs7Ozs7QUFHTCxJQUFJb0IsTUFBTSxJQUFJL0QsR0FBSixFQUFWO0FBQ0ErRCxJQUFJQyxjQUFKO0FBQ0FELElBQUlFLGlCQUFKLEciLCJmaWxlIjoiYzRnX3Zpc3VhbGl6YXRpb24uanMiLCJzb3VyY2VzQ29udGVudCI6WyIgXHQvLyBUaGUgbW9kdWxlIGNhY2hlXG4gXHR2YXIgaW5zdGFsbGVkTW9kdWxlcyA9IHt9O1xuXG4gXHQvLyBUaGUgcmVxdWlyZSBmdW5jdGlvblxuIFx0ZnVuY3Rpb24gX193ZWJwYWNrX3JlcXVpcmVfXyhtb2R1bGVJZCkge1xuXG4gXHRcdC8vIENoZWNrIGlmIG1vZHVsZSBpcyBpbiBjYWNoZVxuIFx0XHRpZihpbnN0YWxsZWRNb2R1bGVzW21vZHVsZUlkXSkge1xuIFx0XHRcdHJldHVybiBpbnN0YWxsZWRNb2R1bGVzW21vZHVsZUlkXS5leHBvcnRzO1xuIFx0XHR9XG4gXHRcdC8vIENyZWF0ZSBhIG5ldyBtb2R1bGUgKGFuZCBwdXQgaXQgaW50byB0aGUgY2FjaGUpXG4gXHRcdHZhciBtb2R1bGUgPSBpbnN0YWxsZWRNb2R1bGVzW21vZHVsZUlkXSA9IHtcbiBcdFx0XHRpOiBtb2R1bGVJZCxcbiBcdFx0XHRsOiBmYWxzZSxcbiBcdFx0XHRleHBvcnRzOiB7fVxuIFx0XHR9O1xuXG4gXHRcdC8vIEV4ZWN1dGUgdGhlIG1vZHVsZSBmdW5jdGlvblxuIFx0XHRtb2R1bGVzW21vZHVsZUlkXS5jYWxsKG1vZHVsZS5leHBvcnRzLCBtb2R1bGUsIG1vZHVsZS5leHBvcnRzLCBfX3dlYnBhY2tfcmVxdWlyZV9fKTtcblxuIFx0XHQvLyBGbGFnIHRoZSBtb2R1bGUgYXMgbG9hZGVkXG4gXHRcdG1vZHVsZS5sID0gdHJ1ZTtcblxuIFx0XHQvLyBSZXR1cm4gdGhlIGV4cG9ydHMgb2YgdGhlIG1vZHVsZVxuIFx0XHRyZXR1cm4gbW9kdWxlLmV4cG9ydHM7XG4gXHR9XG5cblxuIFx0Ly8gZXhwb3NlIHRoZSBtb2R1bGVzIG9iamVjdCAoX193ZWJwYWNrX21vZHVsZXNfXylcbiBcdF9fd2VicGFja19yZXF1aXJlX18ubSA9IG1vZHVsZXM7XG5cbiBcdC8vIGV4cG9zZSB0aGUgbW9kdWxlIGNhY2hlXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLmMgPSBpbnN0YWxsZWRNb2R1bGVzO1xuXG4gXHQvLyBkZWZpbmUgZ2V0dGVyIGZ1bmN0aW9uIGZvciBoYXJtb255IGV4cG9ydHNcbiBcdF9fd2VicGFja19yZXF1aXJlX18uZCA9IGZ1bmN0aW9uKGV4cG9ydHMsIG5hbWUsIGdldHRlcikge1xuIFx0XHRpZighX193ZWJwYWNrX3JlcXVpcmVfXy5vKGV4cG9ydHMsIG5hbWUpKSB7XG4gXHRcdFx0T2JqZWN0LmRlZmluZVByb3BlcnR5KGV4cG9ydHMsIG5hbWUsIHsgZW51bWVyYWJsZTogdHJ1ZSwgZ2V0OiBnZXR0ZXIgfSk7XG4gXHRcdH1cbiBcdH07XG5cbiBcdC8vIGRlZmluZSBfX2VzTW9kdWxlIG9uIGV4cG9ydHNcbiBcdF9fd2VicGFja19yZXF1aXJlX18uciA9IGZ1bmN0aW9uKGV4cG9ydHMpIHtcbiBcdFx0aWYodHlwZW9mIFN5bWJvbCAhPT0gJ3VuZGVmaW5lZCcgJiYgU3ltYm9sLnRvU3RyaW5nVGFnKSB7XG4gXHRcdFx0T2JqZWN0LmRlZmluZVByb3BlcnR5KGV4cG9ydHMsIFN5bWJvbC50b1N0cmluZ1RhZywgeyB2YWx1ZTogJ01vZHVsZScgfSk7XG4gXHRcdH1cbiBcdFx0T2JqZWN0LmRlZmluZVByb3BlcnR5KGV4cG9ydHMsICdfX2VzTW9kdWxlJywgeyB2YWx1ZTogdHJ1ZSB9KTtcbiBcdH07XG5cbiBcdC8vIGNyZWF0ZSBhIGZha2UgbmFtZXNwYWNlIG9iamVjdFxuIFx0Ly8gbW9kZSAmIDE6IHZhbHVlIGlzIGEgbW9kdWxlIGlkLCByZXF1aXJlIGl0XG4gXHQvLyBtb2RlICYgMjogbWVyZ2UgYWxsIHByb3BlcnRpZXMgb2YgdmFsdWUgaW50byB0aGUgbnNcbiBcdC8vIG1vZGUgJiA0OiByZXR1cm4gdmFsdWUgd2hlbiBhbHJlYWR5IG5zIG9iamVjdFxuIFx0Ly8gbW9kZSAmIDh8MTogYmVoYXZlIGxpa2UgcmVxdWlyZVxuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy50ID0gZnVuY3Rpb24odmFsdWUsIG1vZGUpIHtcbiBcdFx0aWYobW9kZSAmIDEpIHZhbHVlID0gX193ZWJwYWNrX3JlcXVpcmVfXyh2YWx1ZSk7XG4gXHRcdGlmKG1vZGUgJiA4KSByZXR1cm4gdmFsdWU7XG4gXHRcdGlmKChtb2RlICYgNCkgJiYgdHlwZW9mIHZhbHVlID09PSAnb2JqZWN0JyAmJiB2YWx1ZSAmJiB2YWx1ZS5fX2VzTW9kdWxlKSByZXR1cm4gdmFsdWU7XG4gXHRcdHZhciBucyA9IE9iamVjdC5jcmVhdGUobnVsbCk7XG4gXHRcdF9fd2VicGFja19yZXF1aXJlX18ucihucyk7XG4gXHRcdE9iamVjdC5kZWZpbmVQcm9wZXJ0eShucywgJ2RlZmF1bHQnLCB7IGVudW1lcmFibGU6IHRydWUsIHZhbHVlOiB2YWx1ZSB9KTtcbiBcdFx0aWYobW9kZSAmIDIgJiYgdHlwZW9mIHZhbHVlICE9ICdzdHJpbmcnKSBmb3IodmFyIGtleSBpbiB2YWx1ZSkgX193ZWJwYWNrX3JlcXVpcmVfXy5kKG5zLCBrZXksIGZ1bmN0aW9uKGtleSkgeyByZXR1cm4gdmFsdWVba2V5XTsgfS5iaW5kKG51bGwsIGtleSkpO1xuIFx0XHRyZXR1cm4gbnM7XG4gXHR9O1xuXG4gXHQvLyBnZXREZWZhdWx0RXhwb3J0IGZ1bmN0aW9uIGZvciBjb21wYXRpYmlsaXR5IHdpdGggbm9uLWhhcm1vbnkgbW9kdWxlc1xuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5uID0gZnVuY3Rpb24obW9kdWxlKSB7XG4gXHRcdHZhciBnZXR0ZXIgPSBtb2R1bGUgJiYgbW9kdWxlLl9fZXNNb2R1bGUgP1xuIFx0XHRcdGZ1bmN0aW9uIGdldERlZmF1bHQoKSB7IHJldHVybiBtb2R1bGVbJ2RlZmF1bHQnXTsgfSA6XG4gXHRcdFx0ZnVuY3Rpb24gZ2V0TW9kdWxlRXhwb3J0cygpIHsgcmV0dXJuIG1vZHVsZTsgfTtcbiBcdFx0X193ZWJwYWNrX3JlcXVpcmVfXy5kKGdldHRlciwgJ2EnLCBnZXR0ZXIpO1xuIFx0XHRyZXR1cm4gZ2V0dGVyO1xuIFx0fTtcblxuIFx0Ly8gT2JqZWN0LnByb3RvdHlwZS5oYXNPd25Qcm9wZXJ0eS5jYWxsXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLm8gPSBmdW5jdGlvbihvYmplY3QsIHByb3BlcnR5KSB7IHJldHVybiBPYmplY3QucHJvdG90eXBlLmhhc093blByb3BlcnR5LmNhbGwob2JqZWN0LCBwcm9wZXJ0eSk7IH07XG5cbiBcdC8vIF9fd2VicGFja19wdWJsaWNfcGF0aF9fXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLnAgPSBcIlwiO1xuXG5cbiBcdC8vIExvYWQgZW50cnkgbW9kdWxlIGFuZCByZXR1cm4gZXhwb3J0c1xuIFx0cmV0dXJuIF9fd2VicGFja19yZXF1aXJlX18oX193ZWJwYWNrX3JlcXVpcmVfXy5zID0gXCIuL1Jlc291cmNlcy9wdWJsaWMvanMvYzRnX3Zpc3VhbGl6YXRpb24uanNcIik7XG4iLCIndXNlIHN0cmljdCc7XG5cbmNsYXNzIFZpcyB7XG5cbiAgICBjb25zdHJ1Y3RvcigpIHtcbiAgICAgICAgdGhpcy5lbGVtZW50cyA9IGRvY3VtZW50LmdldEVsZW1lbnRzQnlDbGFzc05hbWUoJ2M0Z19jaGFydCcpO1xuICAgICAgICB0aGlzLmNoYXJ0cyA9IFtdO1xuICAgIH1cblxuICAgIGdlbmVyYXRlQ2hhcnRzKCkge1xuICAgICAgICBjb25zdCBzY29wZSA9IHRoaXM7XG4gICAgICAgIGxldCBlbEluZGV4ID0gMDtcbiAgICAgICAgd2hpbGUgKGVsSW5kZXggPCB0aGlzLmVsZW1lbnRzLmxlbmd0aCkge1xuICAgICAgICAgICAgbGV0IGVsZW1lbnQgPSB0aGlzLmVsZW1lbnRzLml0ZW0oZWxJbmRleCk7XG4gICAgICAgICAgICBmZXRjaCgnY29uNGdpcy9mZXRjaGNoYXJ0LycgKyBlbGVtZW50LmRhdGFzZXQuY2hhcnQpXG4gICAgICAgICAgICAgICAgLnRoZW4oZnVuY3Rpb24gKHJlc3BvbnNlKSB7XG4gICAgICAgICAgICAgICAgICAgIHJldHVybiByZXNwb25zZS5qc29uKCk7XG4gICAgICAgICAgICAgICAgfSlcbiAgICAgICAgICAgICAgICAudGhlbihmdW5jdGlvbiAocmVzcG9uc2VKc29uKSB7XG5cbiAgICAgICAgICAgICAgICAgICAgLy8gY29uc29sZS5sb2cocmVzcG9uc2VKc29uLmF4aXMueC50aWNrLmZvcm1hdCk7XG5cbiAgICAgICAgICAgICAgICAgICAgbGV0IGNoYXJ0ID0ge1xuICAgICAgICAgICAgICAgICAgICAgICAgYmluZHRvOiAnIycgKyBlbGVtZW50LmlkLFxuICAgICAgICAgICAgICAgICAgICAgICAgYmFzZTogcmVzcG9uc2VKc29uLFxuICAgICAgICAgICAgICAgICAgICAgICAganNvbjoge30sXG4gICAgICAgICAgICAgICAgICAgICAgICByYW5nZTogZnVuY3Rpb24ocmFuZ2UpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB0aGlzLmpzb24gPSBzY29wZS5wYXJzZUpzb24odGhpcy5iaW5kdG8sIHRoaXMuYmFzZSwgdGhpcywgcmFuZ2UpO1xuICAgICAgICAgICAgICAgICAgICAgICAgfSxcbiAgICAgICAgICAgICAgICAgICAgICAgIHVwZGF0ZTogZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgdGhpcy5jaGFydCA9IGMzLmdlbmVyYXRlKHRoaXMuanNvbik7XG4gICAgICAgICAgICAgICAgICAgICAgICB9LFxuICAgICAgICAgICAgICAgICAgICB9O1xuXG4gICAgICAgICAgICAgICAgICAgIGNoYXJ0Lmpzb24gPSBzY29wZS5wYXJzZUpzb24oJyMnICsgZWxlbWVudC5pZCwgcmVzcG9uc2VKc29uLCBjaGFydCk7XG5cbiAgICAgICAgICAgICAgICAgICAgaWYgKHR5cGVvZiByZXNwb25zZUpzb24uYXhpcy54LnRpY2sgIT09ICd1bmRlZmluZWQnICYmIHR5cGVvZiByZXNwb25zZUpzb24uYXhpcy54LnRpY2suZm9ybWF0ICE9PSAndW5kZWZpbmVkJykge1xuICAgICAgICAgICAgICAgICAgICAgICAgY2hhcnQuZm9ybWF0ID0gcmVzcG9uc2VKc29uLmF4aXMueC50aWNrLmZvcm1hdDtcbiAgICAgICAgICAgICAgICAgICAgICAgIGNoYXJ0Lmpzb24uYXhpcy54LnRpY2suZm9ybWF0ID0gZnVuY3Rpb24gKHgpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBsZXQgY2hydCA9IHNjb3BlLmdldENoYXJ0QnlCaW5kSWQoZWxlbWVudC5pZCk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgcmV0dXJuIGNocnQuZm9ybWF0W3hdO1xuICAgICAgICAgICAgICAgICAgICAgICAgfTtcbiAgICAgICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAgICAgICAgIHNjb3BlLmNoYXJ0cy5wdXNoKGNoYXJ0KTtcblxuICAgICAgICAgICAgICAgICAgICBjaGFydC51cGRhdGUoKTtcblxuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgZWxJbmRleCArPSAxO1xuICAgICAgICB9XG4gICAgfVxuXG4gICAgcGFyc2VKc29uKGJpbmR0bywganNvbiwgY2hhcnQsIHJhbmdlID0gJ3JhbmdlX2RlZmF1bHQnKSB7XG4gICAgICAgIC8vY29uc29sZS5sb2cocmFuZ2UpO1xuICAgICAgICBsZXQgYzNqc29uID0ge1xuICAgICAgICAgICAgYmluZHRvOiBiaW5kdG8sXG4gICAgICAgICAgICBkYXRhOiB7XG4gICAgICAgICAgICAgICAgeHM6IHt9LFxuICAgICAgICAgICAgICAgIGNvbHVtbnM6IFtdLFxuICAgICAgICAgICAgICAgIHR5cGVzOiB7fSxcbiAgICAgICAgICAgICAgICBjb2xvcnM6IHt9LFxuICAgICAgICAgICAgICAgIG5hbWVzOiB7fSxcbiAgICAgICAgICAgICAgICBncm91cHM6IFtdXG4gICAgICAgICAgICB9LFxuICAgICAgICAgICAgYXhpczoge30sXG4gICAgICAgICAgICB0b29sdGlwOiB7XG4gICAgICAgICAgICAgICAgZm9ybWF0OiB7XG5cbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9LFxuICAgICAgICAgICAgem9vbToge1xuICAgICAgICAgICAgICAgIGVuYWJsZWQ6IGZhbHNlXG4gICAgICAgICAgICB9XG4gICAgICAgIH07XG5cbiAgICAgICAgbGV0IGluZGV4ID0gMDtcbiAgICAgICAgd2hpbGUgKGluZGV4IDwganNvbi5jb2xvcnMubGVuZ3RoKSB7XG4gICAgICAgICAgICBjM2pzb24uZGF0YS5jb2xvcnNbJ3knICsgaW5kZXhdID0ganNvbi5jb2xvcnNbaW5kZXhdO1xuICAgICAgICAgICAgaW5kZXggKz0gMTtcbiAgICAgICAgfVxuXG4gICAgICAgIGxldCByYW5nZUxvd2VyQm91bmQ7XG4gICAgICAgIGxldCByYW5nZVVwcGVyQm91bmQ7XG4gICAgICAgIGlmIChyYW5nZSAhPT0gJ3JhbmdlX2FsbCcpIHtcbiAgICAgICAgICAgIGlmICh0eXBlb2YganNvbi5yYW5nZXNbcmFuZ2VdID09PSAndW5kZWZpbmVkJykge1xuICAgICAgICAgICAgICAgIHJhbmdlID0gJ3JhbmdlX2FsbCc7XG4gICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgIHJhbmdlTG93ZXJCb3VuZCA9IGpzb24ucmFuZ2VzW3JhbmdlXS5sb3dlckJvdW5kO1xuICAgICAgICAgICAgICAgIHJhbmdlVXBwZXJCb3VuZCA9IGpzb24ucmFuZ2VzW3JhbmdlXS51cHBlckJvdW5kO1xuICAgICAgICAgICAgICAgIC8vY29uc29sZS5sb2cocmFuZ2VMb3dlckJvdW5kICsgXCIvXCIgKyByYW5nZVVwcGVyQm91bmQpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9XG5cbiAgICAgICAgaW5kZXggPSAwO1xuICAgICAgICB3aGlsZSAoaW5kZXggPCBqc29uLmRhdGEubGVuZ3RoKSB7XG4gICAgICAgICAgICBjM2pzb24uZGF0YS54c1sneScgKyBpbmRleF0gPSAneCcgKyBpbmRleDtcbiAgICAgICAgICAgIGxldCB4ID0gWyd4JyArIGluZGV4XTtcbiAgICAgICAgICAgIGxldCB5ID0gWyd5JyArIGluZGV4XTtcbiAgICAgICAgICAgIGxldCBpID0gMDtcbiAgICAgICAgICAgIHdoaWxlIChpIDwganNvbi5kYXRhW2luZGV4XS5kYXRhUG9pbnRzLmxlbmd0aCkge1xuICAgICAgICAgICAgICAgIGlmICgoanNvbi5kYXRhW2luZGV4XS50eXBlID09PSAncGllJykgfHwgKHJhbmdlID09PSAncmFuZ2VfYWxsJykgfHwgKGpzb24uZGF0YVtpbmRleF0uZGF0YVBvaW50c1tpXS54ID49IHJhbmdlTG93ZXJCb3VuZCAgJiYganNvbi5kYXRhW2luZGV4XS5kYXRhUG9pbnRzW2ldLnggPD0gcmFuZ2VVcHBlckJvdW5kKSkge1xuICAgICAgICAgICAgICAgICAgICB4LnB1c2goanNvbi5kYXRhW2luZGV4XS5kYXRhUG9pbnRzW2ldLngpO1xuICAgICAgICAgICAgICAgICAgICB5LnB1c2goanNvbi5kYXRhW2luZGV4XS5kYXRhUG9pbnRzW2ldLnkpO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBpICs9IDE7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBjM2pzb24uZGF0YS5jb2x1bW5zLnB1c2goeCwgeSk7XG4gICAgICAgICAgICBjM2pzb24uZGF0YS50eXBlc1sneScgKyBpbmRleF0gPSBqc29uLmRhdGFbaW5kZXhdLnR5cGU7XG4gICAgICAgICAgICBpZiAodHlwZW9mIGpzb24uZGF0YVtpbmRleF0ubmFtZSAhPT0gJ3VuZGVmaW5lZCcpIHtcbiAgICAgICAgICAgICAgICBjM2pzb24uZGF0YS5uYW1lc1sneScgKyBpbmRleF0gPSBqc29uLmRhdGFbaW5kZXhdLm5hbWU7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBpZiAodHlwZW9mIGpzb24uZGF0YVtpbmRleF0uZ3JvdXAgIT09ICd1bmRlZmluZWQnKSB7XG4gICAgICAgICAgICAgICAgd2hpbGUgKHR5cGVvZiBjM2pzb24uZGF0YS5ncm91cHNbanNvbi5kYXRhW2luZGV4XS5ncm91cF0gPT09ICd1bmRlZmluZWQnKSB7XG4gICAgICAgICAgICAgICAgICAgIGMzanNvbi5kYXRhLmdyb3Vwcy5wdXNoKFtdKTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgYzNqc29uLmRhdGEuZ3JvdXBzW2pzb24uZGF0YVtpbmRleF0uZ3JvdXBdLnB1c2goJ3knICsgaW5kZXgpO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgaW5kZXggKz0gMTtcbiAgICAgICAgfVxuXG4gICAgICAgIGlmICh0eXBlb2YganNvbi5heGlzICE9PSAndW5kZWZpbmVkJykge1xuICAgICAgICAgICAgYzNqc29uLmF4aXMgPSBqc29uLmF4aXM7XG4gICAgICAgICAgICAvLyBjM2pzb24uYXhpcy5mb3JtYXQgPSBmdW5jdGlvbih4KSB7XG4gICAgICAgICAgICAvLyAgICAgcmV0dXJuIGpzb24uYXhpcy5mb3JtYXRbeF07XG4gICAgICAgICAgICAvLyB9O1xuICAgICAgICB9XG5cbiAgICAgICAgaWYgKCh0eXBlb2YganNvbi56b29tICE9PSAndW5kZWZpbmVkJykgJiYgKHR5cGVvZiBqc29uLnpvb20uZW5hYmxlZCAhPT0gJ3VuZGVmaW5lZCcpKSB7XG4gICAgICAgICAgICBjM2pzb24uem9vbS5lbmFibGVkID0ganNvbi56b29tLmVuYWJsZWQ7XG4gICAgICAgIH1cblxuICAgICAgICBpZiAodHlwZW9mIGpzb24udG9vbHRpcCAhPT0gJ3VuZGVmaW5lZCcgJiYgdHlwZW9mIGpzb24udG9vbHRpcC5mb3JtYXQgIT09ICd1bmRlZmluZWQnICYmIHR5cGVvZiBqc29uLnRvb2x0aXAuZm9ybWF0LnRpdGxlICE9PSAndW5kZWZpbmVkJykge1xuICAgICAgICAgICAgY2hhcnQudG9vbHRpcGZvcm1hdHRpdGxlID0ganNvbi50b29sdGlwLmZvcm1hdC50aXRsZTtcbiAgICAgICAgICAgIGxldCBzY29wZSA9IHRoaXM7XG4gICAgICAgICAgICBjM2pzb24udG9vbHRpcC5mb3JtYXQudGl0bGUgPSBmdW5jdGlvbiAoeCkge1xuICAgICAgICAgICAgICAgIGxldCBjaHJ0ID0gc2NvcGUuZ2V0Q2hhcnRCeUJpbmRJZChiaW5kdG8uc3Vic3RyKDEsIGJpbmR0by5sZW5ndGgpKTtcbiAgICAgICAgICAgICAgICByZXR1cm4gY2hydC50b29sdGlwZm9ybWF0dGl0bGVbeF07XG4gICAgICAgICAgICB9O1xuICAgICAgICB9XG5cbiAgICAgICAgY29uc29sZS5sb2coYzNqc29uKTtcbiAgICAgICAgcmV0dXJuIGMzanNvbjtcbiAgICB9XG5cbiAgICBnZXRDaGFydEJ5QmluZElkKGlkKSB7XG4gICAgICAgIGxldCBpbmRleCA9IDA7XG4gICAgICAgIHdoaWxlIChpbmRleCA8IHRoaXMuY2hhcnRzLmxlbmd0aCkge1xuICAgICAgICAgICAgaWYgKHRoaXMuY2hhcnRzW2luZGV4XS5iaW5kdG8gPT09ICcjJyArIGlkKSB7XG4gICAgICAgICAgICAgICAgcmV0dXJuIHRoaXMuY2hhcnRzW2luZGV4XTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGluZGV4ICs9IDE7XG4gICAgICAgIH1cbiAgICAgICAgcmV0dXJuIG51bGw7XG4gICAgfVxuXG4gICAgYWRkQ2xpY2tMaXN0ZW5lcnMoKSB7XG4gICAgICAgIGNvbnN0IHNjb3BlID0gdGhpcztcbiAgICAgICAgbGV0IGJ1dHRvbnMgPSBkb2N1bWVudC5nZXRFbGVtZW50c0J5Q2xhc3NOYW1lKCdjNGdfY2hhcnRfcmFuZ2VfYnV0dG9uJyk7XG4gICAgICAgIGxldCBpbmRleCA9IDA7XG4gICAgICAgIHdoaWxlIChpbmRleCA8IGJ1dHRvbnMubGVuZ3RoKSB7XG4gICAgICAgICAgICBidXR0b25zLml0ZW0oaW5kZXgpLmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICAgICAgbGV0IGNoYXJ0ID0gc2NvcGUuZ2V0Q2hhcnRCeUJpbmRJZCh0aGlzLmRhdGFzZXQudGFyZ2V0KTtcbiAgICAgICAgICAgICAgICBjaGFydC5yYW5nZSh0aGlzLmRhdGFzZXQucmFuZ2UpO1xuICAgICAgICAgICAgICAgIGNoYXJ0LnVwZGF0ZSgpO1xuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICBpbmRleCArPSAxO1xuICAgICAgICB9XG4gICAgfVxufVxuXG5sZXQgdmlzID0gbmV3IFZpcygpO1xudmlzLmdlbmVyYXRlQ2hhcnRzKCk7XG52aXMuYWRkQ2xpY2tMaXN0ZW5lcnMoKTtcbiJdLCJzb3VyY2VSb290IjoiIn0=