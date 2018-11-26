<widget class="large">
    <widget-title>Топ 5 блюд</widget-title>
    <widget-content>
        <?php if(count($topDishes) > 0):?>
            <div class="chart" id="chart"></div>
            <ul class="legend">
                <?php foreach($topDishes as $id => $dish):?>
                    <li>
                        <div class="legend-color <?php echo "clr$id";?>"></div>
                        <div class="legend-name"><?php echo $dish['name'];?></div>
                        <div class="legend-summ green"><?php echo $dish['summ'];?></div>
                    </li>
                <?php endforeach;?>
            </ul>
        <?php else:?>
            <div style="line-height:240px;text-align:center;font-weight:bold;">Нет данных для сбора статистики</div>
        <?php endif;?>
        <script>
            var maxValue = 25;
            var container = $('.chart');
            var dataset = [<?php echo implode(',',$sectorsArray);?>];

            var addSector = function(index, data, startAngle, collapse) {
              var sectorDeg = 3.6 * data;
              var skewDeg = 90 + sectorDeg;
              var rotateDeg = startAngle;
              if (collapse) {
                skewDeg++;
              }

              var sector = $('<div>', {
                'class': 'sector clr' + index
              }).css({
                'transform': 'rotate(' + rotateDeg + 'deg) skewY(' + skewDeg + 'deg)'
              });
              container.append(sector);

              return startAngle + sectorDeg;
            };

            dataset.reduce(function (prev, curr, index) {
              return (function addPart(data, angle) {
                if (data <= maxValue) {
                  return addSector(index, data, angle, false);
                }
                return addPart(data - maxValue, addSector(index, maxValue, angle, true));
              })(curr, prev);
            }, 0);            
        </script>
    </widget-content>
</widget>