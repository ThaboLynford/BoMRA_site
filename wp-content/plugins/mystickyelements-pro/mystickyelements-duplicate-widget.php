<?php 

if(isset($_GET['page']) && $_GET['page'] == "my-sticky-elements-new-widget" && !isset($_GET['copy-from'])) { ?>
    <div class="mystickyelements-popup-form">
        <div class="mystickyelements-popup-overlay"></div>
        <div class="mystickyelements-popup-content">
			<a href="javascript:void(0);" class="mystickyelements-popup-close" onClick="window.history.back()"><svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" width="10px" height="10px" version="1.1" style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd" viewBox="0 0 2.19 2.19" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:xodm="http://www.corel.com/coreldraw/odm/2003"><path class="fil0" d="M1.84 0.06c0.08,-0.08 0.21,-0.08 0.29,0 0.08,0.08 0.08,0.21 0,0.29l-0.75 0.74 0.75 0.75c0.08,0.08 0.08,0.21 0,0.29 -0.08,0.08 -0.21,0.08 -0.29,0l-0.75 -0.75 -0.74 0.75c-0.08,0.08 -0.21,0.08 -0.29,0 -0.08,-0.08 -0.08,-0.21 0,-0.29l0.74 -0.75 -0.74 -0.74c-0.08,-0.08 -0.08,-0.21 0,-0.29 0.08,-0.08 0.21,-0.08 0.29,0l0.74 0.74 0.75 -0.74z"></path></svg></a>
            <div class="popup-title">Create a new widget</div>
            <div class="popup-description">Would you like to load settings from an existing widget?</div>
            <form action="<?php echo admin_url("admin.php") ?>" method="get">
                <div class="select-field">
                    <input type="hidden" name="page" value="my-sticky-elements-new-widget" />
                    <select name="copy-from" >
                        <option value="">No thanks</option>
                        <?php
						$elements_widgets = get_option( 'mystickyelements-widgets' );
						$total_widgets = count($elements_widgets);
                        $menu_text = esc_attr__('Settings', 'mystickyelements');
                        if($total_widgets > 0) {
                            $menu_text = "Settings Widget #1";                            
							$text = $elements_widgets[0];
                            if(!empty($text) && $text != "" && $text != null ) {
                                $menu_text = "Settings " . $text;
                            }
                        } else {
                            $total_widgets = 0;
                        }			
                        echo "<option value='0'>{$menu_text}</option>";                        
                        if (!empty($elements_widgets) && $total_widgets != null && is_numeric($total_widgets) && $total_widgets > 0) {
                            for ($i = 1; $i < $total_widgets; $i++) {
								$widget_title = "Settings ".$elements_widgets[$i];
								echo "<option value='{$i}'>{$widget_title}</option>";
                                
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="select-field-btn">
                    <button type="submit" class="popup-form-btn">Start Editing</button>
                </div>
            </form>
        </div>
    </div>
<?php } ?>