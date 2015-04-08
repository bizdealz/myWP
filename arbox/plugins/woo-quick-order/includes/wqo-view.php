<?php
add_shortcode('woo_quick_order', 'wqo_product');

function wqo_addtocart() {
  global $woocommerce;
  $vid=$_POST['wqo_prod_var_id'];
  $pid=$_POST['wqo_prod_id'];
  $vid=$_POST['wqo_prod_var_id'];
  $pqty=$_POST['wqo_prod_qty'];

  if($vid==0){
    $product = WC_Product_Factory::get_product($pid);    
  }else{
    $product = WC_Product_Factory::get_product($vid);    
  }
  $stock=$product->get_stock_quantity();
  $availability = $product->get_availability();
 
  if($availability['class']=='out-of-stock'){
    echo 'Out of stock';
    exit;
  }
       
  if($stock!=''){
    	foreach($woocommerce->cart->cart_contents as $cart_item_key => $values ) {
        $c_item_id='';
        $c_stock='';
        if($values['variation_id']!=''){
          $c_item_id=$values['variation_id'];
        }else{
          $c_item_id=$values['product_id'];
        }
        $c_stock=$values['quantity']+$pqty;
        
        if($vid==0 && $pid==$c_item_id && $c_stock>$stock){
          $product = WC_Product_Factory::get_product($pid);
          echo 'You have cross the stock limit';
          exit;
        }else if($vid==$c_item_id && $c_stock>$stock){
          $product = WC_Product_Factory::get_product($vid);
          echo 'You have cross the stock limit';
          exit;
        }        
	   }    
  }
 
  if($vid==0){
    $z=$woocommerce->cart->add_to_cart($pid,$pqty,null, null, null );
  }else{    
    $z=$woocommerce->cart->add_to_cart($pid, $pqty, $vid, $product->get_variation_attributes(),null);
  }
  echo '1';
  
  exit;
}
function wqo_cart_amount(){
  global $woocommerce;
  echo $woocommerce->cart->get_cart_total();  
  exit;
}

function wqo_product() {
  if (!class_exists('Woocommerce')) {
    echo '<div id="message" class="error"><p>Please Activate Wp WooCommerce Plugin</p></div>';
    return false;
  }
  global $woocommerce;
  if(get_option('wqo_image_size')){
    $wqo_img_size=get_option('wqo_image_size');
  }else{
    $wqo_img_size=40;
  }
  if(get_option('wqo_display_mini_cart')==1){  
  ?>

<link rel='stylesheet'  href='<?php echo WQO_BASE_URL.'/css/template_'.get_option('wqo_cart_template').'.css'; ?>' type='text/css' />
<?php
  }
?>
<form method="post" id="wqo_options">
  <?php
    echo woocommerce_product_dropdown_categories( array(), 1, 0, '' );
    //die('okzzz');
  ?>  
  <select name="wqo_front_order_by">
      <option value="date" <?php if ($_POST['wqo_front_order_by']=='date'):?> selected="selected"<?php endif;?>>Date</option>
      <option value="name" <?php if ($_POST['wqo_front_order_by']=='name'):?> selected="selected"<?php endif;?>>Name</option>
<!--      <option value="_sale_price">Price</option>                -->
  </select>
  <select name="wqo_front_order">
      <option value="ASC" <?php if ($_POST['wqo_front_order']=='ASC'):?> selected="selected"<?php endif;?>>ASC</option>
      <option value="DESC" <?php if ($_POST['wqo_front_order']=='DESC'):?> selected="selected"<?php endif;?>>DESC</option>                
  </select>

  <input type="hidden" value="1" name="wqo_hval" />
  <input type="submit" class="wqo_search" name="wqo_btn_search" value="Search"/>
</form> <br /> 
  <?php
  
  
  $cart_url = $woocommerce->cart->get_cart_url();  
  ?>
<div class="span4 alertAdd" style="opacity: 1; display: block;">
  <div class="alert alert-info"id="wqo_alert_info" style="display: none;"> Added to your cart </div>
</div>
<div id="wqo_cart_amount" class="wqo_cart_amount" onClick="testing();">
  <a href="<?php echo$cart_url;?>"><div id="wqo_cart_price" class="wqo_cart_price"><?php echo $woocommerce->cart->get_cart_total(); ?></div></a>  
</div>
<script>  
  //jQuery('#dropdown_product_cat option[value=]').text('All products');
  function wqo_add_prod(pid,vid){
    var qty= jQuery('#product_qty_'+vid).val();
    if(qty==0){
      jQuery('#wqo_alert_info').text('Out of Stock');
      jQuery('#wqo_alert_info').show()
      setTimeout(function(){jQuery('#wqo_alert_info').hide()}, 1500);      
      return false;
    }
    if(vid==0){
      qty= jQuery('#product_qty_'+pid).val();
    }
    
    var ajax_url = '<?php echo admin_url( 'admin-ajax.php' ); ?>';
        jQuery.ajax({
          type: "POST",
          url:ajax_url,
          data : {
                  'action':          'wqo_addtocart',
                  'wqo_prod_id':     pid,
                  'wqo_prod_var_id': vid,
                  'wqo_prod_qty':    qty
          },
          success: function(response){            
            if(response==1){
              jQuery('#wqo_alert_info').text('Added to your cart');
            }else{
              jQuery('#wqo_alert_info').text(response);
            }
            
            jQuery.ajax({
              type: "POST",
              url:ajax_url,
              data : {'action': 'wqo_cart_amount'},
              success: function(data){             
                jQuery('#wqo_cart_price').html(data);
              }
            });
            
             jQuery('#wqo_alert_info').show()
             setTimeout(function(){jQuery('#wqo_alert_info').hide()}, 2000);          
          }
        });
  }
  jQuery(document).ready(function(){
    jQuery(".ajax").colorbox();
  });
  var plugin_url='<? echo plugins_url(); ?>/woo-quick-order/includes/wqo-popup-data.php';
</script>  
<?php
  $ordby='date';
  $ord='DESC';
  if(isset($_POST['wqo_hval']) && $_POST['product_cat']==''){
    $ordby=$_POST['wqo_front_order_by'];
    $ord=$_POST['wqo_front_order'];
  }

  if(isset($_POST['wqo_hval']) && $_POST['product_cat']!=''){
    
    $category_id = get_cat_ID($_POST['wqo_front_category']);
    $args = array(
			'post_type'				=> 'product',
			'post_status' 			=> 'publish',
			/*'ignore_sticky_posts'	=> 1,*/
			'orderby' 				=> $_POST['wqo_front_order_by'],
			'order' 				=> $_POST['wqo_front_order'],
      'type' => 'numeric',
			'posts_per_page' 		=> 1000,
			'meta_query' 			=> array(
				array(
					'key' 			=> '_visibility',
					'value' 		=> array('catalog', 'visible'),
					'compare' 	=> 'IN'
				)
			),
			'tax_query' 			=> array(
            array(
            'taxonomy' 		=> 'product_cat',
            'terms' 		=> array( esc_attr($_POST['product_cat']) ),
            'field' 		=> 'slug',
            'operator' 		=> 'IN'
          )
		    )
		);
    
  }else{
    $args = array(
        'post_status'         => 'publish',
        'post_type'           => 'product',
        /*'ignore_sticky_posts'	=> 1,*/
        'orderby' 				    => $ordby,
        'type' => 'numeric',
			  'order' 				      => $ord,
        'posts_per_page' 		=> 1000
   );
  }

    $loop = new WP_Query( $args );
    
      if ($loop->have_posts()){
        echo '<table><tr><th>Name</th><th>Image</th><th>Price</th><th>Quantity</th><th></th></tr>';
        foreach($loop->posts as $val){
            $variation_display=false;
            $variation=false;
            if (get_option('wqo_display_variation')=='1'){
              $variation_display= true;
            }            
            
            if ($variation_display == true){
                $variation_query = new WP_Query();
                $args_variation = array(
                  'post_status' => 'publish',
                  'post_type' => 'product_variation',
                  'post_parent' => $val->ID
                );                
                $variation_query->query($args_variation);

                if ($variation_query->have_posts()){
                  $variation=true;
                }
            }
                                  
            if($variation==true){
              $product_name_org=$val->post_title;
              $product_url = get_permalink($val->ID);
              
              foreach($variation_query->posts as $var_data){
                 $product = WC_Product_Factory::get_product($var_data->ID);
                 $max_stock=500;                                  
                 if($product->variation_has_stock==1){
                   //$max_stock=$product->total_stock;
                   $max_stock=$product->get_stock_quantity();
                 }
                 $availability=$product->get_availability();
                  if($availability['class']=='out-of-stock'){
                    $max_stock=0;
                  }
                 
                  $prod_att=woocommerce_get_formatted_variation($product->get_variation_attributes(),true);                  
                  $product_name='<a href="'. plugins_url().'/woo-quick-order/includes/wqo-popup-data.php?pid='.$var_data->ID.'" class="ajax">'.$product_name_org.'('.$prod_att.')</a>';
                  $product_price=woocommerce_price($product->get_price());
                  $img_url = WQO_BASE_URL. '/images/placeholder.png';
                  if (has_post_thumbnail($var_data->ID)){
                    $img_url2 = wp_get_attachment_url( get_post_thumbnail_id($var_data->ID) );                    
                    $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($var_data->ID), 'thumbnail' );
                    $img_url = $thumb['0'];
                    
                  } else if (has_post_thumbnail($val->ID)){
                    $img_url2 = wp_get_attachment_url( get_post_thumbnail_id($val->ID) );                    
                    $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($val->ID), 'thumbnail' );
                    $img_url = $thumb['0'];                   
                  }
                  
                  if (get_option('wqo_display_image_preview')=='1'){
                  echo '<tr><td>'.$product_name.'</td><td><a href="'.$img_url2.'" class="preview"><img src="'.$img_url.'" height="'.$wqo_img_size.'" width="'.$wqo_img_size.'" /></a></td><td>'.$product_price.'</td>';
                  }else{
                    echo '<tr><td>'.$product_name.'</td><td><img src="'.$img_url.'" height="'.$wqo_img_size.'" width="'.$wqo_img_size.'" /></td><td>'.$product_price.'</td>';
                  }                  
                  ?>
                    <td>
                                                                   
                        <?php
                        if($max_stock!=0){                            
                          ?><input type="number" style="width:70px;" value="1" min="1"  max="<?php echo $max_stock;?>" name="product_qty_<?php echo $var_data->ID?>" id="product_qty_<?php echo $var_data->ID?>" /><?php                            
                        }else{                            
                           ?><input type="number" style="width:70px;" value="0" min="0" max="0" name="product_qty_<?php echo $var_data->ID?>" id="product_qty_<?php echo $var_data->ID?>" /><?php
                        }
                        ?>
                      
                    </td>  
                  <?php
                  echo '<td><div class="wqo_add_btn"><a onclick="wqo_add_prod('.$val->ID.','.$var_data->ID.');"><div class="wqo_add_cart"></div></a></div></td></tr>';
              }              
            }else{
                wqo_show_prod($val->ID,$wqo_img_size, $val->post_title);
            }
        }//end foreach
          echo '</table>';
      }//if  
}

function wqo_show_prod($id, $wqo_img_size, $post_title){
    $max_stock=500;
    $product = WC_Product_Factory::get_product($id);                
    if($product->get_stock_quantity()!=''){
      $max_stock=$product->get_stock_quantity();
    }
    $availability=$product->get_availability();
    if($availability['class']=='out-of-stock'){
      $max_stock=0;
    }
    $product_url = get_permalink($id);                
    $product_name='<a href="'. plugins_url().'/woo-quick-order/includes/wqo-popup-data.php?pid='.$id.'" class="ajax">'.$post_title.'</a>';
    $product = get_product($id);
    $product_price =$product->get_price_html();                

    if (has_post_thumbnail($id)){
        $img_url2 = wp_get_attachment_url( get_post_thumbnail_id($id,'thumbnail'));
        $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($id), 'thumbnail' );
        $img_url = $thumb['0'];

    } else {
        $img_url=WQO_BASE_URL. '/images/placeholder.png';
        $img_url2=$img_url;
    }
    if (get_option('wqo_display_image_preview')=='1'){
      echo '<tr><td>'.$product_name.'</td><td><a href="'.$img_url2.'" class="preview"><img src="'.$img_url.'" height="'.$wqo_img_size.'" width="'.$wqo_img_size.'" /></a></td><td>'.$product_price.'</td>';
    }else{                
      echo '<tr><td>'.$product_name.'</td><td><img src="'.$img_url.'" height="'.$wqo_img_size.'" width="'.$wqo_img_size.'" /></td><td>'.$product_price.'</td>';
    }
    ?>
      <td>
          <?php
          if($max_stock!=0){
            ?><input type="number" style="width:70px;" value="1" min="0" max="0<?php echo $max_stock;?>" name="product_qty_<?php echo $id;?>" id="product_qty_<?php echo $id;?>" /><?php
          }else{
            ?><input type="number" style="width:70px;" value="0" min="0" max="0" name="product_qty_<?php echo $id;?>" id="product_qty_<?php echo $id;?>" /><?php
          }
          ?>        
      </td>  
    <?php
    echo '<td><div class="wqo_add_btn"><a onclick="wqo_add_prod('.$id.', 0);"><div class="wqo_add_cart"></div></a></div></td></tr>';
}
add_action( 'wp_ajax_nopriv_wqo_addtocart','wqo_addtocart' );
add_action( 'wp_ajax_wqo_addtocart', 'wqo_addtocart' );
add_action( 'wp_ajax_nopriv_wqo_cart_amount','wqo_cart_amount' );
add_action( 'wp_ajax_wqo_cart_amount', 'wqo_cart_amount' );
?>