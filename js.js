/*Javascript code*/
	 window.onload = function()
     {
       var aList = document.getElementsByTagName(a);
       var iCount = aList.length;
       for(var i = 0;i<iCount;i++)
       {
       	if(aList[i].target != "_blank")
         {
           aList[i].herf = 'http://ac.jobdu.com/' + aList[i].herf;
         }
       }
     }