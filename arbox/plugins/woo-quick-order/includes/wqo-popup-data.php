<?php

require_once('../../../../wp-blog-header.php');
global $wpdb;
global $woocommerce;
$pid=$_GET['pid'];
$product = get_product($pid);
?>

<div class="wqo_popup_con_main">
  <div class="wqo_popup_con_1">
    Product Details
  </div>
  <div class="wqo_popup_con_2">
    <div class="wqo_popup_con_left">
        <?php 

        if (has_post_thumbnail($pid)){ 
          $imgUlr = wp_get_attachment_url( get_post_thumbnail_id($pid) );
          $src = '<img src="'.$imgUlr.'" alt="Placeholder" width="200" />';
        } else {
          $imgUlr=WQO_BASE_URL.'/images/placeholder.png';            
          $src = '<img src="'.$imgUlr.'" alt="Placeholder" width="200"  />';
        }
        echo $src;
        ?>
    </div>
    <div class="wqo_popup_con_right">
              <div class="wqo_popup_title">
                <?php echo $product->get_title();?>
              </div>
              <div class="wqo_popup_price">
                <?php woocommerce_get_template( 'loop/price.php' );?>
              </div>    
              <!-----------sku--------->
              <div class="wqo_product_meta">
                <?php do_action( 'woocommerce_product_meta_start' ); ?>
                <?php if ( $product->is_type( array( 'simple', 'variable' ) ) && get_option( 'woocommerce_enable_sku' ) == 'yes' && $product->get_sku() ) : ?>
                  <span itemprop="productID" class="sku_wrapper"><?php _e( 'SKU:', 'woocommerce' ); ?> <span class="sku"><?php echo $product->get_sku(); ?></span>.</span>
                <?php endif; ?>
                <?php
                  $size = sizeof( get_the_terms( $_GET['pid'], 'product_cat' ) );
                  echo $product->get_categories( ', ', '<span class="posted_in">' . _n( 'Category:', 'Categories:', $size, 'woocommerce' ) . ' ', '.</span>' );
                ?>
                <?php
                  $size = sizeof( get_the_terms( $_GET['pid'], 'product_tag' ) );
                  echo $product->get_tags( ', ', '<br /><span class="tagged_as">' . _n( 'Tag:', 'Tags:', $size, 'woocommerce' ) . ' ', '.</span>' );
                ?>
                <?php do_action( 'woocommerce_product_meta_end' ); ?>
              </div>
    </div>

    <div class="wqo_clear"></div>
    
  </div>
<?php
$product_description = get_post($pid)->post_content;
if($product_description!=''){
  echo '<div class="wqo_popup_con_3">'.$product_description.'</div>';
}
?>

  

  
</div> 
  

  

<style>
.wqo_popup_con_main{
    padding: 0px;
    margin: 0px;
    width: 500px;
    min-height: 400px;
    padding: 10px;
  }
  .wqo_popup_con_1{
    width: 100%;
    font-size: 20px;
    color: brown;
  }
  .wqo_popup_con_2{
    width: 100%;
    margin-top: 10px;
  }
  .wqo_popup_con_3{
    width: 100%;
    margin-top: 10px;
    font-size: 12px;
    color: #666666;
  }
  
  .wqo_popup_con_left{
    float: left;
    width: 50%;
    margin: 0px;    
  }
  .wqo_popup_con_left img{
    border: solid 2px #CCCCCC;
  }  
  .wqo_popup_con_right{
    float: right;
    width: 50%;    
  }
  .wqo_clear{
    clear: both;
  }
  .wqo_popup_title{
    font-size: 22px;
    color: darkorange;
    margin-bottom: 20px;
  }
  .wqo_popup_price{
    font-size: 20px;    
    margin-bottom: 20px;
  }
</style> 