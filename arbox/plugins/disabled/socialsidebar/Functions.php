<?php
//**********************************************************
// CLASS >> A3SCS
// NOTES >> Houses social sidebar functions and data.
//**********************************************************
class A3SCS {

//**********************************************************
// A3SCS >> Settings Link
// PARAM >> Array  | Links
// PARAM >> String | File
// NOTES >> Creates a settings link for the plugin page.
//**********************************************************
public static function Settings_Link( $Links, $File )
{
	static $Check;

	if ( ! $Check )
	{
		$Check = plugin_basename( __FILE__ );
		$Check = str_replace( "Functions.php", "index.php", $Check );
	}

	if ( $File === $Check )
	{
		$Blog = get_bloginfo( "wpurl" );
		$Link = "<a href='$Blog/wp-admin/options-general.php?page=A3SCS'>Settings</a>";
		array_unshift( $Links, $Link );
	}
	return $Links;
}

//*************************************************************
// A3SCS >> Admin: Init
// NOTES >> Register settings and sanitization callback.
//*************************************************************
public static function Admin_Init()
{
	$Opt_Group = "A3SCS_Options";
	$Opt_Name  = "A3SCS";
	$Sanitize  = array( "A3SCS", "Admin_Sanatize" );

	register_setting( $Opt_Group, $Opt_Name, $Sanitize );
}

//*************************************************************
// A3SCS >> Admin: Menu
// NOTES >> Add admin page to Settings menu.
//*************************************************************
public static function Admin_Menu()
{
	$Page_Title = "Social Sidebar";
	$Menu_Title = "Social Sidebar";
	$Capability = "manage_options";
	$Menu_Slug  = "A3SCS";
	$Function   = array( "A3SCS", "Admin_Page" );

	add_options_page( $Page_Title, $Menu_Title, $Capability, $Menu_Slug, $Function );
}

//*************************************************************
// A3SCS >> Admin: Page
// NOTES >> Build admin page HTML markup.
//*************************************************************
public static function Admin_Page()
{
	if ( ! current_user_can( "manage_options" ) )
		wp_die( "You do not have sufficient permissions to access this page." );

	wp_enqueue_style( "dashboard" );
	wp_enqueue_script( "dashboard" );
	wp_enqueue_script( "jquery" );
	wp_enqueue_script( "jquery-ui-sortable" );

	include_once( "Admin.php" );
}

//*************************************************************
// A3SCS >> Admin: Sanatize
// PARAM >> Array | input
// NOTES >> Sanatize admin page input.
//*************************************************************
public static function Admin_Sanatize( $Input )
{
	// Value Sanatizing
	$Input['Links_List'] = wp_filter_nohtml_kses( A3SCS::Slashes( $Input['Links_List'] ) );

	return $Input;
}

//*************************************************************
// A3SCS >> Uninstall
// NOTES >> Remove options for clean uninstall.
//*************************************************************
public static function Uninstall()
{
	delete_option( "A3SCS" );
	delete_option( "A3SCS_Version" );
}

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

//*************************************************************
// A3SCS >> Slashes
// PARAM >> String | Value
// NOTES >> Add slashes to string if magic quotes is not on.
//*************************************************************
public static function Slashes( $Value )
{
	if ( !get_magic_quotes_gpc() ) $Value = addslashes( $Value );
	$Value = str_replace ( "\"", "&quot;", $Value );
	return $Value;
}

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

//*************************************************************
// A3SCS >> Style
// NOTES >> Output stylesheet link.
//*************************************************************
public static function Style()
{
	$URL = plugins_url( '/Styles/Social-Sidebar-Min.css' , __FILE__ );
	echo "<link rel='stylesheet' type='text/css' media='screen' id='Social-Sidebar-CSS' href='$URL' />";
}

//*************************************************************
// A3SCS >> Build
// NOTES >> Overall build function for meta tags.
//*************************************************************
public static function Build()
{
	// Variables
	$Options = get_option( "A3SCS" );
	$Styles  = '';
	$Links   = '';

	// Styles
	$Options['Position'] = ( isset( $Options['Position'] ) ? $Options['Position'] : "Left"   );
	$Options['Style'   ] = ( isset( $Options['Style'   ] ) ? $Options['Style'   ] : "Square" );
	$Options['Size'    ] = ( isset( $Options['Size'    ] ) ? $Options['Size'    ] : "Small"  );
	$Options['Theme'   ] = ( isset( $Options['Theme'   ] ) ? $Options['Theme'   ] : "Dark"   );
	$Options['Label'   ] = ( isset( $Options['Label'   ] ) ? $Options['Label'   ] : "Square" );
	
		if ( $Options['Position'] === "Left"  ) $Styles .= "Pos-Left";
	elseif ( $Options['Position'] === "Right" ) $Styles .= "Pos-Right";
	
	if ( $Options['Style'] === "Circle" ) $Styles .= " Circle";
	if ( $Options['Size']  === "Large"  ) $Styles .= " Large";

		if ( $Options['Theme'] === "Light" ) $Styles .= " Theme-Light";
	elseif ( $Options['Theme'] === "Trans" ) $Styles .= " Theme-Trans";
	elseif ( $Options['Theme'] === "Color" ) $Styles .= " Theme-Color";
	
		if ( $Options['Label'] === "Square" ) $Styles .= " Label-Square";
	elseif ( $Options['Label'] === "Curve"  ) $Styles .= " Label-Curve";
	elseif ( $Options['Label'] === "Round"  ) $Styles .= " Label-Round";
	elseif ( $Options['Label'] === "Fancy"  ) $Styles .= " Label-Fancy";
	
		if ( $Options['Shadow'] === "Bar"   ) $Styles .= " Shadow";
	elseif ( $Options['Shadow'] === "Links" ) $Styles .= " Shadow-All";

		if ( $Options['Corners'] === "Bar"   ) $Styles .= " Corners";
	elseif ( $Options['Corners'] === "Links" ) $Styles .= " Corners-All";
	
	// Links
	$Options['Links_List'] = ( isset( $Options['Links_List'] ) ? stripslashes( $Options['Links_List'] ) : false );
	
	$Link_List = explode( "|||", $Options['Links_List'] );
	
	foreach ( $Link_List as $Link )
	{
		$Link = explode( "@@", $Link );
		
		if ( $Link[0] !== '' )
		{		
			$Name = $Link[0];
			$URL  = $Link[1];
			$Icon = $Link[2];
			
			$NoFollow  = $Link[3] === "True" ? " rel='nofollow'"  : '';
			$NewWindow = $Link[4] === "True" ? " target='_blank'" : '';

			$Links .= "<li><a href='$URL' class='$Icon'$NoFollow$NewWindow><span>$Name</span></a></li>";
		}
	}
	
	// Begin Tag Output
	if ( $Options['Links_List'] )
	{
		echo "<!-- A3 / Social Sidebar -->\n";
		echo "<aside id='Social-Sidebar' class='$Styles'>";
		echo "<ul>$Links</ul>";
		echo "</aside>";
	}
}

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

} // End A3SCS Class
?>