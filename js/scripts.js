jQuery(document).ready(function()
{
    /*
    $('#Menu a').each(function() {
        $(this).mouseover(function() {
            alert('over');
            return false;
        });
        
        $(this).mouseout(function() {
            alert('out');
            return false;
        });
    });       
    */
    
    //$('#SQLDebugInfo #QueryTime span')
    
     
});

function addDebugBubble(obj, text)
{
    $(obj).CreateBubblePopup({
         position : 'top',
         align : 'center',

         innerHtml: text,

         innerHtmlStyle: {
         color:'#ffffff',
         'text-align':'center'
         },

         themeName: 'all-black',
         themePath: 'images/jquerybubblepopup-theme'
    });    
}