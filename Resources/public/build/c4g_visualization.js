!function(t){var e={};function a(n){if(e[n])return e[n].exports;var o=e[n]={i:n,l:!1,exports:{}};return t[n].call(o.exports,o,o.exports,a),o.l=!0,o.exports}a.m=t,a.c=e,a.d=function(t,e,n){a.o(t,e)||Object.defineProperty(t,e,{enumerable:!0,get:n})},a.r=function(t){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})},a.t=function(t,e){if(1&e&&(t=a(t)),8&e)return t;if(4&e&&"object"==typeof t&&t&&t.__esModule)return t;var n=Object.create(null);if(a.r(n),Object.defineProperty(n,"default",{enumerable:!0,value:t}),2&e&&"string"!=typeof t)for(var o in t)a.d(n,o,function(e){return t[e]}.bind(null,o));return n},a.n=function(t){var e=t&&t.__esModule?function(){return t.default}:function(){return t};return a.d(e,"a",e),e},a.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},a.p="",a(a.s=0)}([function(t,e,a){"use strict";var n=function(){function t(t,e){for(var a=0;a<e.length;a++){var n=e[a];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(t,n.key,n)}}return function(e,a,n){return a&&t(e.prototype,a),n&&t(e,n),e}}();var o=new(function(){function t(){!function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,t),this.elements=document.getElementsByClassName("c4g_chart"),this.charts=[]}return n(t,[{key:"generateCharts",value:function(){for(var t=this,e=this,a=0,n=function(){var n=t.elements.item(a);fetch("con4gis/fetchchart/"+n.dataset.chart).then((function(t){return t.json()})).then((function(t){var a={bindto:"#"+n.id,base:t,json:{},range:function(t){this.json=e.parseJson(this.bindto,this.base,this,t)},update:function(){this.chart=c3.generate(this.json)}};a.json=e.parseJson("#"+n.id,t,a),void 0!==t.axis.x.tick&&void 0!==t.axis.x.tick.format&&(a.format=t.axis.x.tick.format,a.json.axis.x.tick.format=function(t){return e.getChartByBindId(n.id).format[t]}),void 0!==t.axis.x.tick&&void 0!==t.axis.x.tick.rotate&&(a.rotate=t.axis.x.tick.rotate,a.json.axis.x.tick.rotate=function(t){return e.getChartByBindId(n.id).rotate[t]}),e.charts.push(a),a.update()})),a+=1};a<this.elements.length;)n()}},{key:"parseJson",value:function(t,e,a){for(var n=arguments.length>3&&void 0!==arguments[3]?arguments[3]:"range_default",o={bindto:t,data:{xs:{},columns:[],types:{},colors:{},names:{},groups:[]},axis:{},tooltip:{format:{}},zoom:{enabled:!1}},r=0;r<e.colors.length;)o.data.colors["y"+r]=e.colors[r],r+=1;var i=void 0,s=void 0;for("range_all"!==n&&(void 0===e.ranges[n]?n="range_all":(i=e.ranges[n].lowerBound,s=e.ranges[n].upperBound)),r=0;r<e.data.length;){o.data.xs["y"+r]="x"+r;for(var u=["x"+r],d=["y"+r],l=0;l<e.data[r].dataPoints.length;)("pie"===e.data[r].type||"range_all"===n||e.data[r].dataPoints[l].x>=i&&e.data[r].dataPoints[l].x<=s)&&(u.push(e.data[r].dataPoints[l].x),d.push(e.data[r].dataPoints[l].y)),l+=1;if(o.data.columns.push(u,d),o.data.types["y"+r]=e.data[r].type,void 0!==e.data[r].name&&(o.data.names["y"+r]=e.data[r].name),void 0!==e.data[r].group){for(;void 0===o.data.groups[e.data[r].group];)o.data.groups.push([]);o.data.groups[e.data[r].group].push("y"+r)}r+=1}if(void 0!==e.axis&&(o.axis=e.axis),void 0!==e.zoom&&void 0!==e.zoom.enabled&&(o.zoom.enabled=e.zoom.enabled),void 0!==e.tooltip&&void 0!==e.tooltip.format&&void 0!==e.tooltip.format.title){a.tooltipformattitle=e.tooltip.format.title;var c=this;o.tooltip.format.title=function(e){return c.getChartByBindId(t.substr(1,t.length)).tooltipformattitle[e]}}return console.log(o),o}},{key:"getChartByBindId",value:function(t){for(var e=0;e<this.charts.length;){if(this.charts[e].bindto==="#"+t)return this.charts[e];e+=1}return null}},{key:"addClickListeners",value:function(){for(var t=this,e=document.getElementsByClassName("c4g_chart_range_button"),a=0;a<e.length;)e.item(a).addEventListener("click",(function(){var e=t.getChartByBindId(this.dataset.target);e.range(this.dataset.range),e.update()})),a+=1}}]),t}());o.generateCharts(),o.addClickListeners()}]);