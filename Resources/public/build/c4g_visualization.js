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
                        json: scope.parseJson('#' + element.id, responseJson),
                        range: function range(_range) {
                            this.json = scope.parseJson(this.bindto, this.base, _range);
                        },
                        update: function update() {
                            this.chart = c3.generate(this.json);
                        }
                    };

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
        value: function parseJson(bindto, json) {
            var range = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : 'range_default';

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
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vd2VicGFjay9ib290c3RyYXAiLCJ3ZWJwYWNrOi8vLy4vUmVzb3VyY2VzL3B1YmxpYy9qcy9jNGdfdmlzdWFsaXphdGlvbi5qcyJdLCJuYW1lcyI6WyJWaXMiLCJlbGVtZW50cyIsImRvY3VtZW50IiwiZ2V0RWxlbWVudHNCeUNsYXNzTmFtZSIsImNoYXJ0cyIsInNjb3BlIiwiZWxJbmRleCIsImVsZW1lbnQiLCJpdGVtIiwiZmV0Y2giLCJkYXRhc2V0IiwiY2hhcnQiLCJ0aGVuIiwicmVzcG9uc2UiLCJqc29uIiwicmVzcG9uc2VKc29uIiwiYmluZHRvIiwiaWQiLCJiYXNlIiwicGFyc2VKc29uIiwicmFuZ2UiLCJ1cGRhdGUiLCJjMyIsImdlbmVyYXRlIiwiYXhpcyIsIngiLCJ0aWNrIiwiZm9ybWF0IiwiY2hydCIsImdldENoYXJ0QnlCaW5kSWQiLCJwdXNoIiwibGVuZ3RoIiwiYzNqc29uIiwiZGF0YSIsInhzIiwiY29sdW1ucyIsInR5cGVzIiwiY29sb3JzIiwibmFtZXMiLCJncm91cHMiLCJ6b29tIiwiZW5hYmxlZCIsImluZGV4IiwicmFuZ2VMb3dlckJvdW5kIiwicmFuZ2VVcHBlckJvdW5kIiwicmFuZ2VzIiwibG93ZXJCb3VuZCIsInVwcGVyQm91bmQiLCJ5IiwiaSIsImRhdGFQb2ludHMiLCJ0eXBlIiwibmFtZSIsImdyb3VwIiwiY29uc29sZSIsImxvZyIsImJ1dHRvbnMiLCJhZGRFdmVudExpc3RlbmVyIiwidGFyZ2V0IiwidmlzIiwiZ2VuZXJhdGVDaGFydHMiLCJhZGRDbGlja0xpc3RlbmVycyJdLCJtYXBwaW5ncyI6IjtRQUFBO1FBQ0E7O1FBRUE7UUFDQTs7UUFFQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTs7UUFFQTtRQUNBOztRQUVBO1FBQ0E7O1FBRUE7UUFDQTtRQUNBOzs7UUFHQTtRQUNBOztRQUVBO1FBQ0E7O1FBRUE7UUFDQTtRQUNBO1FBQ0EsMENBQTBDLGdDQUFn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QUltRCxVQUFVdEQsU0FBU0Msc0JBQVQsQ0FBZ0Msd0JBQWhDLENBQWQ7QUFDQSxnQkFBSXVDLFFBQVEsQ0FBWjtBQUNBLG1CQUFPQSxRQUFRYyxRQUFRekIsTUFBdkIsRUFBK0I7QUFDM0J5Qix3QkFBUWhELElBQVIsQ0FBYWtDLEtBQWIsRUFBb0JlLGdCQUFwQixDQUFxQyxPQUFyQyxFQUE4QyxZQUFXO0FBQ3JELHdCQUFJOUMsUUFBUU4sTUFBTXdCLGdCQUFOLENBQXVCLEtBQUtuQixPQUFMLENBQWFnRCxNQUFwQyxDQUFaO0FBQ0EvQywwQkFBTVMsS0FBTixDQUFZLEtBQUtWLE9BQUwsQ0FBYVUsS0FBekI7QUFDQVQsMEJBQU1VLE1BQU47QUFDSCxpQkFKRDtBQUtBcUIseUJBQVMsQ0FBVDtBQUNIO0FBQ0o7Ozs7OztBQUdMLElBQUlpQixNQUFNLElBQUkzRCxHQUFKLEVBQVY7QUFDQTJELElBQUlDLGNBQUo7QUFDQUQsSUFBSUUsaUJBQUosRyIsImZpbGUiOiJjNGdfdmlzdWFsaXphdGlvbi5qcyIsInNvdXJjZXNDb250ZW50IjpbIiBcdC8vIFRoZSBtb2R1bGUgY2FjaGVcbiBcdHZhciBpbnN0YWxsZWRNb2R1bGVzID0ge307XG5cbiBcdC8vIFRoZSByZXF1aXJlIGZ1bmN0aW9uXG4gXHRmdW5jdGlvbiBfX3dlYnBhY2tfcmVxdWlyZV9fKG1vZHVsZUlkKSB7XG5cbiBcdFx0Ly8gQ2hlY2sgaWYgbW9kdWxlIGlzIGluIGNhY2hlXG4gXHRcdGlmKGluc3RhbGxlZE1vZHVsZXNbbW9kdWxlSWRdKSB7XG4gXHRcdFx0cmV0dXJuIGluc3RhbGxlZE1vZHVsZXNbbW9kdWxlSWRdLmV4cG9ydHM7XG4gXHRcdH1cbiBcdFx0Ly8gQ3JlYXRlIGEgbmV3IG1vZHVsZSAoYW5kIHB1dCBpdCBpbnRvIHRoZSBjYWNoZSlcbiBcdFx0dmFyIG1vZHVsZSA9IGluc3RhbGxlZE1vZHVsZXNbbW9kdWxlSWRdID0ge1xuIFx0XHRcdGk6IG1vZHVsZUlkLFxuIFx0XHRcdGw6IGZhbHNlLFxuIFx0XHRcdGV4cG9ydHM6IHt9XG4gXHRcdH07XG5cbiBcdFx0Ly8gRXhlY3V0ZSB0aGUgbW9kdWxlIGZ1bmN0aW9uXG4gXHRcdG1vZHVsZXNbbW9kdWxlSWRdLmNhbGwobW9kdWxlLmV4cG9ydHMsIG1vZHVsZSwgbW9kdWxlLmV4cG9ydHMsIF9fd2VicGFja19yZXF1aXJlX18pO1xuXG4gXHRcdC8vIEZsYWcgdGhlIG1vZHVsZSBhcyBsb2FkZWRcbiBcdFx0bW9kdWxlLmwgPSB0cnVlO1xuXG4gXHRcdC8vIFJldHVybiB0aGUgZXhwb3J0cyBvZiB0aGUgbW9kdWxlXG4gXHRcdHJldHVybiBtb2R1bGUuZXhwb3J0cztcbiBcdH1cblxuXG4gXHQvLyBleHBvc2UgdGhlIG1vZHVsZXMgb2JqZWN0IChfX3dlYnBhY2tfbW9kdWxlc19fKVxuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5tID0gbW9kdWxlcztcblxuIFx0Ly8gZXhwb3NlIHRoZSBtb2R1bGUgY2FjaGVcbiBcdF9fd2VicGFja19yZXF1aXJlX18uYyA9IGluc3RhbGxlZE1vZHVsZXM7XG5cbiBcdC8vIGRlZmluZSBnZXR0ZXIgZnVuY3Rpb24gZm9yIGhhcm1vbnkgZXhwb3J0c1xuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5kID0gZnVuY3Rpb24oZXhwb3J0cywgbmFtZSwgZ2V0dGVyKSB7XG4gXHRcdGlmKCFfX3dlYnBhY2tfcmVxdWlyZV9fLm8oZXhwb3J0cywgbmFtZSkpIHtcbiBcdFx0XHRPYmplY3QuZGVmaW5lUHJvcGVydHkoZXhwb3J0cywgbmFtZSwgeyBlbnVtZXJhYmxlOiB0cnVlLCBnZXQ6IGdldHRlciB9KTtcbiBcdFx0fVxuIFx0fTtcblxuIFx0Ly8gZGVmaW5lIF9fZXNNb2R1bGUgb24gZXhwb3J0c1xuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5yID0gZnVuY3Rpb24oZXhwb3J0cykge1xuIFx0XHRpZih0eXBlb2YgU3ltYm9sICE9PSAndW5kZWZpbmVkJyAmJiBTeW1ib2wudG9TdHJpbmdUYWcpIHtcbiBcdFx0XHRPYmplY3QuZGVmaW5lUHJvcGVydHkoZXhwb3J0cywgU3ltYm9sLnRvU3RyaW5nVGFnLCB7IHZhbHVlOiAnTW9kdWxlJyB9KTtcbiBcdFx0fVxuIFx0XHRPYmplY3QuZGVmaW5lUHJvcGVydHkoZXhwb3J0cywgJ19fZXNNb2R1bGUnLCB7IHZhbHVlOiB0cnVlIH0pO1xuIFx0fTtcblxuIFx0Ly8gY3JlYXRlIGEgZmFrZSBuYW1lc3BhY2Ugb2JqZWN0XG4gXHQvLyBtb2RlICYgMTogdmFsdWUgaXMgYSBtb2R1bGUgaWQsIHJlcXVpcmUgaXRcbiBcdC8vIG1vZGUgJiAyOiBtZXJnZSBhbGwgcHJvcGVydGllcyBvZiB2YWx1ZSBpbnRvIHRoZSBuc1xuIFx0Ly8gbW9kZSAmIDQ6IHJldHVybiB2YWx1ZSB3aGVuIGFscmVhZHkgbnMgb2JqZWN0XG4gXHQvLyBtb2RlICYgOHwxOiBiZWhhdmUgbGlrZSByZXF1aXJlXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLnQgPSBmdW5jdGlvbih2YWx1ZSwgbW9kZSkge1xuIFx0XHRpZihtb2RlICYgMSkgdmFsdWUgPSBfX3dlYnBhY2tfcmVxdWlyZV9fKHZhbHVlKTtcbiBcdFx0aWYobW9kZSAmIDgpIHJldHVybiB2YWx1ZTtcbiBcdFx0aWYoKG1vZGUgJiA0KSAmJiB0eXBlb2YgdmFsdWUgPT09ICdvYmplY3QnICYmIHZhbHVlICYmIHZhbHVlLl9fZXNNb2R1bGUpIHJldHVybiB2YWx1ZTtcbiBcdFx0dmFyIG5zID0gT2JqZWN0LmNyZWF0ZShudWxsKTtcbiBcdFx0X193ZWJwYWNrX3JlcXVpcmVfXy5yKG5zKTtcbiBcdFx0T2JqZWN0LmRlZmluZVByb3BlcnR5KG5zLCAnZGVmYXVsdCcsIHsgZW51bWVyYWJsZTogdHJ1ZSwgdmFsdWU6IHZhbHVlIH0pO1xuIFx0XHRpZihtb2RlICYgMiAmJiB0eXBlb2YgdmFsdWUgIT0gJ3N0cmluZycpIGZvcih2YXIga2V5IGluIHZhbHVlKSBfX3dlYnBhY2tfcmVxdWlyZV9fLmQobnMsIGtleSwgZnVuY3Rpb24oa2V5KSB7IHJldHVybiB2YWx1ZVtrZXldOyB9LmJpbmQobnVsbCwga2V5KSk7XG4gXHRcdHJldHVybiBucztcbiBcdH07XG5cbiBcdC8vIGdldERlZmF1bHRFeHBvcnQgZnVuY3Rpb24gZm9yIGNvbXBhdGliaWxpdHkgd2l0aCBub24taGFybW9ueSBtb2R1bGVzXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLm4gPSBmdW5jdGlvbihtb2R1bGUpIHtcbiBcdFx0dmFyIGdldHRlciA9IG1vZHVsZSAmJiBtb2R1bGUuX19lc01vZHVsZSA/XG4gXHRcdFx0ZnVuY3Rpb24gZ2V0RGVmYXVsdCgpIHsgcmV0dXJuIG1vZHVsZVsnZGVmYXVsdCddOyB9IDpcbiBcdFx0XHRmdW5jdGlvbiBnZXRNb2R1bGVFeHBvcnRzKCkgeyByZXR1cm4gbW9kdWxlOyB9O1xuIFx0XHRfX3dlYnBhY2tfcmVxdWlyZV9fLmQoZ2V0dGVyLCAnYScsIGdldHRlcik7XG4gXHRcdHJldHVybiBnZXR0ZXI7XG4gXHR9O1xuXG4gXHQvLyBPYmplY3QucHJvdG90eXBlLmhhc093blByb3BlcnR5LmNhbGxcbiBcdF9fd2VicGFja19yZXF1aXJlX18ubyA9IGZ1bmN0aW9uKG9iamVjdCwgcHJvcGVydHkpIHsgcmV0dXJuIE9iamVjdC5wcm90b3R5cGUuaGFzT3duUHJvcGVydHkuY2FsbChvYmplY3QsIHByb3BlcnR5KTsgfTtcblxuIFx0Ly8gX193ZWJwYWNrX3B1YmxpY19wYXRoX19cbiBcdF9fd2VicGFja19yZXF1aXJlX18ucCA9IFwiXCI7XG5cblxuIFx0Ly8gTG9hZCBlbnRyeSBtb2R1bGUgYW5kIHJldHVybiBleHBvcnRzXG4gXHRyZXR1cm4gX193ZWJwYWNrX3JlcXVpcmVfXyhfX3dlYnBhY2tfcmVxdWlyZV9fLnMgPSBcIi4vUmVzb3VyY2VzL3B1YmxpYy9qcy9jNGdfdmlzdWFsaXphdGlvbi5qc1wiKTtcbiIsIid1c2Ugc3RyaWN0JztcblxuY2xhc3MgVmlzIHtcblxuICAgIGNvbnN0cnVjdG9yKCkge1xuICAgICAgICB0aGlzLmVsZW1lbnRzID0gZG9jdW1lbnQuZ2V0RWxlbWVudHNCeUNsYXNzTmFtZSgnYzRnX2NoYXJ0Jyk7XG4gICAgICAgIHRoaXMuY2hhcnRzID0gW107XG4gICAgfVxuXG4gICAgZ2VuZXJhdGVDaGFydHMoKSB7XG4gICAgICAgIGNvbnN0IHNjb3BlID0gdGhpcztcbiAgICAgICAgbGV0IGVsSW5kZXggPSAwO1xuICAgICAgICB3aGlsZSAoZWxJbmRleCA8IHRoaXMuZWxlbWVudHMubGVuZ3RoKSB7XG4gICAgICAgICAgICBsZXQgZWxlbWVudCA9IHRoaXMuZWxlbWVudHMuaXRlbShlbEluZGV4KTtcbiAgICAgICAgICAgIGZldGNoKCdjb240Z2lzL2ZldGNoY2hhcnQvJyArIGVsZW1lbnQuZGF0YXNldC5jaGFydClcbiAgICAgICAgICAgICAgICAudGhlbihmdW5jdGlvbiAocmVzcG9uc2UpIHtcbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuIHJlc3BvbnNlLmpzb24oKTtcbiAgICAgICAgICAgICAgICB9KVxuICAgICAgICAgICAgICAgIC50aGVuKGZ1bmN0aW9uIChyZXNwb25zZUpzb24pIHtcblxuICAgICAgICAgICAgICAgICAgICAvLyBjb25zb2xlLmxvZyhyZXNwb25zZUpzb24uYXhpcy54LnRpY2suZm9ybWF0KTtcblxuICAgICAgICAgICAgICAgICAgICBsZXQgY2hhcnQgPSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBiaW5kdG86ICcjJyArIGVsZW1lbnQuaWQsXG4gICAgICAgICAgICAgICAgICAgICAgICBiYXNlOiByZXNwb25zZUpzb24sXG4gICAgICAgICAgICAgICAgICAgICAgICBqc29uOiBzY29wZS5wYXJzZUpzb24oJyMnICsgZWxlbWVudC5pZCwgcmVzcG9uc2VKc29uKSxcbiAgICAgICAgICAgICAgICAgICAgICAgIHJhbmdlOiBmdW5jdGlvbihyYW5nZSkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHRoaXMuanNvbiA9IHNjb3BlLnBhcnNlSnNvbih0aGlzLmJpbmR0bywgdGhpcy5iYXNlLCByYW5nZSk7XG4gICAgICAgICAgICAgICAgICAgICAgICB9LFxuICAgICAgICAgICAgICAgICAgICAgICAgdXBkYXRlOiBmdW5jdGlvbigpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB0aGlzLmNoYXJ0ID0gYzMuZ2VuZXJhdGUodGhpcy5qc29uKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIH0sXG4gICAgICAgICAgICAgICAgICAgIH07XG5cbiAgICAgICAgICAgICAgICAgICAgaWYgKHR5cGVvZiByZXNwb25zZUpzb24uYXhpcy54LnRpY2sgIT09ICd1bmRlZmluZWQnICYmIHR5cGVvZiByZXNwb25zZUpzb24uYXhpcy54LnRpY2suZm9ybWF0ICE9PSAndW5kZWZpbmVkJykge1xuICAgICAgICAgICAgICAgICAgICAgICAgY2hhcnQuZm9ybWF0ID0gcmVzcG9uc2VKc29uLmF4aXMueC50aWNrLmZvcm1hdDtcbiAgICAgICAgICAgICAgICAgICAgICAgIGNoYXJ0Lmpzb24uYXhpcy54LnRpY2suZm9ybWF0ID0gZnVuY3Rpb24gKHgpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBsZXQgY2hydCA9IHNjb3BlLmdldENoYXJ0QnlCaW5kSWQoZWxlbWVudC5pZCk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgcmV0dXJuIGNocnQuZm9ybWF0W3hdO1xuICAgICAgICAgICAgICAgICAgICAgICAgfTtcbiAgICAgICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAgICAgICAgIHNjb3BlLmNoYXJ0cy5wdXNoKGNoYXJ0KTtcblxuICAgICAgICAgICAgICAgICAgICBjaGFydC51cGRhdGUoKTtcblxuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgZWxJbmRleCArPSAxO1xuICAgICAgICB9XG4gICAgfVxuXG4gICAgcGFyc2VKc29uKGJpbmR0bywganNvbiwgcmFuZ2UgPSAncmFuZ2VfZGVmYXVsdCcpIHtcbiAgICAgICAgLy9jb25zb2xlLmxvZyhyYW5nZSk7XG4gICAgICAgIGxldCBjM2pzb24gPSB7XG4gICAgICAgICAgICBiaW5kdG86IGJpbmR0byxcbiAgICAgICAgICAgIGRhdGE6IHtcbiAgICAgICAgICAgICAgICB4czoge30sXG4gICAgICAgICAgICAgICAgY29sdW1uczogW10sXG4gICAgICAgICAgICAgICAgdHlwZXM6IHt9LFxuICAgICAgICAgICAgICAgIGNvbG9yczoge30sXG4gICAgICAgICAgICAgICAgbmFtZXM6IHt9LFxuICAgICAgICAgICAgICAgIGdyb3VwczogW11cbiAgICAgICAgICAgIH0sXG4gICAgICAgICAgICBheGlzOiB7fSxcbiAgICAgICAgICAgIHpvb206IHtcbiAgICAgICAgICAgICAgICBlbmFibGVkOiBmYWxzZVxuICAgICAgICAgICAgfVxuICAgICAgICB9O1xuXG4gICAgICAgIGxldCBpbmRleCA9IDA7XG4gICAgICAgIHdoaWxlIChpbmRleCA8IGpzb24uY29sb3JzLmxlbmd0aCkge1xuICAgICAgICAgICAgYzNqc29uLmRhdGEuY29sb3JzWyd5JyArIGluZGV4XSA9IGpzb24uY29sb3JzW2luZGV4XTtcbiAgICAgICAgICAgIGluZGV4ICs9IDE7XG4gICAgICAgIH1cblxuICAgICAgICBsZXQgcmFuZ2VMb3dlckJvdW5kO1xuICAgICAgICBsZXQgcmFuZ2VVcHBlckJvdW5kO1xuICAgICAgICBpZiAocmFuZ2UgIT09ICdyYW5nZV9hbGwnKSB7XG4gICAgICAgICAgICBpZiAodHlwZW9mIGpzb24ucmFuZ2VzW3JhbmdlXSA9PT0gJ3VuZGVmaW5lZCcpIHtcbiAgICAgICAgICAgICAgICByYW5nZSA9ICdyYW5nZV9hbGwnO1xuICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICByYW5nZUxvd2VyQm91bmQgPSBqc29uLnJhbmdlc1tyYW5nZV0ubG93ZXJCb3VuZDtcbiAgICAgICAgICAgICAgICByYW5nZVVwcGVyQm91bmQgPSBqc29uLnJhbmdlc1tyYW5nZV0udXBwZXJCb3VuZDtcbiAgICAgICAgICAgICAgICAvL2NvbnNvbGUubG9nKHJhbmdlTG93ZXJCb3VuZCArIFwiL1wiICsgcmFuZ2VVcHBlckJvdW5kKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuXG4gICAgICAgIGluZGV4ID0gMDtcbiAgICAgICAgd2hpbGUgKGluZGV4IDwganNvbi5kYXRhLmxlbmd0aCkge1xuICAgICAgICAgICAgYzNqc29uLmRhdGEueHNbJ3knICsgaW5kZXhdID0gJ3gnICsgaW5kZXg7XG4gICAgICAgICAgICBsZXQgeCA9IFsneCcgKyBpbmRleF07XG4gICAgICAgICAgICBsZXQgeSA9IFsneScgKyBpbmRleF07XG4gICAgICAgICAgICBsZXQgaSA9IDA7XG4gICAgICAgICAgICB3aGlsZSAoaSA8IGpzb24uZGF0YVtpbmRleF0uZGF0YVBvaW50cy5sZW5ndGgpIHtcbiAgICAgICAgICAgICAgICBpZiAoKGpzb24uZGF0YVtpbmRleF0udHlwZSA9PT0gJ3BpZScpIHx8IChyYW5nZSA9PT0gJ3JhbmdlX2FsbCcpIHx8IChqc29uLmRhdGFbaW5kZXhdLmRhdGFQb2ludHNbaV0ueCA+PSByYW5nZUxvd2VyQm91bmQgICYmIGpzb24uZGF0YVtpbmRleF0uZGF0YVBvaW50c1tpXS54IDw9IHJhbmdlVXBwZXJCb3VuZCkpIHtcbiAgICAgICAgICAgICAgICAgICAgeC5wdXNoKGpzb24uZGF0YVtpbmRleF0uZGF0YVBvaW50c1tpXS54KTtcbiAgICAgICAgICAgICAgICAgICAgeS5wdXNoKGpzb24uZGF0YVtpbmRleF0uZGF0YVBvaW50c1tpXS55KTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgaSArPSAxO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgYzNqc29uLmRhdGEuY29sdW1ucy5wdXNoKHgsIHkpO1xuICAgICAgICAgICAgYzNqc29uLmRhdGEudHlwZXNbJ3knICsgaW5kZXhdID0ganNvbi5kYXRhW2luZGV4XS50eXBlO1xuICAgICAgICAgICAgaWYgKHR5cGVvZiBqc29uLmRhdGFbaW5kZXhdLm5hbWUgIT09ICd1bmRlZmluZWQnKSB7XG4gICAgICAgICAgICAgICAgYzNqc29uLmRhdGEubmFtZXNbJ3knICsgaW5kZXhdID0ganNvbi5kYXRhW2luZGV4XS5uYW1lO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgaWYgKHR5cGVvZiBqc29uLmRhdGFbaW5kZXhdLmdyb3VwICE9PSAndW5kZWZpbmVkJykge1xuICAgICAgICAgICAgICAgIHdoaWxlICh0eXBlb2YgYzNqc29uLmRhdGEuZ3JvdXBzW2pzb24uZGF0YVtpbmRleF0uZ3JvdXBdID09PSAndW5kZWZpbmVkJykge1xuICAgICAgICAgICAgICAgICAgICBjM2pzb24uZGF0YS5ncm91cHMucHVzaChbXSk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIGMzanNvbi5kYXRhLmdyb3Vwc1tqc29uLmRhdGFbaW5kZXhdLmdyb3VwXS5wdXNoKCd5JyArIGluZGV4KTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGluZGV4ICs9IDE7XG4gICAgICAgIH1cblxuICAgICAgICBpZiAodHlwZW9mIGpzb24uYXhpcyAhPT0gJ3VuZGVmaW5lZCcpIHtcbiAgICAgICAgICAgIGMzanNvbi5heGlzID0ganNvbi5heGlzO1xuICAgICAgICAgICAgLy8gYzNqc29uLmF4aXMuZm9ybWF0ID0gZnVuY3Rpb24oeCkge1xuICAgICAgICAgICAgLy8gICAgIHJldHVybiBqc29uLmF4aXMuZm9ybWF0W3hdO1xuICAgICAgICAgICAgLy8gfTtcbiAgICAgICAgfVxuXG4gICAgICAgIGlmICgodHlwZW9mIGpzb24uem9vbSAhPT0gJ3VuZGVmaW5lZCcpICYmICh0eXBlb2YganNvbi56b29tLmVuYWJsZWQgIT09ICd1bmRlZmluZWQnKSkge1xuICAgICAgICAgICAgYzNqc29uLnpvb20uZW5hYmxlZCA9IGpzb24uem9vbS5lbmFibGVkO1xuICAgICAgICB9XG5cbiAgICAgICAgY29uc29sZS5sb2coYzNqc29uKTtcbiAgICAgICAgcmV0dXJuIGMzanNvbjtcbiAgICB9XG5cbiAgICBnZXRDaGFydEJ5QmluZElkKGlkKSB7XG4gICAgICAgIGxldCBpbmRleCA9IDA7XG4gICAgICAgIHdoaWxlIChpbmRleCA8IHRoaXMuY2hhcnRzLmxlbmd0aCkge1xuICAgICAgICAgICAgaWYgKHRoaXMuY2hhcnRzW2luZGV4XS5iaW5kdG8gPT09ICcjJyArIGlkKSB7XG4gICAgICAgICAgICAgICAgcmV0dXJuIHRoaXMuY2hhcnRzW2luZGV4XTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGluZGV4ICs9IDE7XG4gICAgICAgIH1cbiAgICAgICAgcmV0dXJuIG51bGw7XG4gICAgfVxuXG4gICAgYWRkQ2xpY2tMaXN0ZW5lcnMoKSB7XG4gICAgICAgIGNvbnN0IHNjb3BlID0gdGhpcztcbiAgICAgICAgbGV0IGJ1dHRvbnMgPSBkb2N1bWVudC5nZXRFbGVtZW50c0J5Q2xhc3NOYW1lKCdjNGdfY2hhcnRfcmFuZ2VfYnV0dG9uJyk7XG4gICAgICAgIGxldCBpbmRleCA9IDA7XG4gICAgICAgIHdoaWxlIChpbmRleCA8IGJ1dHRvbnMubGVuZ3RoKSB7XG4gICAgICAgICAgICBidXR0b25zLml0ZW0oaW5kZXgpLmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICAgICAgbGV0IGNoYXJ0ID0gc2NvcGUuZ2V0Q2hhcnRCeUJpbmRJZCh0aGlzLmRhdGFzZXQudGFyZ2V0KTtcbiAgICAgICAgICAgICAgICBjaGFydC5yYW5nZSh0aGlzLmRhdGFzZXQucmFuZ2UpO1xuICAgICAgICAgICAgICAgIGNoYXJ0LnVwZGF0ZSgpO1xuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICBpbmRleCArPSAxO1xuICAgICAgICB9XG4gICAgfVxufVxuXG5sZXQgdmlzID0gbmV3IFZpcygpO1xudmlzLmdlbmVyYXRlQ2hhcnRzKCk7XG52aXMuYWRkQ2xpY2tMaXN0ZW5lcnMoKTtcbiJdLCJzb3VyY2VSb290IjoiIn0=