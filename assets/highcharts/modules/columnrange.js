/* highcharts/modules/columnrange.js
 * Local build of the columnrange series module
 * Based on Highcharts v12+ source
 */

(function (Highcharts) {
    'use strict';

    // Register columnrange series type
    function initColumnRange(Highcharts) {
        var columnPrototype = Highcharts.seriesTypes.column.prototype,
            rangePrototype = Highcharts.seriesTypes.arearange.prototype,
            each = Highcharts.each;

        Highcharts.seriesType(
            'columnrange',                 // new series name
            'column',                     // base series
            Highcharts.merge(Highcharts.defaultPlotOptions.column, {
                pointRange: null,
                tooltip: {
                    valueSuffix: ''
                }
            }),
            {
                translate: function () {
                    var series = this;

                    rangePrototype.translate.apply(series);

                    each(series.points, function (point) {
                        var shapeArgs = point.shapeArgs,
                            height = Math.abs(shapeArgs.y);
                        // Swap dimensions for columnrange
                        shapeArgs.height = height;
                        shapeArgs.y = Math.min(point.plotHigh, point.plotLow);
                    });
                }
            },
            {
                // No point class override needed here
            }
        );
    }

    if (Highcharts) {
        initColumnRange(Highcharts);
    }
}(Highcharts));