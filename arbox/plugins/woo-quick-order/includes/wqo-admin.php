<?php
function wqo_setting_reset(){
  update_option('wqo_display_variation',1);
  update_option('wqo_image_size',40);
  update_option('wqo_display_mini_cart',1);
  update_option('wqo_display_image_preview',1);
  update_option('wqo_cart_template','black');
}

function wqo_setting(){
    if (!class_exists('Woocommerce')) {
      echo '<div id="message" class="error"><p>Please Activate Wp WooCommerce Plugin</p></div>';
      return false;
    }
    
    if($_POST['wqo_status_submit']==1){	
      update_option('wqo_display_variation',$_POST['wqo_display_variation']);
      update_option('wqo_image_size',$_POST['wqo_image_size']);
      update_option('wqo_display_mini_cart',$_POST['wqo_display_mini_cart']);
      update_option('wqo_display_image_preview',$_POST['wqo_display_image_preview']);
      update_option('wqo_cart_template',$_POST['wqo_cart_template']);    
    }

    if($_POST['wqo_status_submit']==2){
      wqo_setting_reset();   
    }    
    ?>
    <h2>Settings</h2>
    <form method="post" id="wqo_options">	
        <input type="hidden" name="wqo_status_submit" id="wqo_status_submit" value="2"  />
      <table width="100%" cellspacing="2" cellpadding="5" class="editform">
        <tr style="display: none;" valign="top"> 
          <td width="150" scope="row">Display Variations:</td>
          <td>
              <select name="wqo_display_variation">
                  <option value="1"<?php if (get_option('wqo_display_variation')=='1'):?> selected="selected"<?php endif;?>>Yes</option>
<!--                  <option value="0"<?php //if (get_option('wqo_display_variation')=='0'):?> selected="selected"<?php //endif;?>>No</option>                -->
              </select>
          </td>
        </tr>
        
        <tr valign="top"> 
          <td width="150" scope="row">Product image size:</td>
          <td>
              <select name="wqo_image_size">
                  <option value="16"<?php if (get_option('wqo_image_size')==16):?> selected="selected"<?php endif;?>>16x16</option>
                  <option value="32"<?php if (get_option('wqo_image_size')==32):?> selected="selected"<?php endif;?>>32x32</option>
                  <option value="40"<?php if (get_option('wqo_image_size')==40):?> selected="selected"<?php endif;?>>40x40</option>
                  <option value="48"<?php if (get_option('wqo_image_size')==48):?> selected="selected"<?php endif;?>>48x48</option>
                  <option value="64"<?php if (get_option('wqo_image_size')==64):?> selected="selected"<?php endif;?>>64x64</option>
              </select>
          </td>
        </tr>
        <tr valign="top"> 
          <td width="150" scope="row">Display Mini Cart:</td>
          <td>
              <select name="wqo_display_mini_cart">
                  <option value="1"<?php if (get_option('wqo_display_mini_cart')=='1'):?> selected="selected"<?php endif;?>>Yes</option>
                  <option value="0"<?php if (get_option('wqo_display_mini_cart')=='0'):?> selected="selected"<?php endif;?>>No</option>                
              </select>
          </td>
        </tr>
        
        
        
        <tr valign="top"> 
          <td width="150" scope="row">Display Image Preview:</td>
          <td>
              <select name="wqo_display_image_preview">
                  <option value="1"<?php if (get_option('wqo_display_image_preview')=='1'):?> selected="selected"<?php endif;?>>Yes</option>
                  <option value="0"<?php if (get_option('wqo_display_image_preview')=='0'):?> selected="selected"<?php endif;?>>No</option>                
              </select>
          </td>
        </tr>
        <tr valign="top"> 
          <td width="150" scope="row">Mini Cart Template:</td>
          <td>
              <select name="wqo_cart_template">
                  <option value="red"<?php if (get_option('wqo_cart_template')=='red'):?> selected="selected"<?php endif;?>>Red</option>
                  <option value="blue"<?php if (get_option('wqo_cart_template')=='blue'):?> selected="selected"<?php endif;?>>blue</option>
                  <option value="green"<?php if (get_option('wqo_cart_template')=='green'):?> selected="selected"<?php endif;?>>Green</option>
                  <option value="sky"<?php if (get_option('wqo_cart_template')=='sky'):?> selected="selected"<?php endif;?>>Sky</option>
                  <option value="pink"<?php if (get_option('wqo_cart_template')=='pink'):?> selected="selected"<?php endif;?>>Pink</option>
                  <option value="black"<?php if (get_option('wqo_cart_template')=='black'):?> selected="selected"<?php endif;?>>Black</option>
                  <option value="grey"<?php if (get_option('wqo_cart_template')=='grey'):?> selected="selected"<?php endif;?>>Grey</option>
                  <option value="yellow"<?php if (get_option('wqo_cart_template')=='yellow'):?> selected="selected"<?php endif;?>>Yellow</option>
              </select>
          </td>
        </tr>
            <tr valign="top">
            <td colspan="2" scope="row">			
              <input type="button" name="save" onclick="document.getElementById('wqo_status_submit').value='1'; document.getElementById('wqo_options').submit();" value="Save setting" class="button-primary" />
              <input type="button" name="reset" onclick="document.getElementById('wqo_status_submit').value='2'; document.getElementById('wqo_options').submit();" value="Reset to default setting" class="button-primary" />
            </td> 
          </tr>
				</td>
			</tr>
    </table>
  </form>   
<?php
}
?>