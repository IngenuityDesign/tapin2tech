<?php
  $campuses = visainterns_campusSlider(get_the_ID()); /*[
      ['nd.png','osu.png','wvu.png'],
      ['nd.png','osu.png','wvu.png'],
      ['nd.png','osu.png','wvu.png'],
  ];*/ //d($campuses);
?>
 <div id="carousel2" class="visible-lg carousel slide" data-interval="false" data-ride="carousel">
      <div class="clear"></div>
      <!-- Carousel items -->
      <div class="carousel-inner">
      <?php foreach($campuses['by3'] as $k=>$row): ?>
          <div class="item <?php echo (($k==$campuses['start']) ? 'active': ''); ?>">
              <div class="row">
                  <?php foreach($row as $i=>$img): ?>
                    <?php $cls = ($img['isPassed'] ? 'date-passed' : ''); ?>
                    <div class="col-xs-4 <?php echo $cls; ?>">
                      <a target="_blank" href="https://recpass.com/visa"><img src="<?php echo $img['image']; ?>" alt="Image" class="img-responsive">
                      <div class="overlay">
                        <div class="loc"><?php echo $img['school_name']; ?></div>
                        <div class="date"><?php echo $img['date']; ?></div>
                      </div>
                      </a>
                    </div>
                  <?php endforeach; ?>
              </div>
              <!--/row-->
          </div>
          <!--/item-->
     <?php endforeach; ?>
      <a class="left carousel-control" href="#carousel2" role="button" data-slide="prev"><img src="<?php get_images_path();?>l_arrow.png" alt="left" /></a>
      <a class="right carousel-control" href="#carousel2" role="button" data-slide="next"><img src="<?php get_images_path();?>r_arrow.png" alt="right" /></a>
    </div>
      <!--/carousel-inner-->

      <div class="clear"></div>
</div>
  <!--/carousel2-->
<div id="carousel2mobile" class="hidden-lg carousel slide" data-interval="false" data-ride="carousel">
      <div class="clear"></div>
      <!-- Carousel items -->
      <div class="carousel-inner">
       <?php foreach($campuses['by4'] as $k=>$row): ?>
          <?php if(count($row) <4): $row[3] = ['school_name'=>'','date'=>'']; endif;?>
          <div class="row item <?php echo (($k==0) ? 'active': ''); ?>">
              <table class="table table-bordered table-condensed">
                  <?php foreach($row as $i=>$img): ?>
                    <?php if($i==0 || $i==2):?> <tr> <?php endif;?>
                      <td class="col-xs-12">
                          <div class="loc"><?php echo $img['school_name']; ?></div>
                          <div class="date"><?php echo $img['date']; ?></div>
                      </td>
                    <?php if($i==1 || $i==3):?> </tr> <?php endif;?>
                  <?php endforeach; ?>
              </table>
              <!--/row-->
          </div>
          <!--/item-->
     <?php endforeach; ?>

      <a class="left carousel-control" href="#carousel2mobile" role="button" data-slide="prev"><img class="img-responsive" src="<?php get_images_path();?>scroll_l.png" alt="left" /></a>
      <a class="right carousel-control" href="#carousel2mobile" role="button" data-slide="next"><img class="img-responsive" src="<?php get_images_path();?>scroll_r.png" alt="right" /></a>
    </div>
      <!--/carousel-inner-->

      <div class="clear"></div>
</div>
  <!--/carousel2-->
  <script>
  var clicked = false;

  jQuery("#carousel2 .item .col-xs-4 a").on({
      mouseenter: function () {
          jQuery(this).find('.overlay').addClass('active');
      },
      mouseleave: function () {
          jQuery(this).parent().find('.overlay').removeClass('active');
      }
  });
  </script>
