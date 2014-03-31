// JavaScript Document
function pageload(hash) {  
		if (hash) 
		{
			var myString = hash.substr(hash.indexOf("/") + 1)  
			var streetaddress= myString.substr(0, myString.indexOf('-')); 
			if(streetaddress=='category')
			{				
				getPage(hash.split('-')[1]);  
			}
			else if(streetaddress=='product')
			{
				$("#smallLoader").css({display: "block"});
					getItem(myString.split('-')[1]);
			}
	
				
		}
		else 
		{
			alert(hash);
			/*var defaultcatID =  $('ul.nav li:first a').attr('rel');
			getPage(defaultcatID);    
			$('ul.nav li:first a').addClass('active');*/
			defaultcatID =  $('#category').attr('rel');
			var data = 'OnloadProduct=' + locationid;
			$.ajax({
			url: SITEURL+"/ajax/productlist.php",	
			type: "GET",		
			data: data,		
			cache: false,
			success: function (html) {	
				$("#smallLoader").css({display: "none"});				
				$('#productview').html(html);		
			}		
		});
		}
	}
		
	function getPage(cat_id) 
	{
//	alert(cat_id);
		var data = 'categoryid=' + cat_id;
		$.ajax({
			url: SITEURL+"/ajax/productlist.php",	
			type: "GET",		
			data: data,		
			cache: false,
			success: function (html) {	
				$("#smallLoader").css({display: "none"});				
				$('#productview').html(html);		
			}		
		});
	}
	function getItem(itemid) 
	{
		var data = 'Item=' + itemid;		
					$.ajax({
						url: SITEURL+"/ajax/toppingbyproduct.php",	
						type: "GET",		
						data: data,		
						cache: false,
						success: function (html) {	
							$("#smallLoader").css({display: "none"});
						   $('#productview').html(html);   		
						}		
					});
	}
