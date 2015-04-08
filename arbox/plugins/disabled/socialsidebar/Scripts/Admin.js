//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

var ADMIN = {
	Item_List: '',
	Item_Cnt:  0
	};

jQuery(document).ready(function()
{
	// Bind: Buttons
	jQuery("#A3SCS-Add-Link").click(function() { ADMIN.Add_Item(); });

	// Bind: Form
	jQuery("#A3SCS-Form").submit(function() { ADMIN.Build_Items(); });
	
	//Load Items
	ADMIN.Load_Items();
});

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

//**********************************************************
// ADMIN >> Code
// PARAM >> Int | Length
// NOTES >> Generate a randomized code.
//**********************************************************
ADMIN.Code = function(Length)
{	
	Length = Length || 10;
	var Chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz";
	var Code  = '';

	for (var i = 0; i < Length; i++)
	{
		var Rand = Math.floor(Math.random() * Chars.length);
		Code += Chars.substring(Rand, Rand + 1);
	}
	return Code;
};

//**********************************************************
// ADMIN >> Template
// PARAM >> String | Template
// PARAM >> Object | Args
// PARAM >> String | Container
//**********************************************************
ADMIN.Template = function(Template, Args, Container)
{
	var Str = jQuery("#Template-" + Template).html();
		Str = ADMIN.Template_Parse(Str, Args);

	if (Container) $(Container).html(Str);
	return Str;
};

//**********************************************************
// ADMIN >> Template: Parse
// PARAM >> String | Template
// PARAM >> Object | Args
//**********************************************************
ADMIN.Template_Parse = function(Template, Args)
{
	Template = Template.replace(/\{{(.*?)\}}/g,
	function(Markup, Content)
	{
		Content = Content.split('.');
		Count   = Content.length;

		if (Count > 1)
		{
			var Initial = Args[Content[0]];
			var Explode = Content.splice(1, Count);
			for (var i = 0; i < Explode.length; i++)
			{
				Initial = Initial[Explode[i]];
			}
			return Initial;
		}
		else { return Args[Content] }
	});
	return Template;
};

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

//**********************************************************
// PROTO >> String: Title
// NOTES >> Capitalizes the first letter of each word.
//**********************************************************
String.prototype.Title = function()
{
	return this.toLowerCase().replace(/_/g, ' ')
	.replace(/\b([a-z\u00C0-\u00ff])/g,
	function (_, Initial) { return Initial.toUpperCase();
	}).replace(/(\s(?:de|a|o|e|da|do|em|ou|[\u00C0-\u00ff]))\b/ig,
	function (_, Match) { return Match.toLowerCase(); });
};

//**********************************************************
// PROTO >> String: Cut
// PARAM >> Int  | Length
// PARAM >> Bool | Trail
//**********************************************************
String.prototype.Cut = function(Length, Trail)
{
	Length = Length || false;
	Trail  = Trail  || true;

	if (!Length || this.length <= Length) return this.toString();
	else
	{
		if (Trail) return this.substring(0, Length - 3) + "...";
		else { return this.substring(0, Length); }
	}
};

//**********************************************************
// PROTO >> String: Commas
// NOTES >> Add commas to numbers eg: 1,000,000
//**********************************************************
String.prototype.Commas = function()
{
	var Str = this.toString() + '',
		X   = Str.split('.'),
		X1  = X[0],
		X2  = X.length > 1 ? '.' + X[1] : '',
		Rx  = /(\d+)(\d{3})/;
	while (Rx.test(X1)) { X1 = X1.replace(Rx, '$1' + ',' + '$2'); }
	return X1 + X2;
};

//**********************************************************
// PROTO >> String: Search
// PARAM >> String | A
// PARAM >> String | B
// NOTES >> Grab a section of string based on A/B.
//**********************************************************
String.prototype.Search = function(A, B)
{
	var Str = this.toString();
		Str = Str.substring(Str.indexOf(A) + A.length);
		Str = Str.substring(0, Str.indexOf(B));
	return Str;
};

//**********************************************************
// PROTO >> String: Trim
// NOTES >> Trim extra spaces and line breaks.
//**********************************************************
String.prototype.Trim = function()
{
	return $.trim(this.replace(/(\r\n|\n|\r)/gm, " ").replace(/\s+/g, " "));
};

//**********************************************************
// PROTO >> String: Strip
// PARAM >> String | Type
// PARAM >> String | Allowed
// NOTES >> Strip out special characters and HTML tags.
//**********************************************************
String.prototype.Strip = function(Type, Allowed)
{
	var Tags = /<\/?([a-z][a-z0-9]*)\b[^>]*>/gi;
	var PHP  = /<!--[\s\S]*?-->|<\?(?:php)?[\s\S]*?\?>/gi;

		 if (Type === "Special") 		  { return this.replace(/[^a-zA-Z 0-9]+/g, ''); }
	else if (Type === "Tags" && !Allowed) { return this.replace(/(<([^>]+)>)/g, '');    }
	else
	{
		Allowed = (((Allowed || '') + '').toLowerCase().match(/<[a-z][a-z0-9]*>/g) || []).join('');
		return this.replace(PHP, '').replace(Tags, function($0, $1)
		{ return Allowed.indexOf('<' + $1.toLowerCase() + '>') > -1 ? $0 : ' '; });
	}
};

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

//**********************************************************
// ADMIN >> Add Item
// PARAM >> Object | Item
//**********************************************************
ADMIN.Add_Item = function(Item)
{
	// Generate Code
	var Code = ADMIN.Item_Cnt + ADMIN.Code(20);
	ADMIN.Item_List += Code + "|";
	ADMIN.Item_Cnt++;

	// Create / Append Div
	var Str = ADMIN.Template("Item", {CODE:Code});
	jQuery("#Links-Sort").append(Str);

	// Fill Fields
	try	{ jQuery("#A3SCS-" + Code + "-Name").val(Item.name); } catch(e){}
	try	{ jQuery("#A3SCS-" + Code + "-URL" ).val(Item.url ); } catch(e){}
	try	{ jQuery("#A3SCS-" + Code + "-Icon").val(Item.icon); } catch(e){}
	try	{ if (Item.nofollow)  jQuery("#A3SCS-" + Code + "-NoFollow" ).prop("checked", true); } catch(e){}
	try	{ if (Item.newwindow) jQuery("#A3SCS-" + Code + "-NewWindow").prop("checked", true); } catch(e){}

	jQuery("#A3SCS-" + Code).hide().fadeIn(400);

	if (jQuery("#Links-List").css("display") === "none") jQuery("#Links-List").fadeIn(400);

	jQuery("#Links-Sort").sortable({ 
		placeholder: "ui-state-highlight", 
		handle: ".Link-Item-Handle", 
		update: function(event, ui) { ADMIN.Item_Order() } });
};

//**********************************************************
// ADMIN >> Item: Order
//**********************************************************
ADMIN.Item_Order = function()
{
	ADMIN.Item_List = '';
	jQuery("#Links-List li div.Link-Item-ID").each(function()
		{ ADMIN.Item_List += jQuery(this).text() + "|"; });
	ADMIN.Build_Items();
};

//**********************************************************
// ADMIN >> Item: Remove
// PARAM >> String | Code
//**********************************************************
ADMIN.Item_Remove = function(Code)
{
	var List = ADMIN.Item_List.split("|");	
	for (var Str = '', i = 0, Len = List.length - 1; i < Len; i++)
	{
		if (List[i] != Code)
			Str += List[i] + "|";
	}
	ADMIN.Item_List = Str;
	
	jQuery("#A3SCS-" + Code).hide(200, function()
	{
		jQuery("#A3SCS-" + Code).remove();
		if (ADMIN.Item_List === '') jQuery("#Links-List").hide();
	});
	ADMIN.Build_Items();
};

//**********************************************************
// ADMIN >> Build: Items
//**********************************************************
ADMIN.Build_Items = function()
{
	var List = ADMIN.Item_List.split("|");
	
	for (var Str = '', i = 0, Len = List.length - 1; i < Len; i++)
	{
		var Code      = List[i];
		var Name      = jQuery("#A3SCS-" + Code + "-Name").val().trim();
		var URL       = jQuery("#A3SCS-" + Code + "-URL" ).val().trim();
		var Icon      = jQuery("#A3SCS-" + Code + "-Icon").val();
		var NoFollow  = jQuery("#A3SCS-" + Code + "-NoFollow" ).is(':checked');
		var NewWindow = jQuery("#A3SCS-" + Code + "-NewWindow").is(':checked');
		
		if (Code !== '' && Name !== '' && URL !== '')
		{
			Name = Name.replace(/@@/g, '').replace(/\|\|\|/g, '');
			URL  = URL.replace(/@@/g, '').replace(/\|\|\|/g, '');
			
			NoFollow  = NoFollow  ? "True" : "False";
			NewWindow = NewWindow ? "True" : "False";
			
			Str += Name + "@@";
			Str += URL  + "@@";
			Str += Icon + "@@";
			Str += NoFollow  + "@@";
			Str += NewWindow + "|||";
		}
	}
	
	jQuery("#A3SCS-Links-List").val(Str);
};

//**********************************************************
// ADMIN >> Load: Items
//**********************************************************
ADMIN.Load_Items = function()
{
	var List = jQuery("#A3SCS-Links-List").val();
	if (List === '') return;
	
	List = List.split("|||");
	
	for (var i = 0, Len = List.length - 1; i < Len; i++)
	{
		if (List[i] !== '')
		{	
			var Item = List[i].split("@@");
			var NoFollow  = Item[3] === "True" ? true : false;
			var NewWindow = Item[4] === "True" ? true : false;
			var Args = {
				name: Item[0],
				url:  Item[1],
				icon: Item[2],
				nofollow: NoFollow,
				newwindow: NewWindow
				};
	
			ADMIN.Add_Item(Args);
		}
	}
};