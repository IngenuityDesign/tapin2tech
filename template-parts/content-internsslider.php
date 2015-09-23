<?php 
  $interns = visainterns_internSlider(get_the_ID());/*[
      ['image'=>'profile_img.png',
       'name'=>'JUELIA',
       'table'=>[
              ['Technology, Business, Management','Foster City, M4','Clarity PPM Business Requirements & Workforce'],
         ],
         'desc'=>'This summer, I gained skills and insight into process analysis and project management by writing requirements to transition important financial and non-financial data from offline spreadsheets to Clarity PPM systems. This involved communicating with developers and Clarity experts, writing test scenarios, and critical thinking in Agile. Other valuable skills that I acquired include designing Visio process diagrams as I attempted to piece together the Annual Planning Process for projects in Technology. Additionally, I was directly involved in re-designing and automating key deliverables for Workforce, and their eventual load for the Executive Dashboard project.',
      ],
      ['image'=>'profile_img.png',
       'name'=>'JUELIA',
       'table'=>[
              ['Technology, Business, Management','B.S. Business Administration','Clarity PPM Business Requirements & Workforce'],
         ],
         'desc'=>'This summer, I gained skills and insight into process analysis and project management by writing requirements to transition important financial and non-financial data from offline spreadsheets to Clarity PPM systems. This involved communicating with developers and Clarity experts, writing test scenarios, and critical thinking in Agile. Other valuable skills that I acquired include designing Visio process diagrams as I attempted to piece together the Annual Planning Process for projects in Technology. Additionally, I was directly involved in re-designing and automating key deliverables for Workforce, and their eventual load for the Executive Dashboard project.',
      ],
  ];*/
  $labels = ['Department','Office Location', 'Title of Project(s)'];?>
<div id="carousel1" class="carousel slide " data-interval="false" data-ride="carousel">
<h3 class="bold">MEET SOME OF OUR INTERNS & NEW GRADS <br><small>Who are already making an impact in the world.</small></h3>
 <div class="slide-nav">
      <a class="left carousel-control" href="#carousel1" role="button" data-slide="prev"><img src="<?php get_images_path();?>left_grey.png" alt="left" />
        <span class="sr-only">Previous</span>
      </a>
      <a class="right carousel-control" href="#carousel1" role="button" data-slide="next"><img src="<?php get_images_path();?>right_grey.png" alt="right" />
        <span class="sr-only">Next</span>
      </a>
    </div> 
    <!-- Wrapper for slides -->
  <div class="carousel-inner" role="listbox">

    <?php foreach($interns as $k=>$intern): ?>
    <?php $cls = ($k==0) ? 'active' : '';   ?>
    <div class="item <?php echo $cls; ?>">
      <div class="top">
           <img class="img-circle img-responsive" src="<?php echo $intern['image']; ?>" /><?php //get_images_path();?>
      </div>
      <h1 class="intern-name bold"><?php echo $intern['name']; ?></h1>
      <div class="bottom">
          <!--<table class="table table-bordered table-condensed">-->
           <?php //foreach($intern['table'] as $k=>$row): //d($row);?>
            <div id="table" class="row-eq-height">
              <div class="col-xs-12 col-md-4"><h3><?php echo $labels[0];?></h3><p><?php echo $intern['table'][0];?></p></div>
              <div class="col-xs-12 col-md-4"><h3><?php echo $labels[1];?></h3><p><?php echo $intern['table'][1];?></p></div>
              <div class="col-xs-12 col-md-4"><h3><?php echo $labels[2];?></h3><p><?php echo $intern['table'][2];?></p></div>
            </div>
            <?php //endforeach; ?>
          <!--</table>-->
          <div class="description"><?php echo $intern['description'];?></div>
          <div class="clear"></div>
      </div>
    </div>
    <?php endforeach; ?>
    <div class="clear"></div>
  </div>
  </div>
<div class="clear"></div>
     <a class="activator closed">
      <div class="bio-activator">   <span></span>   </div>
      <!--<img src="<?php //get_images_path();?>activate_btn.png" />-->
      <span></span>
    </a>

    <script>
    jQuery(document).ready(function() {  
          jQuery(".carousel-inner").swipe( {
            swipeLeft:function(event, direction, distance, duration, fingerCount) {
              jQuery(this).parent().carousel('next'); 
            },
            swipeRight: function() {
              jQuery(this).parent().carousel('prev'); 
            },
            threshold:0
          });
      });
    </script>