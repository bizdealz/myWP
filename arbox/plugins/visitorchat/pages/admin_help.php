<?php
/**
 * 
 * ClientEngage VisitorChat for WordPress (http://www.clientengage.com)
 * Copyright 2013, ClientEngage (http://www.clientengage.com)
 *
 * You must have purchased a valid license from CodeCanyon in order to have 
 * the permission to use this file.
 * 
 * You may only use this file according to the respective licensing terms 
 * you agreed to when purchasing this item on CodeCanyon.
 * 
 * All PHP code utilising WordPress features is licensed as GPL version 2 or 
 * later. External libraries placed in the "vendors"-directory are subject to
 * their respective licensing terms (take note of their licenses).
 *  
 * However, other ClientEngage assets such as JavaScript code, 
 * CSS styles and images are not licensed as GPL version 2 or later and you are 
 * required to abide by the CodeCanyon Regular License. 
 * 
 *
 * @author          ClientEngage <contact@clientengage.com>
 * @copyright       Copyright 2013, ClientEngage (http://www.clientengage.com)
 * @link            http://www.clientengage.com ClientEngage
 * @since           ClientEngage VisitorChat for WordPress v 1.0
 * @license         GPL version 2 or later - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * 
 */
defined('ABSPATH') or die('No direct access.');
?>
<div class="wrap">

    <div class="icon32 icon-settings"><br /></div>
    <h2><?php echo __('VisitorChat About, Help & Credits', 'visitorchat'); ?></h2>


    <div id="poststuff">
        <div id="post-body" class="metabox-holder ">
            <div class="stuffbox">
                <h3><?php echo __('About', 'visitorchat'); ?></h3>
                <div class="inside">




                    <div class="well">
                        <p class="lead">
                            <?php echo __('The ClientEngage VisitorChat for WordPress is a fully-featured real-time chat for your WordPress website. And best of all: VisitorChat comes with a Windows-based client out-of-the-box. Without having to visit the VisitorChat’s admin-interface you will be notified from your Windows tray and can start chatting straight away!'); ?>
                        </p>
                        <div class="well">ClientEngage: ClientEngage VisitorChat for WordPress (<a href="http://www.clientengage.com" target="_blank">www.clientengage.com</a>)<br />


                            <p>Copyright 2013: ClientEngage (<a href="http://www.clientengage.com" target="_blank">www.clientengage.com</a>)</p>
                            <p>You must have purchased a valid license from CodeCanyon in order to have 
                                the permission to use this file.</p>

                            <p>You may only use this file according to the respective licensing terms 
                                you agreed to when purchasing this item on CodeCanyon.</p>

                            <p>All PHP code utilising WordPress features is licensed as GPL version 2 or 
                                later. External libraries placed in the "vendors"-directory are subject to
                                their respective licensing terms (take note of their licenses).</p>

                            <p>However, other ClientEngage assets such as JavaScript code, 
                                CSS styles and images are not licensed as GPL version 2 or later and you are 
                                required to abide by the CodeCanyon Regular License. </p>

                            <p> <strong>Author:</strong>          ClientEngage <a href="mailto:contact@clientengage.com">contact@clientengage.com</a><br />
                                <strong>Copyright:</strong>       Copyright 2013, ClientEngage (<a href="http://www.clientengage.com" target="_blank">www.clientengage.com</a>)<br />
                                <strong>Link:</strong>            <a href="http://www.clientengage.com" target="_blank">www.clientengage.com</a> ClientEngage</p>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div id="poststuff">
        <div id="post-body" class="metabox-holder ">
            <div class="stuffbox">
                <h3><?php echo __('Help', 'visitorchat'); ?></h3>
                <div class="inside">

                    <h4>Understanding Opt-In and Opt-Out</h4>
                    <p>The default setting is <strong>Opt-Out</strong>. This means that VisitorChat will always be shown, unless you specifically hide the chat through individual posts'/pages' meta-settings.</p>
                    <p>You can also choose <strong>Opt-In</strong>. This means that VisitorChat will only be shown if you specifically decide to do so by using individual posts'/pages' meta-settings. </p>

                    <hr />

                    <h4>Toggling VisitorChat on individual posts & pages</h4>
                    <p>If you wish to show or hide VisitorChat on individual posts or pages, simply "edit" the page and perform your settings in the "VisitorChat Meta-Box".</p>

                    <hr />

                    <h4>VisitorChat ShortCodes</h4>
                    <p>You can use the VisitorChat shortcodes to conditionally display content according to whether your chat's status is offline, or online:</p>
                    <p>[visitorchat status="online"]This content is shown if the chat is online.[/visitorchat]</p>
                    <p>[visitorchat status="offline"]This content is shown if the chat is offline.[/visitorchat]</p>

                    <hr />

                    <h4>Executing code when VisitorChat is loaded</h4>
                    <p>Simply paste the code below into one of your script-tags and insert your code within the function's body - it will be executed once VisitorChat is fully initialised:</p>
                    <pre>
    var CEVC_Onload = function() {
        // your code goes here
    };
                    </pre>
                    <hr />

                    <h4>Programmatically opening VisitorChat</h4>
                    <p>Simply paste the code below into one of your script-tags (the event will only be attached once VisitorChat is loaded):</p>
                    <pre>
    var CEVC_Onload = function() {
        $(".btnOpenChat").on("click", function(e){
             e.preventDefault();
             if(!$(".vc_chat_container").hasClass(".vc_box_open"))
             {
                $(".vc_chat_head").trigger("click");
             }
        });
    };
                    </pre>
                    <p>And use a hyperlink such as the following to trigger the action:</p>
                    <pre>
    &lt;a href=&quot;#&quot; class=&quot;btnOpenChat&quot;&gt;Open Chat&lt;/a&gt;
                    </pre>


                </div>
            </div>
        </div>
    </div>


    <div id="poststuff">
        <div id="post-body" class="metabox-holder ">
            <div class="stuffbox">
                <h3><?php echo __('Third-Party Components & Credits', 'visitorchat'); ?></h3>
                <div class="inside">

                    <h2>Third Party Components: Credits</h2>
                    <p class="lead">This application was made possible with the help of various third party components. For a list of attributions, please see below.</p>

                    <h3>Twitter Bootstrap</h3>
                    <p>
                        Many of this application's visual aspects were made possible by using the Twitter Bootstrap framework.<br />
                        <strong>License:</strong> Apache License v2.0 <br />
                        <strong>Path:</strong> <code>app\webroot\css\bootstrap\LICENSE</code> <br />
                        <strong>Website:</strong> <a href="http://twitter.github.com/bootstrap/" target="_blank">http://twitter.github.com/bootstrap/</a> <br />
                    </p>
                    <hr />

                    <h3>jQuery</h3>
                    <p>
                        Many aspects of the user interaction logic use jQuery.<br />
                        <strong>License:</strong> MIT License <br />
                        <strong>Path:</strong> <code>app\webroot\js\jquery\MIT-LICENSE.txt</code> <br />
                        <strong>Website:</strong> <a href="http://jquery.com/" target="_blank">http://jquery.com/</a> <br />
                    </p>
                    <hr />


                    <h3>CryptoJS 3.1</h3>
                    <strong>License:</strong> New BSD License <br />
                    <strong>Path:</strong> <code>https://code.google.com/p/crypto-js/wiki/License</code> <br />
                    <strong>Website:</strong> <a href="https://code.google.com/p/crypto-js/" target="_blank">https://code.google.com/p/crypto-js/</a> <br />
                    </p>
                    <hr />


                    <h3>php-user-agent</h3>
                    <strong>License:</strong> MIT License <br />
                    <strong>Path:</strong> <code>app\Vendor\php-user-agent\LICENSE</code> <br />
                    <strong>Website:</strong> <a href="https://github.com/ornicar/php-user-agent" target="_blank">https://github.com/ornicar/php-user-agent</a> <br />
                    </p>
                    <hr />


                    <h3>jsmin-php</h3>
                    <strong>License:</strong> MIT License <br />
                    <strong>Path:</strong> <code>app\Vendor\jsmin\jsmin.php</code> <br />
                    <strong>Website:</strong> <a href="https://github.com/rgrove/jsmin-php" target="_blank">https://github.com/rgrove/jsmin-php</a> <br />
                    </p>
                    <hr />




                </div>
            </div>
        </div>
    </div>



    <div id="poststuff">
        <div id="post-body" class="metabox-holder ">
            <div class="stuffbox">
                <h3><?php echo __('License Details', 'visitorchat'); ?></h3>
                <div class="inside">

                    <p>  This is the end-user license agreement for ClientEngage VisitorChat for 
                        WordPress (hereafter referred to as SOFTWARE PRODUCT).</p>

                    <p>Please read the terms and conditions of this license agreement carefully before 
                        continuing with this installation: ClientEngage's End-User License Agreement 
                        ("EULA") is a legal agreement between you (either an individual or a single 
                        entity) and ClientEngage for this software product.</p>

                    <p>You must have purchased a valid license from CodeCanyon in order to have the 
                        permission to use this file.</p>

                    <p>You may only use this software according to the respective licensing terms you 
                        agreed to when purchasing this item on CodeCanyon.</p>

                    <p>1. Description of Other Rights and Limitations.<br />
                        (a) Support Services.<br />
                        ClientEngage may provide you with support services related to the SOFTWARE 
                        PRODUCT ("Support Services"). Any supplemental software code provided to you as 
                        part of the Support Services shall be considered part of the SOFTWARE PRODUCT 
                        and subject to the terms and conditions of this EULA.</p>

                    <p>(b) Compliance with Applicable Laws.<br />
                        You must comply with all applicable laws regarding use of the SOFTWARE PRODUCT.</p>

                    <p>2. No Warranties<br />
                        ClientEngage expressly disclaims any warranty for the SOFTWARE PRODUCT. The 
                        SOFTWARE PRODUCT is provided 'As Is' without any express or implied warranty of 
                        any kind, including but not limited to any warranties of merchantability, 
                        noninfringement, or fitness of a particular purpose. ClientEngage does not 
                        warrant or assume responsibility for the accuracy or completeness of any 
                        information, text, graphics, links or other items contained within the SOFTWARE 
                        PRODUCT. ClientEngage further expressly disclaims any warranty or representation 
                        to Authorized Users or to any third party.</p>

                    <p>3. Limitation of Liability<br />
                        In no event shall ClientEngage be liable for any damages (including, without 
                        limitation, lost profits, business interruption, or lost information) rising out 
                        of 'Authorized Users' use of or inability to use the SOFTWARE PRODUCT, even if 
                        ClientEngage has been advised of the possibility of such damages. In no event 
                        will ClientEngage be liable for loss of data or for indirect, special, 
                        incidental, consequential (including lost profit), or other damages based in 
                        contract, tort or otherwise. ClientEngage shall have no liability with respect 
                        to the content of the SOFTWARE PRODUCT or any part thereof, including but not 
                        limited to errors or omissions contained therein, libel, infringements of rights 
                        of publicity, privacy, trademark rights, business interruption, personal injury, 
                        loss of privacy, moral rights or the disclosure of confidential information.</p>




                </div>
            </div>
        </div>
    </div>



    <p style="margin-top:15px; font-style: italic;font-weight: bold;color: #26779a;">ClientEngage develops a range of solutions that help you get closer to your customers. Visit <a href="http://www.clientengage.com" target="_blank" style="color:#72a1c6;">www.clientengage.com</a> to find out more.</p>

</div>