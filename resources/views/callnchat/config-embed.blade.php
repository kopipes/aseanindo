(function(){
     let showWindowChat = false
     window.haloYelow = window.haloYelow || {};
     window.haloYelow.iframeWidth = '380px';
     window.haloYelow.iframeHeight = '85%';
 
     // button element
     var haloYelowChatButton = document.createElement('button');
     Object.assign(haloYelowChatButton.style, {
         "position": "fixed",
         "box-shadow": "0px 2px 2px 1px #0000003b",
         "border-radius": "100px",
         "width": "60px",
         "height": "60px",
         "right": "15px",
         "border": "none",
         "background-image": "url({{asset('widget/widget_callchat_logo_new.png')}})",
         "background-position": "center",
         "background-size": "100%",
         "background-repeat": "no-repeat",
         "cursor": "pointer",
         "bottom": "11px",
         "transition" : "width 0.5s ease 0s",
         "z-index" : "10000000000000000"
     });
     //
 
     // iframe element
     var haloYelowChatFrame = document.createElement('iframe');
     haloYelowChatFrame.src = "{{route('callnchat.index',[$company_username,'contact'])}}";
     haloYelowChatFrame.id = "haloYelowChatFrame";
     haloYelowChatFrame.allow = "autoplay; camera; microphone;geolocation 'self' {{url('')}}";
     haloYelowChatFrame.oncontextmenu = "return false";
     Object.assign(haloYelowChatFrame.style, {
         "background": "none",
         "border": "0px",
         "float": "none",
         "position": "absolute",
         "inset": "0px",
         "width":  "100%",
         "height": "100%",
         "margin": "0px",
         "padding": "0px",
         "min-height": "0px",
         "border-radius": "15px",
     });
     //
     var boxIframeElement = document.createElement("div")
     boxIframeElement.setAttribute('id','haloyelow-callnchat')
     Object.assign(boxIframeElement.style,{
         "width":  window.haloYelow.iframeWidth,
         "height": window.haloYelow.iframeHeight,
         "bottom" : 0,
         "opacity" : 0,
         "pointer-events" : "none",
     })
     boxIframeElement.appendChild(haloYelowChatFrame)
 
     haloYelowChatButton.addEventListener("click", function(){
         if(!showWindowChat){
             showWindowChat = true
             Object.assign(haloYelowChatButton.style, {
                 "width": "60px",
                 "background-image": "url({{asset('widget/widget_callchat_logo_new.png')}})",
                 "background-size": "100%",
             });
             Object.assign(boxIframeElement.style,{
                 "opacity" : 1,
                 "pointer-events" : "auto",
                 "bottom" : "85px",
             })
         }else{
             showWindowChat = false
             Object.assign(haloYelowChatButton.style, {
                 "background-image": "url({{asset('widget/widget_callchat_logo_new.png')}})",
                 "background-size": "100%",
                 "background-repeat": "no-repeat",
             });
             Object.assign(boxIframeElement.style,{
                 "opacity" : 0,
                 "pointer-events" : "none",
                 "bottom" : "0",
             })
         }
     });
     window.haloYelow.haloYelowChatButton = haloYelowChatButton
     window.haloYelow.boxIframeElement = boxIframeElement
 
     var css = document.createElement('link')
     css.setAttribute('rel','stylesheet')
     css.setAttribute('href',"{{asset('widget/config.css')}}")
 
     document.querySelector('body').appendChild(boxIframeElement);
     document.querySelector('body').appendChild(haloYelowChatButton);
     document.querySelector('body').appendChild(css)

 })();